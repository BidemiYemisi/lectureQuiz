
$(document).ready(function () {

    $('.custom').hide();

    $("#custom_parent").change(function () {

        if ($(this).val() == "show") {
            $('.custom').show();
        }
        else {
            $('.custom').hide();
        }

    });

});
$(document).ready(function () {

    $('.custom1').hide();

    $("#custom_parent1").change(function () {

        if ($(this).val() == "show") {
            $('.custom1').show();
        }
        else {
            $('.custom1').hide();
        }

    });

});
$(document).ready(function () {

    $('.custom2').hide();

    $("#custom_parent2").change(function () {

        if ($(this).val() == "show") {
            $('.custom2').show();
        }
        else {
            $('.custom2').hide();
        }

    });

});
