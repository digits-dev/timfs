$(document).ready(function() {
    $("#transfer_price_category_description").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});