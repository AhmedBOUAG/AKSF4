$(function () {
    $('.next > a').html('<i class="fas fa-forward"></i>')
    $('.last > a').html('<i class="fas fa-step-forward"></i>')
    $('.first > a').html('<i class="fas fa-step-backward"></i>')
    $('.previous > a').html('<i class="fas fa-backward"></i>')

    function explode() {
        $('.ads-adsense').slideDown(1000);
        setTimeout(function () {
            $('.ads-adsense').show().slideToggle(500);
        }, 8000);
    }

    function getRandomInt(max) {
        var randVal = Math.floor(Math.random() * Math.floor(max));
        console.log(randVal);
        return randVal;
    }
    /**
     * Random pour afficher l'annonce publicitaire en haut de la page. 
     */
    if (getRandomInt(3) === 0) {
        setTimeout(explode, 3500);
    }
    new Typed('#typed', {
        stringsElement: '#typed-strings',
        typeSpeed: 65,
        loop: true,
        cursorChar: '_',
    });
    /**
     * handling the video thumbnail
     */
    $('.card-deck > .card').mouseenter(function () {
        $(this).find('.card-filigrane').slideDown(200);
    }).mouseleave(function () {
        $(this).find('.card-filigrane').slideUp(200);
    });
    /**
     * Display Map Aitkermoune in index page
     */
    var aitkermouneMap = L.map('map').setView([29.393176, -9.546011], 12);
    var icon = L.icon({
        iconUrl: '/uploads/images/marker-icon.png',
        shadowUrl: '/uploads/images/marker-shadow.png',
    });
    var marker = L.marker([29.405176, -9.553011], {icon: icon}).addTo(aitkermouneMap);
    marker.bindPopup("<center>Aitkermoune أيت كــــرمون</center>").openPopup();
    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    }).addTo(aitkermouneMap);
    L.polygon([
        [29.422764, -9.559811],
        [29.427986, -9.529214],
        [29.398826, -9.529339],
        [29.372337, -9.540111],
        [29.365301, -9.547235],
        [29.369251, -9.565196],
        [29.420446, -9.564592]
    ]).addTo(aitkermouneMap);
});
