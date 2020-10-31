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

        $consumerKey = $_ENV['TWITTER_CONSUMER_KEY'];
        $consumerSecret = $_ENV['TWITTER_CONSUMER_SECRET'];
        $accessToken = $_ENV['TWITTER_ACCESS_TOKEN'];
        $accessTokenSecret = $_ENV['TWITTER_ACCESS_TOKEN_SECRET'];
        $rapidApiKey = $_ENV['RAPID_KEY'];

        $botometer = new Botometer($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret, $rapidApiKey);
    
        // Check a single account by screen name
        $resultadoJSON = $botometer->checkAccount( '@'.$username );
        $objJSON = json_decode($resultadoJSON); 
        if ($objJSON->user->majority_lang == 'en')
            $scores = json_encode($objJSON->display_scores->english);
        else
            $scores = json_encode($objJSON->display_scores->universal);

      	$request->session()->flash('display_scores', json_decode($scores, true));
   $display_scores = json_decode($scores, true);
          return redirect('/clasificacion/botometer/'.$username)->with('display_scores', json_decode($scores, true));
        //  return view('puntajeBotometer')
    }

    public function resultado(Request $request, $username) {
		$display_scores = $request->session()->get('display_scores');
		return view('puntajeBotometer')->with('username', $username)->with('display_scores', $display_scores);
    }
}
