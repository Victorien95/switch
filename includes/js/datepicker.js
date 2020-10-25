

jQuery(document).ready(function() {
    jQuery.datetimepicker.setLocale('fr');
})
;

$(function () {
    $('#date_arrivee').datetimepicker({
        inline: true,
        sideBySide: true,
        firstDay: 1
    });
    $('#date_depart').datetimepicker({
        inline: true,
        sideBySide: true
    });
});