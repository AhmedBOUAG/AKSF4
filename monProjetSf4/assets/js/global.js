$(function() {
    $('.next > a').html('<i class="fas fa-forward"></i>')
    $('.last > a').html('<i class="fas fa-step-forward"></i>')
    $('.first > a').html('<i class="fas fa-step-backward"></i>')
    $('.previous > a').html('<i class="fas fa-backward"></i>')

    function explode() {
        $('ins.ads-adsense').slideDown(1000)
        setTimeout(function() {
            $('.ads-adsense').show().slideToggle(500)
        }, 8000)
    }

    function getRandomInt(max) {
        var r = Math.floor(Math.random() * Math.floor(max))
        return r
    }
    /**
     * Random pour afficher l'annonce publicitaire en haut de la page.
     */
    if (getRandomInt(3) === 0) {
        setTimeout(explode, 3500)
    }
    new Typed('#typed', {
            stringsElement: '#typed-strings',
            typeSpeed: 65,
            loop: true,
            cursorChar: '_',
        })
        /**
         * handling the video thumbnail
         */
    $('.card-deck > .card')
        .mouseenter(function() {
            $(this).find('.card-filigrane').slideDown(200)
        })
        .mouseleave(function() {
            $(this).find('.card-filigrane').slideUp(200)
        })
})