<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Botometer\Botometer;

class BotometerController extends Controller
{
    public function index() {
        return view('botometer');
    }

    public function botometer(Request $request) {

        $request->validate([
            'username' => ['required', 'regex:/^[A-Za-z0-9_]{1,15}$/']
        ]);

        $username = $request->input('username');

        $consumerKey = env('TWITTER_CONSUMER_KEY');
        $consumerSecret = env('TWITTER_CONSUMER_SECRET');
        $accessToken = env('TWITTER_ACCESS_TOKEN');
        $accessTokenSecret = env('TWITTER_ACCESS_TOKEN_SECRET');
        $rapidApiKey = env('RAPID_KEY');

        // Chequeo si el usuario existe o no tiene tweets
        $settings = array(
            'oauth_access_token' => $accessToken,
            'oauth_access_token_secret' => $accessTokenSecret,
            'consumer_key' => $consumerKey,
            'consumer_secret' => $consumerSecret
        );
        $url = 'https://api.twitter.com/1.1/users/show.json';
        $getfield = '?screen_name='.$username;        
        $requestMethod = 'GET';
        $twitter = new \TwitterAPIExchange($settings);
        $json =  $twitter->setGetfield($getfield)
                     ->buildOauth($url, $requestMethod)
                     ->performRequest();
        $resultadoJSON = json_decode($json, true);
        if(array_key_exists('errors', $resultadoJSON)) {
            if($resultadoJSON['errors'][0]['code'] == 50) {
                $errors['undefined'] = 'El usuario '.$username.' no existe.';
                return view('botometer',compact('username'))->withErrors($errors);
            }
        }
        if($resultadoJSON['statuses_count'] == 0) {
            $errors['undefined'] = 'El usuario '.$username.' no tiene tweets.';
            return view('botometer',compact('username'))->withErrors($errors);
        }

        $botometer = new Botometer($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret, $rapidApiKey);
        // Check a single account by screen name
        $resultadoJSON = $botometer->checkAccount( '@'.$username );
        $objJSON = json_decode($resultadoJSON); 
        if ($objJSON->user->majority_lang == 'en')
            $scores = json_encode($objJSON->display_scores->english);
        else
            $scores = json_encode($objJSON->display_scores->universal);
        return redirect('/clasificacion/botometer/'.$username)->with('display_scores', json_decode($scores, true));
    }

    public function resultado(Request $request, $username) {
		$display_scores = $request->session()->get('display_scores');
		return view('puntajeBotometer')->with('username', $username)->with('display_scores', $display_scores);
    }
}
