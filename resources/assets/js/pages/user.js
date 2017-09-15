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
      $(this).notify(resp.data.msg, resp.data.type);
    })
    .catch(function (error) {
      $(this).notify(err.msg, err.type);
    });

  });

  // -----------------------------------------------------------------------------

  $("#formDashboardAccountEmail").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(response => {
      $(this).notify(resp.data.msg, resp.data.type);
    })
    .catch(function (error) {
      $(this).notify(err.msg, err.type);
    });

  });

  // -----------------------------------------------------------------------------

  $("#formDashboardAccountPassword").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(response => {
      $(this).notify(resp.data.msg, resp.data.type);
    })
    .catch(function (error) {
      $(this).notify(err.msg, err.type);
    });

  });

  // -----------------------------------------------------------------------------

  $("#formDashboardAccountDelete").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(response => {
      $(this).notify(resp.data.msg, resp.data.type);
    })
    .catch(function (error) {
      $(this).notify(err.msg, err.type);
    });

  });

  // -----------------------------------------------------------------------------

  $("#formDashboardAccountNotification").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(response => {
      $(this).notify(resp.data.msg, resp.data.type);
    })
    .catch(function (error) {
      $(this).notify(err.msg, err.type);
    });

  });

  // -----------------------------------------------------------------------------

  $("#formDashboardNotification").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(response => {
      $(this).notify(resp.data.msg, resp.data.type);
    })
    .catch(function (error) {
      $(this).notify(err.msg, err.type);
    });

  });

  // -----------------------------------------------------------------------------

});
