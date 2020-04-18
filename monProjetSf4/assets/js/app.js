/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');
require('../css/affogato.css');
require('../css/dropzone.css');
require('../css/checkboxnradio.min.css');
require('../css/comment.css');
require('../css/animate-icon.css');
require('../css/control.MiniMap.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');
const $ = require('jquery');
global.$ = global.jQuery = $;
// app.js
require('./dropzone');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');
require('bootstrap/dist/css/bootstrap.css');
require('@fortawesome/fontawesome-free/css/all.min.css');

var Typed = require('typed.js');
global.Typed = Typed;
require('leaflet');
require('leaflet/dist/leaflet.css');
// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');
require('./global');
require('./osm-leaflet');
$(document).ready(function () {
    $('[data-toggle="popover"]').popover();

    var width = $(window).width();
    console.log(width);
});
