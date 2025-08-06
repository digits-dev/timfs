$(document).ready(function() {
    $("#asset_type_description").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});