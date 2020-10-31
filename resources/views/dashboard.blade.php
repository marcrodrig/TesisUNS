@extends('layouts.default')

@section('title', 'Inicio')

@section('content')
    <div class="jumbotron">
		<h1 class="display-4">Tesis UNS</h1>
		<p class="lead">Tomando tres algoritmos de Machine Learning se desarrollan tres modelos para predecir si una cuenta pública de Twitter puede ser un bot o no. La detección de bots no es una tarea fácil, se utilizan muchas características para tal fin, por lo que el resultado obtenido puede no ser certero.</p>
		<div class="d-flex justify-content-center"><a class="btn btn-success btn-lg" href="{{ route('clasificacion') }}" role="button">Ir a Detector Bot</a></div>
		<hr class="my-4">
		<p>Los algoritmos empleados son <i>K vecinos más cercanos (K Nearest Neighbors)</i>, <i>Naïve Bayes Gaussiano (Gaussian Naive Bayes)</i> y <i>Bosque aleatorio (Random Forest)</i>.</p>
		<p class="lead"><a class="btn btn-info btn-sm" href="{{ route('data') }}" role="button">Más...</a></p>
  </div>
@stop