/**
 * Pages to Skip
 * @returns {boolean}
 */
function skipPage() {
  const skipPages = [
    "page-product-preview",
    "page-course-view",
    "page-dashboard",
    "page-contact",
    "page-account",
    "page-account-delete",
    "page-question",
    "page-course",
    "page-user-login",
    "page-user-register",
    "page-user-password",
    "page-user-passwordCreate"
  ];
  const pageId = $("body").prop("id");

  // Within the Page, Skip it.
  if (_.indexOf(skipPages, pageId) !== -1) {
    return true;
  }

  return false;
}

$(() => {

  // Need accessible in two methods below, thus "var" is used.
  const navbar = $("#header .navbar");
  const titlebar = $("#title-bar");

  if (skipPage() === false) {
    navbar.addClass("navbar-fixed-top");
    navbar.addClass("navbar-transparent");
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
  }

});
