/**
 * Obsługa multi-multiselecta
 */
$(document).ready(function() {

	// ustawienie jednakowej szerokości inputów (nawet pustych)
	iMinWidth = $('.formMultiChoose .options').css('min-width').replace(/px/, "");
	iOptWidth = $('.formMultiChoose .options').width();
	iSelWidth = $('.formMultiChoose .selected').width();

	if(iOptWidth > iSelWidth && iOptWidth > iMinWidth)
	{
		$('.formMultiChoose .selected').width(iOptWidth);
		$('.formMultiChoose .selected').css('min-width', iOptWidth);
		$('.formMultiChoose .options').css('min-width', iOptWidth);
	}
	else if(iSelWidth > iOptWidth && iSelWidth > iMinWidth)
	{
		$('.formMultiChoose .options').width(iSelWidth);
		$('.formMultiChoose .options').css('min-width', iSelWidth);
		$('.formMultiChoose .selected').css('min-width', iSelWidth);
	}

	// przeniesienie elementu do wybranych
	$('.multiButtons .add').click(function(){
		var aOpt = $('.formMultiChoose .options option:selected');
		$('.formMultiChoose .selected').append(aOpt);
		return false;
	});

	// usunięcie elementu z wybranych
	$('.multiButtons .rmv').click(function(){
		var aOpt = $('.formMultiChoose .selected option:selected');
		$('.formMultiChoose .options').append(aOpt);
		return false;
	});

	// oznaczenie wybranych wartości przed submitem formularza
	$('.formMultiChoose').parents('form').submit(function(){
		$('.formMultiChoose .selected option').attr('selected', 'selected');
	});
});