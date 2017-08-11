$(() => {

  // Customize the Popover to use HTML and a Header, it looks nice.
  // Also see the multiline-tooltips.scss file
  $('body').popover({
    //Popover, activated by clicking
    selector: '[data-toggle=\'popover\']',
    container: 'body',
    html: true,
  });

  // Only Show One at a Time
  $('[data-toggle="popover"]').on('click', function(evt) {
    $('[data-toggle="popover"]').not(this).popover('hide');
  });

});