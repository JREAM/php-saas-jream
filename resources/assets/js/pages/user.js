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

  $("#formDashboardTimezone").submit(function (evt) {
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

  $("#formDashboardEmail").submit(function (evt) {
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

  $("#formDashboardNotification").submit(function (evt) {
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
