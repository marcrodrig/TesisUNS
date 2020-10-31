<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data;
use App\Models\Metrica;
use Carbon\Carbon;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Persisters\Serializers\Igbinary;

class DetectorController extends Controller
{
    public function index() {
        return view('clasificacion');
    }

    public function check(Request $request) {
        $request->validate([
            'username' => ['required', 'regex:/^[A-Za-z0-9_]{1,15}$/']
        ]);

        $username = $request->input('username');
        $settings = array(
            'oauth_access_token' => env('TWITTER_ACCESS_TOKEN'),
            'oauth_access_token_secret' => env('TWITTER_ACCESS_TOKEN_SECRET'),
            'consumer_key' => env('TWITTER_CONSUMER_KEY'),
            'consumer_secret' => env('TWITTER_CONSUMER_SECRET')
        );

        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $getfield = '?screen_name='.$username.'&count=30&tweet_mode=extended';        
        $requestMethod = 'GET';
        $twitter = new \TwitterAPIExchange($settings);
        $json =  $twitter->setGetfield($getfield)
                     ->buildOauth($url, $requestMethod)
                     ->performRequest();
        $tweetsPrediccion = json_decode($json, true);

        $cantidadTweetsPrediccion = count($tweetsPrediccion);
        $predictData = [];
        $hashtagTotalCount = 0;
        $mentionTotalCount = 0;
        $retweetTotalCount = 0;
        $tweetFavouriteTotalCount = 0;
        $httpTotalCount = 0;
        $listaTweetID = [];
        foreach($tweetsPrediccion as $tweet) {
            //dd($tweet);
            $data['TweetID'] = $tweet['id'];
            $data['TextData'] = $tweet['full_text'];
            $data['TweetCreatedAt'] = $tweet['created_at'];
            $data['RetweetCount'] = $tweet['retweet_count'];
            $retweetTotalCount = $retweetTotalCount + $data['RetweetCount'];
            $data['TweetFavouriteCount'] = $tweet['favorite_count'];
            $tweetFavouriteTotalCount = $tweetFavouriteTotalCount + $data['TweetFavouriteCount'];
            $data['TweetSource'] = $tweet['source'];
    
            $data['UserID'] = $tweet['user']['id'];
            $data['UserScreenName'] = $tweet['user']['screen_name'];
            $data['UserName'] = $tweet['user']['name'];
            $data['UserCreatedAt'] = $tweet['user']['created_at'];
            $data['UserDescription'] = $tweet['user']['description'];
            $data['UserDescriptionLength'] = strlen($tweet['user']['description']);
            $data['UserFollowersCount'] = $tweet['user']['followers_count'];
            $data['UserFriendsCount'] = $tweet['user']['friends_count'];
            $data['UserLocation'] = $tweet['user']['location'];
 
            $url_count = 0;
            foreach(explode(" ",$data['TextData']) as $string) {
                $url = parse_url($string);
                if (gettype($url)=='array' &&  array_key_exists('scheme', $url) && array_key_exists('host', $url)) {
                    $url_count = $url_count + 1;
                }
            }
			$data['HttpCount'] = $url_count;
			
            $httpTotalCount = $httpTotalCount + $data['HttpCount'];
            $data['HashtagCount'] = substr_count($tweet['full_text'], '#');
            $hashtagTotalCount = $hashtagTotalCount + $data['HashtagCount'];
            $data['MentionCount'] = substr_count($tweet['full_text'], '@');
            $mentionTotalCount = $mentionTotalCount + $data['MentionCount'];
            $data['TweetCount'] = $tweet['user']['statuses_count'];

            array_push($predictData, $data);
            array_push($listaTweetID, $data['TweetID']);
            $user = $tweet['user'];
        }

        if($user['protected']) {
            $errors['protected'] = 'El usuario '.$username.' tiene la cuenta protegida';
            return view('clasificacion',compact('username'))->withErrors($errors);
            // ->withInput()??
        }

        # Extracción de características para la predicción
        foreach($predictData as &$data) {
            // agregar if 0s?
            $data['AvgHashtag'] = $hashtagTotalCount / 30;
            $data['AvgURLCount'] = $httpTotalCount / 30;
            $data['AvgMention'] = $mentionTotalCount / 30;
            $data['AvgRetweet'] = $retweetTotalCount / 30;
            $data['AvgFavCount'] = $tweetFavouriteTotalCount / 30;


            $data['Reputation'] = $data['AvgRetweet']/$data['UserFollowersCount'];
            $data['CurrentDate'] = Carbon::now();
            $date = Carbon::parse($data['UserCreatedAt']);
            $data['AgeOfAccount'] = $date->diffInDays($data['CurrentDate']);
            $data['TweetPerDay'] = $data['TweetCount']/$data['AgeOfAccount'];
            $data['TweetPerFollower'] = $data['TweetCount']/$data['UserFollowersCount'];
            if ($data['UserFriendsCount'] > 0)
                $data['AgeByFollowing'] = $data['AgeOfAccount']/$data['UserFriendsCount'];    
            else
                $data['AgeByFollowing'] = 0;
            if (preg_match('/Bot|bot|b0t|B0t|BOT/', $data['UserScreenName']))
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
        }
        unset($data);
        $toPredict = [
            $predictData[0]['Reputation'],
            $predictData[0]['AvgHashtag'],
            $predictData[0]['AvgRetweet'],
            $predictData[0]['UserFollowersCount'],
            $predictData[0]['UserFriendsCount'],
            $predictData[0]['AvgFavCount'],
            $predictData[0]['AvgMention'],
            $predictData[0]['AvgURLCount'],
            $predictData[0]['TweetCount'],
            $predictData[0]['AgeOfAccount'],
            $predictData[0]['TweetPerDay'],
            $predictData[0]['TweetPerFollower'],
            $predictData[0]['AgeByFollowing'],
            $predictData[0]['screen_name_binary'],
            $predictData[0]['name_binary'],
            $predictData[0]['description_binary'],
        ];

        // Obtengo los datos de la base de datos que no
        // fueron proveídos por el dataset inicial
        $dataset = Data::all()->toArray();
        $samplesKNNGNB = [];
        $labelsKNNGNB = [];
        $samplesRF = [];
        $labelsRF = [];

        $samples = [];
        $labels = [];
        foreach($dataset as $index => &$data) {
            $data['Reputation'] = $data['AvgRetweet']/$data['UserFollowersCount'];
            $data['CurrentDate'] = Carbon::parse($data['CurrentDate']);
            $date = Carbon::parse($data['UserCreatedAt']);
            $data['AgeOfAccount'] = $date->diffInDays($data['CurrentDate']);
            $data['TweetPerDay'] = $data['TweetCount']/$data['AgeOfAccount'];
            $data['TweetPerFollower'] = $data['TweetCount']/$data['UserFollowersCount'];
            if ($data['UserFriendsCount'] > 0)
                $data['AgeByFollowing'] = $data['AgeOfAccount']/$data['UserFriendsCount'];    
            else
                $data['AgeByFollowing'] = 0;
            if (preg_match('/Bot|bot|b0t|B0t|BOT/', $data['UserScreenName']))
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
            if ($data['bot'] == 0)
                $label = 'noBot';
            else
                $label = 'bot';
            array_push(
                $samples, 
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
            array_push($labels, $label);
        }
        unset($data);

        $datasetEntero = new Labeled($samples, $labels);
        $samplesRestante = array_slice($samples, 3188);
        $labelsRestante = array_slice($labels, 3188);

        $datasetRestante = new Labeled($samplesRestante, $labelsRestante);

        // Obtengo el clasificador entrenado inicialmente y los entreno con
        // los datos restante de la base de datos.
        // Luego, se predice el username requerido
        $persister = new Filesystem(public_path().'/model/knn_entrenamiento_inicial.model', false, new Igbinary());
        $estimatorKNN = $persister->load();
        if($datasetRestante->numRows() > 0)
            $estimatorKNN->partial($datasetRestante);

        $persister = new Filesystem(public_path().'/model/gnb_entrenamiento_inicial.model', false, new Igbinary());
        $estimatorGNB = $persister->load();
        if($datasetRestante->numRows() > 0)
			$estimatorGNB->partial($datasetRestante);
			
        $persister = new Filesystem(public_path().'/model/rf_entrenamiento_inicial.model', false, new Igbinary());
        $estimatorRF = $persister->load();
        $estimatorRF->train($datasetEntero);
        
        $predictionKNN = $estimatorKNN->predictSample($toPredict);
        $predictionGNB = $estimatorGNB->predictSample($toPredict);
        $predictionRF = $estimatorRF->predictSample($toPredict);
        
        $predicciones['K vecinos más cercanos'] = intval($predictionKNN === 'bot');
        $predicciones['Naïve Bayes Gaussiano'] = intval($predictionGNB === 'bot'); 
        $predicciones['Bosque aleatorio'] = intval($predictionRF === 'bot'); 
		$prediccion = intval($predictionKNN === 'bot' | $predictionGNB === 'bot' | $predictionRF === 'bot');
		
		$metricas = Metrica::all();

        Data::updateOrCreate(
            ['UserID' => $predictData[0]['UserID'],
             'UserCreatedAt' => $predictData[0]['UserCreatedAt']
            ],
            ['UserScreenName' => $predictData[0]['UserScreenName'],
             'UserName' => $predictData[0]['UserName'],
             'UserDescription' => $predictData[0]['UserDescription'],
             'UserDescriptionLength' => $predictData[0]['UserDescriptionLength'],
             'UserFollowersCount' => $toPredict[3],
             'UserFriendsCount' => $toPredict[4],
             'UserLocation' => $predictData[0]['UserLocation'],
             'AvgHashtag' => $toPredict[1],
             'AvgURLCount' => $toPredict[7],
             'AvgMention' => $toPredict[6],
             'AvgRetweet' => $toPredict[2],
             'AvgFavCount' => $toPredict[5],
             'TweetCount' => $toPredict[8],
             'CurrentDate' => $predictData[0]['CurrentDate']->toDateString(),
             'bot' => $prediccion],
        );
 
        session(['listaTweetID' => $listaTweetID]);

        return view('clasificacion', compact('username', 'metricas','predicciones','cantidadTweetsPrediccion'));
    }
}