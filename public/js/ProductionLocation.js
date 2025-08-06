$(document).ready(function() {
    $("#production_location_description").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});