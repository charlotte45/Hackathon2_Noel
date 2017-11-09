$(document).ready(function(){
    $('#countdown').timeTo({
        timeTo: new Date(new Date('Mon dec 25 2017 00:00:00 GMT+0100 (CET)')),
        displayDays: 2,
        theme: "black",
        displayCaptions: true,
        fontSize: 48,
        captionSize: 14,
        lang:'fr'
    });
});
