let widgets = document.getElementById("widgets");
let spinnerTweets = document.getElementById("spinnerTweets");
let itemsCarousel = $('.carousel-inner').children();
let segundaSeccionTweets = false;
let terceraSeccionTweets = false;
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
			// Primeros 10 Tweets
            axios.get('https://tesis-uns.herokuapp.com/widgets1')
            .then(function (response) {
                var widgetsTweets = response.data;
				for (var index = 0; index < widgetsTweets.length; index++) {
					var target=itemsCarousel[index];
                    target.innerHTML=widgetsTweets[index].html;
                    twttr.widgets.load(target);
				}
               // twttr.widgets.load(document.getElementById("primeraSeccionTweets"));
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

$('.carousel').carousel({ interval: false });

$('.carousel').on('slide.bs.carousel', function (event) {
    var target=itemsCarousel[event.to];
	if ($(target).is(':empty')) {
        if (event.to >=10 && event.to <= 19 && !segundaSeccionTweets) {
            segundaSeccionTweets = true;
            axios.get('https://tesis-uns.herokuapp.com/widgets2')
            .then(function (response) {
                var widgetsTweets = response.data;
				for (var index = 0; index < widgetsTweets.length; index++) {
					var target=itemsCarousel[10 + index];
                    target.innerHTML=widgetsTweets[index].html;
                    twttr.widgets.load(target);
				}
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            });
        }
        if (event.to >=20 && event.to <= 29 && !terceraSeccionTweets) {
            terceraSeccionTweets = true;
            axios.get('https://tesis-uns.herokuapp.com/widgets3')
            .then(function (response) {
                var widgetsTweets = response.data;
				for (var index = 0; index < widgetsTweets.length; index++) {
					var target=itemsCarousel[20 + index];
                    target.innerHTML=widgetsTweets[index].html;
                    twttr.widgets.load(target);
				}
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            });
        }
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

$('#modalTweets').on('hidden.bs.modal', function (e) {
	// Volver a primer item
	$('.carousel').carousel(0);
	var target=itemsCarousel[0];
	if ($(target).not(':empty'))
		widgets.style.visibility = "visible";
});
 