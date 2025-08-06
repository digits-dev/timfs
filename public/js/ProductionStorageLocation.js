$(document).ready(function() {
    $("#storage_location_description").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});