const $ = require('jquery');

// create global $ and jQuery variables
global.$ = global.jQuery = $;


$(document).ready(function () {

    if ($("#News_etablissement").length > 0) {
        let selectedValue = $("#News_etablissement").val();
        console.log(selectedValue)
        if (selectedValue) {
            $(".date_limite_inscription").removeClass("d-none")
        }

        $("#News_etablissement").on("change", function (e) {
            let value = e.target.value;
            console.log(value)
            if (value) {
                $(".date_limite_inscription").removeClass("d-none")
            }
            else {
                $(".date_limite_inscription").addClass("d-none")
            }
        })
    }
    // document ready  
});