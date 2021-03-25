/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// require jQuery normally
import $ from 'jquery';
// create global $ and jQuery variables
global.$ = global.jQuery = $;
require('datatables.net-bs4')( window, $ );

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

import 'bootstrap';

//our js
import './js/boardTable';
import './js/messagePreview';
