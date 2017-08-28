import _ from "lodash";
import axios from "axios";
import './components/axios'
import swal from "sweetalert2";
const Promise = require('es6-promise').Promise;


/**
 * =======================================================================
 * Set the users unique CSRF tokenKey/token for all Ajax Calls
 * -----------------------------------------------------------------------
 */
$(() => {

  /**
   * =======================================================================
   * Load Pages and Elements
   * -----------------------------------------------------------------------
   */
  require("./components/forms");

  require("./pages/auth");
  require("./pages/checkout");
  require("./pages/contact");
  require("./pages/dashboard");
  require("./pages/newsletter");
  require("./pages/promotion");
  require("./pages/purchase");
  require("./pages/question");
  require("./pages/user");


});



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
    $("html, body").animate({ scrollTop: 0 }, "slow");
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

  $(".disable-click").click(function() {
    $(this).addClass("disabled");
    return true;
  });

  if ($.isFunction($.fn.dataTable)) {
    $(".data-table").dataTable();
  }

  $(document).scroll(function() {
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
  $("#toggle-timezone").click(evt => {
    evt.preventDefault();
    $("#form-timezone").toggleClass("hide");
  });


  // Course-View, Lights Off
  $(".toggle-lights").click(evt => {
    evt.preventDefault();
    const overlay = $(".overlay");

    if (overlay.hasClass("hide")) {
      $(".overlay").removeClass("hide");
    } else {
      $(".overlay").addClass("hide");
    }
  });


});
