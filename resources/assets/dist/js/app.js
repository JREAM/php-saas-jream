'use strict';

window._ = require('lodash');
window.axios = require('axios');
window.swal = require('sweetalert2');
window.Promise = require('es6-promise').Promise;

/**
 * =======================================================================
 * Set the users unique CSRF tokenKey/token for all Ajax Calls
 * -----------------------------------------------------------------------
 */
$(function () {

  /**
   * Tokens for XHR Calls
   */
  var csrfSelector = $('meta[name=\'csrf\']');
  var tokenKey = csrfSelector.attr('data-key');
  var token = csrfSelector.attr('data-token');

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
    'X-CSRFToken': tokenKey + '|' + token
  };

  /**
   * =======================================================================
   * Load Pages and Elements
   * -----------------------------------------------------------------------
   */
  require('./components/interceptors');
  require('./components/notifications');
  require('./components/forms');

  require('./pages/auth');
  require('./pages/checkout');
  require('./pages/contact');
  require('./pages/dashboard');
  require('./pages/newsletter');
  require('./pages/promotion');
  require('./pages/purchase');
  require('./pages/question');
  require('./pages/user');
});

/**
 * =======================================================================
 * Modernizr: SVG's
 * -----------------------------------------------------------------------
 */
if (!Modernizr.svg) {
  var imgs = $('img');
  var svgExtension = /.*\.svg$/;

  for (var i = 0; i < imgs.length; i++) {
    if (imgs[i].src.match(svgExtension)) {
      imgs[i].src = imgs[i].src.slice(0, -3) + 'png';

      // console.log(imgs[i].src);
    }
  }
}

/**
 * =======================================================================
 * Load: Vue Resources
 * -----------------------------------------------------------------------
 */
// window.Vue = require('vue');
// Vue.config.productionTip = false;

function toggleLogo() {
  if ($(window).width() < 980) {
    $('#logo').addClass('hide');
    $('#logo-ico').removeClass('hide');
  } else {
    $('#logo').removeClass('hide');
    $('#logo-ico').addClass('hide');
  }
}

$(function () {

  $('[data-toggle=tooltip]').tooltip();
  $('[data-toggle=popover]').popover({
    trigger: 'hover'
  });

  $('a[href=\'#top\']').click(function () {
    $('html, body').animate({ scrollTop: 0 }, 'slow');
    return false;
  });

  if ($.isFunction($.fn.autosize)) {
    $('textarea .autosize').autosize();
  }

  if ($.isFunction($.fn.expander)) {
    $('div.expandable').expander({
      slicePoint: 150,
      expandPrefix: ' ',
      expandText: '(more)',
      collapseTimer: 5000,
      userCollapseText: '(less)',
      expandEffect: 'slideDown',
      collapseEffect: 'slideUp',
      preserveWords: true
    });
  }

  $('.disable-click').click(function () {
    $(this).addClass('disabled');
    return true;
  });

  if ($.isFunction($.fn.dataTable)) {
    $('.data-table').dataTable();
  }

  $(document).scroll(function () {
    var scroll = $(this).scrollTop();

    if (scroll > 600) {
      $('#goto-top').removeClass('hide');
    } else {
      $('#goto-top').addClass('hide');
    }
  });

  toggleLogo();

  // Detect when to show min logo
  $(window).resize(function () {
    toggleLogo();
  });

  // For the Dashboard/Account
  $('#toggle-timezone').click(function (evt) {
    evt.preventDefault();
    $('#form-timezone').toggleClass('hide');
  });

  // Course-View, Lights Off
  $('.toggle-lights').click(function (evt) {
    evt.preventDefault();
    var overlay = $('.overlay');

    if (overlay.hasClass('hide')) {
      $('.overlay').removeClass('hide');
    } else {
      $('.overlay').addClass('hide');
    }
  });
});
//# sourceMappingURL=app.js.map