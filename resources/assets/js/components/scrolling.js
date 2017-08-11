$(() => {


  // Need accessible in two methods below, thus "var" is used.
  var navbar = $("#header .navbar");
  var titlebar = $("#title-bar");

  $(".point").waypoint({
    handler: function (direction) {
      if (direction == "down") {
        titlebar.addClass("past-point-titlebar");
      }
      else {
        titlebar.removeClass("past-point-titlebar");
      }

    },
    offset: 65
  });

  // Darken navbar on scroll not attached to very top
  $("body").waypoint({
    handler: function (direction) {
      if (direction == "down") {
        navbar.addClass("past-point-nav-body");
      }
      else {
        navbar.removeClass("past-point-nav-body");
      }

    },
    offset: -50
  });

});
