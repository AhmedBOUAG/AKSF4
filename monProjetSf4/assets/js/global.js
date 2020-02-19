$(function () {
    $('.next > a').html('<i class="fas fa-forward"></i>');
    $('.last > a').html('<i class="fas fa-step-forward"></i>');
    $('.first > a').html('<i class="fas fa-step-backward"></i>');
    $('.previous > a').html('<i class="fas fa-backward"></i>');
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
})