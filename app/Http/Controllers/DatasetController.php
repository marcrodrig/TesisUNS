<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Metrica;

class DatasetController extends Controller {
   
    public function index(Request $request) {
        $metricas = Metrica::all();
        return view('dataset', compact('metricas'));
    }

}