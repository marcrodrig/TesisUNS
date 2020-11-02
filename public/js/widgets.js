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
            axios.get('widgets')
            .then(function (response) {
				var widgetsTweets = response.data;
				for (var index = 0; index < widgetsTweets.length; index++) {
					var target=itemsCarousel[index];
                	target.innerHTML=widgetsTweets[index].html;
				}
                twttr.widgets.load(widgets);
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            });
      	}
  	);
}

function hideSpinnerTweets() {
	//console.log('hide spinner');
	spinnerTweets.style.display = "none";
}

$('.carousel').carousel('pause');

$('#modalTweets').on('hidden.bs.modal', function (e) {
	// Volver a primer item
	$('.carousel').carousel(0);
	var target=itemsCarousel[0];
	if ($(target).not(':empty'))
		widgets.style.visibility = "visible";
});
 