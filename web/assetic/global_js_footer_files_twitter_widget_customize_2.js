$(document).ready(function(){
    hideTwitterBoxElements();
});

$(window).resize(function(){
    resizeTwitterBoxElements();
});


function hideTwitterBoxElements() {
    setTimeout(function () {
        if ($('#twitter-widget-0').length) {
            $('#twitter-widget-0').each(function () {
                var thisWidget = $(this);
                var ibody = $(this).contents().find('body');
                if (ibody.find('.timeline .stream .h-feed li.tweet').length) {
                    ibody.find('.timeline .stream .h-feed li.tweet .e-entry-title').css("font-size", "18");
                    ibody.find('.timeline .stream .h-feed li.tweet .e-entry-title').css("line-height", "1.5");
                    ibody.find('.timeline .stream .h-feed li.tweet .e-entry-title').css("color", "#1692b0");
                    ibody.find('.timeline .stream .h-feed li.tweet .p-name').css("font-size", "22");
                    ibody.find('.timeline .stream .h-feed li.tweet .p-name').css("line-height", "1.5");
                    ibody.find('.timeline .stream .h-feed li.tweet .p-name').css("color", "#0c687e");
                    ibody.find('.timeline .stream .h-feed li.tweet a').css("text-decoration", "none");
                    ibody.find('.timeline .stream .h-feed li.tweet a b').css("text-decoration", "none");
                    ibody.find('.timeline .stream .h-feed li.tweet a span').css("text-decoration", "none")
                    ibody.find('.timeline-header').hide();
                    ibody.find('.timeline .stream .h-feed li.tweet .footer .customisable-highlight').hide();
                    ibody.find('.timeline .stream .h-feed li.tweet .header .p-nickname').hide();
                    //ibody.find('.timeline .stream .h-feed li.tweet .footer').hide();
                    ibody.find('.timeline .stream .h-feed li.tweet.customisable-border').css("border-width", "0");
                } else {
                    $(this).hide();
                }

                $("#twitter-loader").delay(200).fadeOut("fast", function(){
                    thisWidget.css("height", ibody.height()).css("width", "100%");
                    $("#twitter-tl").removeClass("no-display");
                })
            });

        }
    }, 2000);
}
function resizeTwitterBoxElements(){
    if ($('#twitter-widget-0').length) {
        $('#twitter-widget-0').each(function () {
            var thisWidget = $(this);
            var ibody = $(this).contents().find('body');

            thisWidget.css("height", ibody.height()).css("width", "100%");

        });

    }
}