$(document).ready(function() {
    $("#category_description").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});