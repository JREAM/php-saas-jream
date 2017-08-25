window._ = require("lodash");
window.axios = require("axios");

window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest'
};

window.ajax = require('./components/ajax.js');

window.CSRF = "overwriteme";
/**
 * =======================================================================
 * Load Dependencies
 * -----------------------------------------------------------------------
 */
try {
  // These JS Files are loaded individually, so it's off right now.
  // window.$ = window.jQuery = require('jquery');
  // require('bootstrap-sass');
} catch (e) {
}

/**
 * =======================================================================
 * CSRF: jQuery - This will go to API Controller, Event Handler
 * -----------------------------------------------------------------------
 */

$.ajaxSetup({
  dataType: "json",
  cache: false
});


$(() => {

  $(document).ajaxStart(function (evt) {
    "use strict";
  });

  // Globally Handles XHR and applies CSRF token if one exists.
  $(document).ajaxComplete(function (evt, xhr, req) {
    "use strict";
    // ECMA6, if Object Property Exists "csrf"
    if (!!xhr.responseJSON.csrf) {
      $("input[data-name='csrf']").attr("name", xhr.responseJSON.csrf.tokenKey);
      $("input[data-name='csrf']").attr("value", xhr.responseJSON.csrf.token);
      // Separated by a COMMA, Key => Token, make sure to split.
      window.csrf = `${xhr.responseJSON.csrf.tokenKey},${xhr.responseJSON.csrf.token}`;
      $("meta[name=\"csrf-token\"]").attr("content", `${xhr.responseJSON.csrf.tokenKey},${xhr.responseJSON.csrf.token}`);

      const z = $("meta[name=\"csrf-token\"]").attr("content");
      console.log(z);
    }
  });
});

/**
 * =======================================================================
 * Load Pages and Elements
 * -----------------------------------------------------------------------
 */
require("./components/scrolling");
require("./pages/auth");
require("./pages/checkout");
require("./pages/contact");
require("./pages/dashboard");
require("./pages/devtools");
require("./pages/newsletter");
require("./pages/purchase");
require("./pages/promotion");
require("./pages/purchase");
require("./pages/question");
require("./pages/user");

/**
 * =======================================================================
 * Modernizr: SVG's
 * -----------------------------------------------------------------------
 */
if (!Modernizr.svg) {
  const imgs = $("img");
  const svgExtension = /.*\.svg$/;

  for (let i = 0; i < imgs.length; i++) {
    if (imgs[i].src.match(svgExtension)) {
      imgs[i].src = `${imgs[i].src.slice(0, -3)}png`;
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
    $("#logo").addClass("hide");
    $("#logo-ico").removeClass("hide");
  } else {
    $("#logo").removeClass("hide");
    $("#logo-ico").addClass("hide");
  }
}


$(() => {

  $("[data-toggle=tooltip]").tooltip();
  $("[data-toggle=popover]").popover({
    trigger: "hover"
  });

  $("a[href='#top']").click(() => {
    $("html, body").animate({scrollTop: 0}, "slow");
    return false;
  });

  if ($.isFunction($.fn.autosize)) {
    $("textarea .autosize").autosize();
  }

  if ($.isFunction($.fn.expander)) {
    $("div.expandable").expander({
      slicePoint: 150,
      expandPrefix: " ",
      expandText: "(more)",
      collapseTimer: 5000,
      userCollapseText: "(less)",
      expandEffect: "slideDown",
      collapseEffect: "slideUp",
      preserveWords: true
    });
  }

  $(".disable-click").click(function () {
    $(this).addClass("disabled");
    return true;
  });

  if ($.isFunction($.fn.dataTable)) {
    $(".data-table").dataTable();
  }

  $(document).scroll(function () {
    const scroll = $(this).scrollTop();

    if (scroll > 600) {
      $("#goto-top").removeClass("hide");
    } else {
      $("#goto-top").addClass("hide");
    }
  });

  toggleLogo();

  // Detect when to show min logo
  $(window).resize(() => {
    toggleLogo();
  });

  // For the Dashboard/Account
  $("#toggle-timezone").click(function (evt) {
    evt.preventDefault();
    $("#form-timezone").toggleClass("hide");
  });


  // Course-View, Lights Off
  $(".toggle-lights").click(function (evt) {
    evt.preventDefault();
    const overlay = $(".overlay");

    if (overlay.hasClass("hide")) {
      $(".overlay").removeClass("hide");
    } else {
      $(".overlay").addClass("hide");
    }
  });


});
