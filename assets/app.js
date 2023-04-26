/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

import { Tooltip, Toast, Popover } from 'bootstrap'

// start the Stimulus application
import './bootstrap';

const $ = require('jquery');

import AOS from 'aos';
// ..
AOS.init();

// create global $ and jQuery variables
global.$ = global.jQuery = $;


$(document).ready(function () {

    // Gets the video src from the data-src on each button

    var $videoSrc;
    $('.video-btn').click(function () {
        $videoSrc = $(this).data("src");
    });



    // when the modal is opened autoplay it  
    $('#modalVideo').on('shown.bs.modal', function (e) {
        // set the video src to autoplay and not to show related video. Youtube related video is like a box of chocolates... you never know what you're gonna get
        $("#video").attr('src', "https://www.youtube.com/embed/" + $videoSrc + "?rel=0&autoplay=1&amp;modestbranding=1&amp;showinfo=0");
    })



    // stop playing the youtube video when I close the modal
    $('#modalVideo').on('hide.bs.modal', function (e) {
        // a poor man's stop video
        $("#video").attr('src', "https://www.youtube.com/embed/" + $videoSrc);
    })


    $("#News_etablissement").on("change", function (e) {
        console.log("cocoooooooooooooooooo");
    })
    // document ready  
});


var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new Tooltip(tooltipTriggerEl)
})