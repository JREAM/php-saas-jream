// -----------------------------------------------------------------------------
// Shrink Logo based on window size
// -----------------------------------------------------------------------------
function toggleLogo() {
  if ($(window).width() < 980) {
    $('#logo').addClass('hide');
    $('#logo-ico').removeClass('hide');
  } else {
    $('#logo').removeClass('hide');
    $('#logo-ico').addClass('hide');
  }
}

// -----------------------------------------------------------------------------
// Document Ready
// -----------------------------------------------------------------------------
(() => {

  // -----------------------------------------------------------------------------
  // Logo Resize with Window Size
  // -----------------------------------------------------------------------------

  toggleLogo();

  // Detect when to show min logo
  $(window).resize(() => {
    toggleLogo();
  });

  // -----------------------------------------------------------------------------
  // Enable Tooltips
  // -----------------------------------------------------------------------------

  $('[data-toggle=tooltip]').tooltip();

  // -----------------------------------------------------------------------------
  // Multiline Tooltips (Popovers)
  // -----------------------------------------------------------------------------
  // Customize the Popover to use HTML and a Header, it looks nice.
  // Also see the multiline-tooltips.scss file
  // -----------------------------------------------------------------------------
  $('body').popover({
    //Popover, activated by clicking
    selector: '[data-toggle=popover]',
    container: 'body',
    html: true,
  });

  // -----------------------------------------------------------------------------
  // Only Show One at a Time
  // -----------------------------------------------------------------------------
  $('[data-toggle=popover]').on('click', function(evt) {
    $('[data-toggle=popover]').not(this).popover('hide');
  });

  // -----------------------------------------------------------------------------
  // Text Expander
  // -----------------------------------------------------------------------------

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
      preserveWords: true,
    });
  }

  // -----------------------------------------------------------------------------
  // Disable Clicking Class
  // -----------------------------------------------------------------------------
  $('.disable-click').click(function() {
    $(this).addClass('disabled');
    return true;
  });

  // -----------------------------------------------------------------------------

});
