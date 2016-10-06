$(function(){
	$('#sidebar >ul >li >ul').hide();
	$('#sidebar >ul >li').click(function(){
		$(this).children('ul').toggle();
	});
});