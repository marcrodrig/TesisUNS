<div class="card">
  	<div class="card-header text-center font-weight-bold">Predicción</div>
  	<div class="card-body">
		<p class="text-center">A continuación se muestran los resultados obtenidos de distintos algoritmos de machine learning:</p>
    	<div class="card-deck">
			@foreach ($metricas as $metrica)
				<div class="card text-center">
					<div class="card-body">
						<h5 class="card-title">{{ $metrica->clasificador}}</h5>
						<x-resultado-prediccion prediccion="{{ $predicciones[$metrica->clasificador] }}" />
					</div>
				</div>
			@endforeach
    	</div>
    	La predicción se realizó en base a estos <a href="javascript:void(0);" data-toggle="modal" data-target="#modalTweets">{{ $cantidadTweetsPrediccion }} tweets</a>
  	</div>
</div>

<div class="modal fade" id="modalTweets" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header text-center">
				<h5 id="modalLabel">Tweets</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="height: 500px;">
				<div class="d-flex justify-content-center">
					<div class="spinner-border text-light" id="spinnerTweets" role="status">
						<span class="sr-only">Loading...</span>
					</div>
				</div>
				<div id="widgets" style="visibility: hidden">
					<div id="carouselIndicators" class="carousel slide carousel-fade">
						<ol class="carousel-indicators">
							@for ($index=0; $index<$cantidadTweetsPrediccion; $index++)
								@if ($index==0)
									<li data-target="#carouselIndicators" data-slide-to="0" class="active"></li>
								@else
									<li data-target="#carouselIndicators" data-slide-to="{{ $index }}"></li>
								@endif
							@endfor
						</ol>
						<div class="carousel-inner">
							@for ($index=0; $index<$cantidadTweetsPrediccion; $index++)
								@if ($index==0)
									<div class="carousel-item active" data-interval="false"></div>
								@else
									<div class="carousel-item" data-interval="false"></div>
								@endif
							@endfor
						</div>
						<a class="carousel-control-prev" href="#carouselIndicators" role="button" data-slide="prev">
							<span class="carousel-control-prev-icon" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
						</a>
						<a class="carousel-control-next" href="#carouselIndicators" role="button" data-slide="next">
							<span class="carousel-control-next-icon" aria-hidden="true"></span>
							<span class="sr-only">Next</span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>