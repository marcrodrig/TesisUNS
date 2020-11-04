@extends('layouts.default')

@section('title', 'Clasificación')

@section('styles')
<link href="{{ asset('/css/modalWidgets.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/css/puntosSuspensivos.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('content')
<div class="container">
	<h2 class="text-center">Detector Bot</h2>
	<h5 class="card-subtitle text-center mb-1 text-muted">Ingrese el nombre de usuario para realizar la clasificación:</h5>
	<div class="d-flex justify-content-center">
		<form action=" {{ url('clasificacion/detector') }}" method="POST" onsubmit="spinnerPrediccion()">
		@csrf
			<div class="form-row align-items-center">
				<div class="col my-1">
					<label class="sr-only" for="username">Username</label>
					<div class="input-group">
						<div class="input-group-prepend">
							<div class="input-group-text">@</div>
						</div>
						<input type="text" class="form-control" name="username" id="username" value="{{ $username ?? '' }}" placeholder="Nombre de usuario" autocomplete="off" required>
					</div>
				</div>
				<div class="col-auto my-1">
					<button id="btn" type="submit" class="btn btn-primary">Chequear</button>
				</div>
			</div>
		</form>
	</div>
	@empty($predicciones)
		<div class="text-center mt-3">
			<img src="{{asset('img/bothuman.jpg')}}" id="imgBotHuman" class="rounded w-50" alt="bothuman">
		</div>
	@endempty
	<div class="d-flex justify-content-center">
		<div id="spinnerPrediccion" style="display: none;">
			<img id="imgSpinnerTesis" src={{ asset('/img/spinner.gif') }} alt="Cargando" style="display: block" />
			<p class="text-center">Verificando usuario
				<span class="dot-one">.</span>
				<span class="dot-two">.</span>
				<span class="dot-three">.</span>
			</p>
		</div>
	</div>
	@isset($predicciones)
		@include('includes.predicciones')
	@endisset
</div>
@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script type="text/javascript" src="{{ asset('js/widgetTwitter.js') }}"></script>
<script type="text/javascript">
	function spinnerPrediccion() {
		let cardPrediccion = document.getElementById("prediccion");
    	if (cardPrediccion !== null)
      		cardPrediccion.style.display = "none";
		document.getElementById("spinnerPrediccion").style.display = "block";
		let img = document.getElementById("imgBotHuman");
		if (img !== null)
			document.getElementById("imgBotHuman").style.display = "none";
  	}
</script>
@isset($predicciones)
<script type="text/javascript" src="{{ asset('js/widgets.js') }}"></script>
@endisset
@stop