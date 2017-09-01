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
  $('[data-toggle=popover]').popover({
    trigger: 'hover'
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
      preserveWords: true
    });
  }

  // -----------------------------------------------------------------------------
  // Disable Clicking Class
  // -----------------------------------------------------------------------------

  $('.disable-click').click(function () {
    $(this).addClass('disabled');
    return true;
  });

  // -----------------------------------------------------------------------------
  // Footer Newsletter Subscribe
  // -----------------------------------------------------------------------------

  $("#formFooterNewsletterSubscribe").on("submit", function(evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).attr("postData");

    axios.post(url, postData).then(resp => {
      if (resp.result == 0) {
        throw resp.data;
      }

      swal({
        title: "Success",
        text: "Your email has been registered, please verify you email address in your inbox!",
        type: "success",
      });

    })
    .catch(err => {
      popError(err.msg);
    });

  });

  // -----------------------------------------------------------------------------

});
