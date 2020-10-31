@extends('layouts.default')

@section('title', 'Clasificación')

@section('styles')
<link href="{{ secure_asset('/css/modalWidgets.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ secure_asset('/css/puntosSuspensivos.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('content')
<div class="container">
	<h2 class="text-center">Clasificación</h2>
	<div class="d-flex justify-content-center">
		<form action=" {{ secure_url('clasificacion') }}" method="POST" onsubmit="spinnerPrediccion()">
		@csrf
			<div class="form-row align-items-center">
				<div class="col my-1">
					<label class="sr-only" for="username">Username</label>
					<div class="input-group">
						<div class="input-group-prepend">
							<div class="input-group-text">@</div>
						</div>
						<input type="text" class="form-control" name="username" id="username" value="{{ $username ?? '' }}" placeholder="Nombre de usuario" required>
					</div>
				</div>
				<div class="col-auto my-1">
					<button id="btn" type="submit" class="btn btn-primary">Chequear</button>
				</div>
			</div>
		</form>
	</div>
	<div class="d-flex justify-content-center">
		<div id="spinnerPrediccion" style="display: none;">
			<img id="imgSpinnerTesis" src={{ secure_asset('/img/spinner.gif') }} alt="Cargando" style="display: block" />
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
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script type="text/javascript" src="{{ secure_asset('js/widgetTwitter.js') }}"></script>
<script type="text/javascript">
	function spinnerPrediccion() {
      	document.getElementById("spinnerPrediccion").style.display = "block";
  	}
</script>
@isset($predicciones)
<script type="text/javascript" src="{{ secure_asset('js/widgets.js') }}"></script>
@endisset
@stop