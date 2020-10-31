<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Data;
use Carbon\Carbon;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Classifiers\NaiveBayes;
use Rubix\ML\CrossValidation\Metrics\Accuracy;
use Rubix\ML\Classifiers\KNearestNeighbors;
use Rubix\ML\Kernels\Distance\Manhattan;
use Rubix\ML\Classifiers\GaussianNB;
use Rubix\ML\Classifiers\RandomForest;
use Rubix\ML\CrossValidation\Metrics\FBeta;
use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Persisters\Serializers\Igbinary;
use Illuminate\Support\Facades\DB;

class MetricaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Predicción: 
         * 'Reputation', 'AvgHashtag', 'AvgRetweet', 'UserFollowersCount',
         * 'UserFriendsCount', 'AvgFavCount', 'AvgMention', 'AvgURLCount',
         * 'TweetCount', 'AgeOfAccount', 'TweetPerDay', 'TweetPerFollower',
         * 'AgeByFollowing', 'screen_name_binary', 'name_binary', 'description_binary'
         * Target:
         * 'bot'
         */
        $dataset = Data::all()->toArray();
        $x = [];
        $y = [];
        foreach($dataset as &$data) {
            $data['Reputation'] = $data['AvgRetweet']/$data['UserFollowersCount'];
            $data['Current_Date'] = Carbon::parse($data['CurrentDate']);
            $date = Carbon::parse($data['UserCreatedAt']);
            $data['AgeOfAccount'] = $date->diffInDays($data['CurrentDate']);
            $data['TweetPerDay'] = $data['TweetCount']/$data['AgeOfAccount'];
            $data['TweetPerFollower'] = $data['TweetCount']/$data['UserFollowersCount'];
            if ($data['UserFriendsCount'] > 0)
                $data['AgeByFollowing'] = $data['AgeOfAccount']/$data['UserFriendsCount'];    
            else
                $data['AgeByFollowing'] = 0;
            if (str_contains($data['UserScreenName'],'Bot'))
                $data['screen_name_binary'] = 1;
            else
                $data['screen_name_binary'] = 0;
            if (preg_match('/Bot|bot|b0t|B0t|BOT/', $data['UserName']))
                $data['name_binary'] = 1;
            else
                $data['name_binary'] = 0;
            if (preg_match('/Bot|bot|b0t|B0t|BOT/', $data['UserDescription']))
                $data['description_binary'] = 1;
            else
                $data['description_binary'] = 0;
            array_push(
                $x, 
                array(
                    $data['Reputation'],
                    floatval($data['AvgHashtag']),
                    floatval($data['AvgRetweet']),
                    $data['UserFollowersCount'],
                    $data['UserFriendsCount'],
                    floatval($data['AvgFavCount']),
                    floatval($data['AvgMention']),
                    floatval($data['AvgURLCount']),
                    $data['TweetCount'],
                    $data['AgeOfAccount'],
                    $data['TweetPerDay'],
                    $data['TweetPerFollower'],
                    $data['AgeByFollowing'],
                    $data['screen_name_binary'],
                    $data['name_binary'],
                    $data['description_binary']
                ),
            );
            if ($data['bot'] == 0)
                $label = 'noBot';
            else
                $label = 'bot';
            array_push($y, $label);
        }
        unset($data);
        
        $samples = $x;
        $labels = $y;
        $dataset = new Labeled($samples, $labels);
        [$training, $testing] = $dataset->randomize()->split(0.8);

        # K vecinos más cercanos
        $persister = new Filesystem(public_path().'/model/knn_entrenamiento_inicial.model', false, new Igbinary());
        
        $estimatorKNN = new KNearestNeighbors(3,true, new Manhattan());
        $estimatorKNN->train($training);

        $persister->save($estimatorKNN);

        $predictions = $estimatorKNN->predict($testing);
        $accuracy = new Accuracy();
        $score = $accuracy->score($predictions, $testing->labels());
        $exactitudes['knn'] = $score;
        $precision = new FBeta(0.7);
        $score = $precision->score($predictions, $testing->labels());
        $precisiones['knn'] = $score;

        # Naïve Bayes Gaussiano
        $persister = new Filesystem(public_path().'/model/gnb_entrenamiento_inicial.model', false, new Igbinary());

        $estimatorGNB = new GaussianNB();
        $estimatorGNB->train($training);

        $persister->save($estimatorGNB); 

        $predictions = $estimatorGNB->predict($testing);
        $score = $accuracy->score($predictions, $testing->labels());
        $exactitudes['gnb'] = $score;
        $score = $precision->score($predictions, $testing->labels());
        $precisiones['gnb'] = $score;

        # Bosque aleatorio
        $persister = new Filesystem(public_path().'/model/rf_entrenamiento_inicial.model', false, new Igbinary());

        $estimatorRF = new RandomForest();
        $estimatorRF->train($training);

        $persister->save($estimatorRF); 

        $predictions = $estimatorRF->predict($testing);
        $score = $accuracy->score($predictions, $testing->labels());
        $exactitudes['rf'] = $score;
        $score = $precision->score($predictions, $testing->labels());
        $precisiones['rf'] = $score;

        DB::table('metricas')->insert([
            'clasificador' => 'K vecinos más cercanos',
            'exactitud' => $exactitudes['knn'],
            'precision' => $precisiones['knn'],
        ]);
        DB::table('metricas')->insert([
            'clasificador' => 'Naïve Bayes Gaussiano',
            'exactitud' => $exactitudes['gnb'],
            'precision' => $precisiones['gnb'],
        ]);
        DB::table('metricas')->insert([
            'clasificador' => 'Bosque aleatorio',
            'exactitud' => $exactitudes['rf'],
            'precision' => $precisiones['rf'],
        ]);
    }
}
