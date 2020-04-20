$(document).ready(function() {
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