<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class WidgetController extends Controller
{
    public function widgetTweet() {
        $listaTweetID = request()->session()->get('listaTweetID');
        $widgets = [];
        foreach($listaTweetID as $tweetID) {
            $response = Http::get('https://publish.twitter.com/oembed?url=https://twitter.com/Interior/status/'.$tweetID.'&omit_script=true&hide_media=true&lang=es&align=center&width=550');
            array_push($widgets, $response->json());
        }
        return $widgets;
    }
}
