jQuery(document).read(function($) {

    $('#shortcode_toggle').change(function() {
        if (this.checked) {
            $("#special_zone").toggle("slow", function() {
                // Animation complete.
            });
        } else {
            // the checkbox is now no longer checked
        }
    });

});