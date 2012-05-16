$(document).ready(function(){

	afterAccessChange();

	$('#access').change(afterAccessChange);


	function afterAccessChange()
	{
		if($('#access').val() == 'public')
		{
			$('.multi').hide();
		}
		else
		{
			$('.multi').show();
		}
	}
});