/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)

const $ = require('jquery');

global.$ = global.jQuery = $;


// $(".100vh-ignore-navbar").css({ "min-height": whrem })

// LATER FUCKIT 
// $(function (){
//     document.styleSheets.
//     const whrem = (1/16 * $(document).height()) - 3.6 + 'rem !important';
//     document.querySelector('100vh-ignore-navbar').style.minHeight = whrem;
// })

// console.log(whrem)
import './styles/app.scss';

require('bootstrap');


// start the Stimulus application
// import './bootstrap';
