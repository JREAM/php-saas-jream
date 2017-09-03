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
      $(this).notify('Timezone Updated', "success");
    })
    .catch(function (error) {
      $(this).notify(err.msg, "error");
    });

  });

  // -----------------------------------------------------------------------------

  $("#formDashboardEmail").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(response => {
      $(this).notify('Your email was updated.', "success");
    })
    .catch(function (error) {
      $(this).notify(err.msg, "error");
    });

  });

  // -----------------------------------------------------------------------------

  $("#formDashboardNotification").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(response => {
      $(this).notify('Your notification settings have been updated.', "success");
    })
    .catch(function (error) {
      $(this).notify(err.msg, "error");
    });

  });

  // -----------------------------------------------------------------------------

});
