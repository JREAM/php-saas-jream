// -----------------------------------------------------------------------------
// Document Ready
// -----------------------------------------------------------------------------
$(() => {

  // -----------------------------------------------------------------------------

  $('#toggle-timezone').click(evt => {
    evt.preventDefault();
    $('#form-timezone').toggleClass('hide');
  });

  // -----------------------------------------------------------------------------

  $("#formUpdateTimezone").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(response => {
      swal({
        title: 'Success',
        text: 'Your Timezone has been updated',
        type: 'success',
        timer: 3000
      })
    })
    .catch(function (error) {
      popError(error.msg);
    });

  });

  // -----------------------------------------------------------------------------

  $("#formUpdateEmail").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(response => {
      swal({
        title: 'Success',
        text: 'Your email was updated',
        type: 'success',
        timer: 3000
      })
    })
    .catch(function (error) {
      popError(error.msg);
    });

  });

  // -----------------------------------------------------------------------------

  $("#formUpdateNotificationsAction").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(response => {
      swal({
        title: 'Success',
        text: 'Your notification settings have been updated.',
        type: 'success',
        timer: 3000
      })
    })
    .catch(function (error) {
      popError(error.msg);
    });

  });

  // -----------------------------------------------------------------------------

});
