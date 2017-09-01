// -----------------------------------------------------------------------------
// Document Ready
// -----------------------------------------------------------------------------
$(() => {

  // -----------------------------------------------------------------------------

  $("#formUserLogin").on('submit', function (evt) {
    evt.preventDefault();

    let url = $(this).attr('action');
    let postData = $(this).serialize();

    axios.post(url, postData).then(resp => {
      if (resp.data.result == 0) {
        throw resp.data;
      }
      window.location = '/dashboard';
    })
      .catch(err => {
        popError(err.msg)
      });

  });

  // -----------------------------------------------------------------------------

  $("#formUserRegister").submit(function (evt) {
    evt.preventDefault();

    const postData = $(this).serialize();
    const url = $(this).attr("action");

    axios.post(url, postData).then(resp => {

    $(this).notify("Logging In", "success").then(dismiss => {
      window.location = '/dashboard';
    })

      // swal({
      //   title: 'Success',
      //   text: 'Logging In..',
      //   type: 'success',
      //   timer: 2000
      // }).then(function () {
      //   },
      //   // When Timer is Complete, or item Closed
      //   function (dismiss) {
      //     if (dismiss === 'timer') {
      //       window.location = '/dashboard';
      //     }
      //     window.location = '/dashboard';
      //   });
    })
      .catch(err => {
        $(this).notify(err.msg, "error");
        // popError(err.msg)
      });

  });

  // -----------------------------------------------------------------------------


  $("#formUserPasswordResetConfirm").submit(function (evt) {
    evt.preventDefault();

    const postData = $(this).serialize();
    const url = $(this).attr("action");

    axios.post(url, postData).then(resp => {
      swal({
        title: 'Success',
        text: 'Please Check your Email',
        type: 'success',
      })
    })
      .catch(err => {
        popError(err.msg)
      });

  });

  // -----------------------------------------------------------------------------

});
