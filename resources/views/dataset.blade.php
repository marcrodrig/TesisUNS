@extends('layouts.default')

@section('title', 'Datos')

@section('content')
<div class="container">
	<div class="card">
		<div class="card-header text-center font-weight-bold">Datos</div>
		<div class="card-body">
			<p class="card-text">En el siguiente repositorio GitHub se puede ver cómo se obtuvieron los datos para
				entrenar los clasificadores: <a target="_blank" href="https://github.com/marcrodrig/Dataset-Tesis-UNS/blob/master/C%C3%B3digo/Procesamiento%20para%20Datasets.ipynb">Dataset Tesis UNS</a></p>
			<table class="table table-hover table-bordered">
				<thead class="thead-dark">
					<tr>
						<th class="w-10">Dataset</th>
						<th class="w-50">Fuente</th>
						<th class="w-auto">Descripción</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Humanos</td>
						<td>
							<p class="text-justify">Twitter API (Tweepy): recolectados 2.500 usuarios verificados de los
								cuales se obtuvieron 75.000 tweets.</p>
						</td>
						<td>Dimensión: (75000, 19)<br>Características: 18<br>Target: 1 (bot)<br></td>
					</tr>
					<tr>
						<td>Bots</td>
						<td>
							<p class="text-justify">Twitter API (Tweepy): Para usuarios bots, se toman 670 usuarios del
								dataset <i>botwiki-2019</i> obtenido en 
								<a target="_blank" href="https://botometer.iuni.iu.edu/bot-repository/datasets.html">botometer.iuni.iu.edu/bot-repository/datasets.html</a>,
								de los cuales se obtuvieron 26.800 tweets.</p>
						</td>
						<td>Dimensión: (26800, 19)<br>Características: 18<br>Target: 1 (bot)<br></td>
					</tr>
					<tr>
						<td>Total</td>
						<td colspan="1">Se toman los datasets <i>Humanos</i> y <i>Bots</i>, se extraen algunas
							características y se agregan otras para la predicción.</td>
						<td>Dimensión: (3188, 17)<br>Características: 16<br>Target: 1 (bot)<br></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<div class="card mt-2">
		<div class="card-header text-center font-weight-bold">Clasificadores</div>
		<div class="card-body">
			<p class="card-text">Al entrenarse los clasificadores, se obtuvieron los siguientes resultados: </p>
			<table class="table table-hover table-bordered">
				<thead class="thead-dark">
					<tr>
						<th class="w-50">Modelo</th>
						<th class="w-25">Exactitud</th>
						<th class="w-25">Precisión</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($metricas as $metrica)
						<tr>
							<td>{{ $metrica->clasificador}}</td>
							<td>{{ $metrica->exactitud }}</td>
							<td>{{ $metrica->precision }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@stop