let widgets = document.getElementById("widgets");
let spinnerTweets = document.getElementById("spinnerTweets");
let itemsCarousel = $('.carousel-inner').children();
if (widgets.style.visibility === "hidden") {
    twttr.ready(
        function (twttr) {
            twttr.events.bind(
                'rendered',
                function (event) {
                    hideSpinnerTweets();
                    widgets.style.visibility = "visible";
                }
            ); 
			// Primer Tweet
            axios.get('widgetTweet/'+ 0)
            .then(function (response) {
                var target=itemsCarousel[0];
                target.innerHTML=response.data.html;
                twttr.widgets.load(target);
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            });
      	}
  	);
}

function showSpinnerTweets() {
	//console.log('show spinner');
	spinnerTweets.style.display = "block";
}

function hideSpinnerTweets() {
	//console.log('hide spinner');
	spinnerTweets.style.display = "none";
}

$('.carousel').on('slide.bs.carousel', function (event) {
	var target=itemsCarousel[event.to];
	if ($(target).is(':empty')) {
		//console.log('vacio');
		axios.get('widgetTweet/'+event.to)
			.then(function (response) {        
				target.innerHTML=response.data.html;
				twttr.widgets.load(target);
			})
			.catch(function (error) {
				// handle error
				console.log(error);
			}
		);
	} 
	else
		hideSpinnerTweets();
});

$('.carousel').on('slid.bs.carousel', function (event) {
	var target=itemsCarousel[event.to];
	if ($(target).is(':empty')) {
		showSpinnerTweets();
		widgets.style.visibility = "hidden";
	}
});

$('.carousel').carousel('pause');

$('#modalTweets').on('hidden.bs.modal', function (e) {
	// Volver a primer item
	$('.carousel').carousel(0);
	var target=itemsCarousel[0];
	if ($(target).not(':empty'))
		widgets.style.visibility = "visible";
});
 