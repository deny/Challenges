$(document).ready(function(){

	// link z potwierdzeniem
	$('.confirm').click(function(){

		if(confirm($(this).attr('data-ask')))
		{
			$(this).attr('href', $(this).attr('href').substr(1));
			return true;
		}

		return false;
	});

});