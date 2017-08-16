window._ = require("lodash");

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
 * Load Pages and Elements
 * -----------------------------------------------------------------------
 */
require("./components/scrolling");
require("./pages/checkout");
require("./pages/contact");
require("./pages/dashboard");
require("./pages/devtools");
require("./pages/product");
require("./pages/promotion");
require("./pages/register");

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
 * CSRF: jQuery
 * -----------------------------------------------------------------------
 */
// $.ajaxSetup({
//   headers: {
//     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
//   },
// });

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

  // $('.carousel').carousel({interval: 5000});
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
    var overlay = $(".overlay");

    if (overlay.hasClass("hide")) {
      $(".overlay").removeClass("hide");
    } else {
      $(".overlay").addClass("hide");
    }
  });


});
