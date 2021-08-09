$(document).ready(function(){
	$("input").change( function() {
		$("button[type='submit']").attr('disabled', false);
	});
	
	$("textarea").change( function() {
		$("button[type='submit']").prop('disabled', false);
		$("button[type='submit']").button('refresh');
	});
});