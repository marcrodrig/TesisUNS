<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class WidgetController extends Controller
{
    public function widgetTweetSeccion1() {
        $listaTweetID = request()->session()->get('listaTweetID');
        $widgets = [];
        if(count($listaTweetID) < 9)
            for ($index=0; $index < count($listaTweetID); $index++) { 
                $response = Http::get('https://publish.twitter.com/oembed?url=https://twitter.com/Interior/status/'.$listaTweetID[$index].'&omit_script=true&hide_media=true&lang=es&align=center&width=550');
                array_push($widgets, $response->json());
            }
        else
            for ($index=0; $index < 10; $index++) { 
                $response = Http::get('https://publish.twitter.com/oembed?url=https://twitter.com/Interior/status/'.$listaTweetID[$index].'&omit_script=true&hide_media=true&lang=es&align=center&width=550');
                array_push($widgets, $response->json());
            }
        return $widgets;
    }

    public function widgetTweetSeccion2() {
        $listaTweetID = request()->session()->get('listaTweetID');
        $widgets = [];
        if(count($listaTweetID) < 19)
            for ($index=0; $index < count($listaTweetID); $index++) { 
                $response = Http::get('https://publish.twitter.com/oembed?url=https://twitter.com/Interior/status/'.$listaTweetID[$index].'&omit_script=true&hide_media=true&lang=es&align=center&width=550');
                array_push($widgets, $response->json());
        }
        else
            for ($index=10; $index < 20; $index++) { 
                $response = Http::get('https://publish.twitter.com/oembed?url=https://twitter.com/Interior/status/'.$listaTweetID[$index].'&omit_script=true&hide_media=true&lang=es&align=center&width=550');
                array_push($widgets, $response->json());
            }
        return $widgets;
    }

    public function widgetTweetSeccion3() {
        $listaTweetID = request()->session()->get('listaTweetID');
        $widgets = [];
        if(count($listaTweetID) < 29)
            for ($index=0; $index < count($listaTweetID); $index++) { 
                $response = Http::get('https://publish.twitter.com/oembed?url=https://twitter.com/Interior/status/'.$listaTweetID[$index].'&omit_script=true&hide_media=true&lang=es&align=center&width=550');
                array_push($widgets, $response->json());
        }
        else
            for ($index=20; $index < 30; $index++) { 
                $response = Http::get('https://publish.twitter.com/oembed?url=https://twitter.com/Interior/status/'.$listaTweetID[$index].'&omit_script=true&hide_media=true&lang=es&align=center&width=550');
                array_push($widgets, $response->json());
            }
        return $widgets;
    }
}
