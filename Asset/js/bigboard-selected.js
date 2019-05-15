$(document).ready(function() {
	// select : view as a list
	$('body').on('click', '#listView', function() {
         $("div.selitem").css('display','block');
    });
	// select : view as one paragraph
	$('body').on('click', '#paragraphView', function() {
        $("div.selitem").css('display','inline-block');
    });
	// select : clear all
	$('body').on('click', '#clearAll', function() {
		 $("input[type='checkbox']:checked").click();
    });
	// select : select all
	$('body').on('click', '#selectAll', function() {
		$("input[type='checkbox']:not(:checked)").click();
    });
	// select : add starred projects to selection
	$('body').on('click', '#addStar', function() {
		$("input[type='checkbox'][class='fav']:not(:checked)").click();
    });
	// select : elect only starred projects
	$('body').on('click', '#onlyStar', function() {
		$("input[type='checkbox']:checked").click();
		$("input[type='checkbox'][class='fav']:not(:checked)").click();
    });	
});
