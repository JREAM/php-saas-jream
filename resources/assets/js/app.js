// -----------------------------------------------------------------------------
// Main Entry File
// -----------------------------------------------------------------------------
window._ = require('lodash');
window.axios = require('axios');
window.swal = require('sweetalert2');
window.Promise = require('es6-promise').Promise;
window.formUtils = require('./components/forms');

// -----------------------------------------------------------------------------
// Axios Interceptor
//
// @important For 200 requests with data.result == 0 I throw an Exception and pass
// the JSON Response to the catch(err => .. method.
// -----------------------------------------------------------------------------
require('./components/interceptors');

// -----------------------------------------------------------------------------
// Scrolling/Effects Features
// -----------------------------------------------------------------------------
require('./components/scrolling');

// -----------------------------------------------------------------------------
// Document Ready
// -----------------------------------------------------------------------------
$(() => {

  /**
   * =======================================================================
   * Set the users unique CSRF tokenKey/token for all Ajax Calls
   * -----------------------------------------------------------------------
   */
  const csrfSelector = $('meta[name=\'csrf\']');
  const tokenKey = csrfSelector.attr('data-key');
  const token = csrfSelector.attr('data-token');

  /**
   * Call when DOM is loaded to pass the tokenKey and token
   *
   * @param  {string} tokenKey
   * @param  {string} token
   *
   * @return object axios
   */
  axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
  };
  axios.defaults.responseType = 'json';

});

/**
 * =======================================================================
 * @important Load these after Axios is configured.
 *
 * Load Pages and Elements
 * Every page has it's own Document Ready
 * -----------------------------------------------------------------------
 */
require('./pages/auth');
require('./pages/checkout');
require('./pages/contact');
require('./pages/dashboard');
require('./pages/newsletter');
require('./pages/promotion');
require('./pages/purchase');
require('./pages/question');
require('./pages/user');
require('./global');

/**
 * =======================================================================
 * Modernizr: SVG's
 * -----------------------------------------------------------------------
 */
if (!Modernizr.svg) {
  const imgs = $('img');
  const svgExtension = /.*\.svg$/;

  for (let i = 0; i < imgs.length; i++) {
    if (imgs[i].src.match(svgExtension)) {
      imgs[i].src = `${imgs[i].src.slice(0, -3)}png`;
    }
  }
}

// -----------------------------------------------------------------------------
