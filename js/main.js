$(document).ready(function() {
    $( ".chest, .tricep, .back, .shoulder, .legs, .bicep,.numbers, p" ).draggable({
        helper: 'clone' ,
        snap: '.excerise',
        snapMode: 'inner'
    });
    
    $(".excerise,.sets,.reps").droppable({
        drop : function(event,ui) {
           var droppedItem = $(ui.draggable).clone();
           $(this).append(droppedItem);
        }
    });
    onClickMenu();

} );


function onClickMenu(){
    $('#menu').click(function(){
        $('#menu').toggleClass('change');
        $('#nav').toggleClass('change');
    })
    /*
    document.getElementById("menu").classList.toggle("change");
    document.getElementById("nav").classList.toggle("change");
    */
}