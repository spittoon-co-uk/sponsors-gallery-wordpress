window.addEventListener("load", function(event) {

	const sponsorsContainer = document.querySelectorAll('.sponsors-container');

	console.log(sponsorsContainer.length);

	for(var i = 0; i < sponsorsContainer.length; i++)
	{

		var sponsorCount = sponsorsContainer[i].getElementsByClassName('sponsor-link').length;

		console.log("count: "+sponsorCount);

		var sponsorWidth = 100 / sponsorCount;

		console.log("width: "+sponsorWidth);

		var sponsorLinks = sponsorsContainer[i].querySelectorAll('.sponsor-link');

		sponsorLinks.forEach(element => {
		  element.setAttribute('style', 'width:'+(sponsorWidth-2)+'%;');
		});

	}

});
