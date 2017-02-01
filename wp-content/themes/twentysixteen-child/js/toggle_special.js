jQuery(document).read(function($) {

    $('#shortcode_toggle').change(function() {
        if ($(this).prop('checked')) {
            // $("#special_zone").toggle("slow", function() {
            // Animation complete.
            // });
            alert("You have elected to show your checkout history."); //checked
        } else {
            alert("You have elected to turn off checkout history."); //not checked
        }
    });

});