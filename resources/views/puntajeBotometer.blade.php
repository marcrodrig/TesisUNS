@extends('botometer')

@section('scores')
<div class="card" id="puntajeBotometer">
	<div class="card-header">
		<h5>Puntaje Botometer</h5>
	</div>
	<div class="card-body">
		<button type="button" class="btn btn-info float-right" data-toggle="modal" data-target="#modalReferencias">
			Tipos de puntaje
		</button>
		@php $rating = $display_scores['overall']; @endphp
		<h5 class="card-title">Puntaje general</h5>
		<p>
			@include('includes.stars')
			{{ '('.$rating.')' }}
		</p>
		<h6 class="card-subtitle mb-2 text-muted">Subpuntajes</h6>
		@foreach ($display_scores as $key => $rating)

			@if($key != 'overall')
				@switch($key)
					@case('fake_follower')
						<p style="margin-bottom: 0px">Fake follower</p>
					@break

					@case('self_declared')
						<p style="margin-bottom: 0px">Self declared</p>
					@break

					@default
						<p style="margin-bottom: 0px">{{ Str::ucfirst($key) }}</p>
				@endswitch
				@include('includes.stars')
				{{ '('.$rating.')' }}
			@endif
		@endforeach
	</div>
</div>

<div class="modal fade" id="modalReferencias" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalLabel">Tipos de puntaje</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p><u>General:</u> Basado en una comparación de varios modelos entrenados en diferentes tipos de
					bots y en cuentas humanas, corresponde al modelo con mayor confianza.</p>
				<p><u>Astroturf:</u> Bots políticos etiquetados manualmente y cuentas involucradas en trenes de
					seguimiento que eliminan contenido sistemáticamente.</p>
				<p><u>Fake follower:</u> Bots comprados para aumentar el número de seguidores.</p>
				<p><u>Financial:</u> Bots que publican utilizando cashtags.</p>
				<p><u>Other:</u> Varios otros bots obtenidos a partir de anotaciones manuales, feedback de los
					usuarios, etc.</p>
				<p><u>Self declared:</u> Bots de botwiki.org.</p>
				<p><u>Spammer:</u> Cuentas etiquetadas como spambots de varios datasets.</p>
			</div>
		</div>
	</div>
</div>
@stop