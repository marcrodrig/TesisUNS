<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class WidgetController extends Controller
{
    public function widgetTweet($index) {
        $listaTweetID = request()->session()->get('listaTweetID');
        $response = Http::get('https://publish.twitter.com/oembed?url=https://twitter.com/Interior/status/'.$listaTweetID[$index].'&omit_script=true&hide_media=true&lang=es&align=center&width=550');
        return $response->json();
    }
}
