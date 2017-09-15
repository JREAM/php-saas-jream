// -----------------------------------------------------------------------------
// Document Ready
// -----------------------------------------------------------------------------
$(() => {

  // -----------------------------------------------------------------------------

  $('.toggle-lights').click(evt => {
    evt.preventDefault();
    const overlay = $('.overlay');

    if (overlay.hasClass('hide')) {
      $('.overlay').removeClass('hide');
      return;
    }

    $('.overlay').addClass('hide');
  });{

  // -----------------------------------------------------------------------------

  $(".course-action").click(evt => {
    evt.preventDefault();
    // T
    $(this).data('content-id');
    alert('add-feature');
    //there is course-mark and course-unmark -- same on the view and list page, diff looking btns
  });

  // -----------------------------------------------------------------------------

});
