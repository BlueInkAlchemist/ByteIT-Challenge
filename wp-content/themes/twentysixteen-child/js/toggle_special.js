jQuery(document).ready(function($) {

    $('#shortcode_toggle').change(function() {
        if ($(this).prop('checked')) {
            $("#special_zone").hide("slow", function() {
                // Animation complete.
            });
            // alert("You have elected to turn off the shortcode area."); //checked
        } else {
            $("#special_zone").show("slow", function() {
                // Animation complete.
            });
            // alert("You have elected to turn on the shortcode area."); //not checked
        }
    });

});