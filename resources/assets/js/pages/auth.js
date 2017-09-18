// -----------------------------------------------------------------------------
// Document Ready
// -----------------------------------------------------------------------------
$(() => {
  // -----------------------------------------------------------------------------
  $("#formUserLogin").submit((evt) => {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(resp => {
      window.location = resp.data.data.redirect;
    })
      .catch(err => {
        $(this).notify(err.msg, err.type);
      });

  });

  // -----------------------------------------------------------------------------

  $("#formUserRegister").submit(function (evt) {
    evt.preventDefault();

    const postData = $(this).serialize();
    const url = $(this).attr("action");

    axios.post(url, postData).then(resp => {

      $(this).notify(resp.data.msg, resp.data.type).then(dismiss => {
        window.location = "/dashboard";
      });
    })
      .catch(err => {
        $(this).notify(err.msg, err.type);
      });

  });

  // -----------------------------------------------------------------------------


  $("#formUserPasswordResetConfirm").submit(function (evt) {
    evt.preventDefault();

    const postData = $(this).serialize();
    const url = $(this).attr("action");

    axios.post(url, postData).then(resp => {
      $(this).notify(resp.data.msg, resp.data.type);
    })
      .catch(err => {
        $(this).notify(err.msg, err.type);
      });

  });

  // -----------------------------------------------------------------------------

});
