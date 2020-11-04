@extends('layouts.default')

@section('title', 'Puntaje Botometer')

@section('styles')
<link href="{{ asset('/css/puntosSuspensivos.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('content')
<h2 class="text-center">Clasificación Botometer</h2>
<p><a target="_blank" href="https://botometer.osome.iu.edu/"><i>Botometer</i></a> (anteriormente BotOrNot) verifica la actividad de una cuenta de Twitter y le da una puntuación. Los puntajes más altos significan una actividad más similar a un bot.</p>
<div class="d-flex justify-content-center">
	<form action=" {{ url('clasificacion/botometer') }}" method="POST" onsubmit="spinnerBotometerAjax()">
		@csrf
		<div class="form-row align-items-center">
			<div class="col my-1">
				<label class="sr-only" for="username">Username</label>
				<div class="input-group">
					<div class="input-group-prepend">
						<div class="input-group-text">@</div>
					</div>
					<input type="text" class="form-control" name="username" id="username" value="{{ $username ?? '' }}"
						placeholder="Nombre de usuario" autocomplete="off" required>
				</div>
			</div>
			<div class="col-auto my-1">
				<button id="btn" type="submit" class="btn btn-primary">Obtener resultado Botometer</button>
			</div>
		</div>
	</form>
</div>
<div class="d-flex justify-content-center">
	<div id="spinnerBotometer" style="display: none;">
		<img id="imgSpinnerTesis" src={{ asset('/img/spinner.gif') }} alt="Cargando" style="display: block" />
		<div class="text-center">Obteniendo puntaje Botometer.</div>
		<p class="text-center">Puede tardar unos segundos
			<span class="dot-one">.</span>
			<span class="dot-two">.</span>
			<span class="dot-three">.</span>
		</p>
	</div>
</div>

@yield('scores')

@stop

@section('scripts')
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
	function spinnerBotometerAjax(){
    cardBotometer = document.getElementById("puntajeBotometer");
    if (cardBotometer !== null)
      cardBotometer.style.display = "none";
    document.getElementById("spinnerBotometer").style.display = "block";
  }
</script>
@stop