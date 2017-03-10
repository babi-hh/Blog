$(document).ready(function () {
    initFlash();
});

function initFlash() {
    var flashes =  $('#flashes');
    if (!flashes.length || flashes.find('p').hasClass('error')) {
        return;
    }
    setTimeout(function() {
        flashes.slideUp('slow');
    },2000);
    
}

