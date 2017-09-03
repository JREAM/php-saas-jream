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
      window.location = '/dashboard';
    })
      .catch(err => {
        $(this).notify(err.msg, "error");
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
      });

  });

  // -----------------------------------------------------------------------------


  $("#formUserPasswordResetConfirm").submit(function (evt) {
    evt.preventDefault();

    const postData = $(this).serialize();
    const url = $(this).attr("action");

    axios.post(url, postData).then(resp => {
      $(this).notify('Great! Next, please confirm your email.', "warning");
    })
      .catch(err => {
        $(this).notify(err.msg, "error");
      });

  });

  // -----------------------------------------------------------------------------

});
