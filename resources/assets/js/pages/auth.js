import swal from "sweetalert2";

let sections = [
  '#page-account',
  '#page-forgotpassword',
  '#page-forgotpassword',
  '#page-createpassword?',
];


$(() => {

  $("#formLogin").on('submit', function (evt) {
    evt.preventDefault();

    let url = $(this).attr('action');
    let postData = $(this).serialize();

    axios.post(url, postData).then(function (response) {
      swal({
        title: 'Success',
        text: 'Logging In..',
        type: 'success',
        timer: 2000
      })
      .then(function () {},
        // When Timer is Complete, or item Closed
        function (dismiss) {
          if (dismiss === 'timer') {
            window.location = '/dashboard';
          }
        window.location = '/dashboard';
      });
    })
    .catch(function (error) {
        popError(error.msg);
    });

  });

  $("#formRegister").submit(function (evt) {
    evt.preventDefault();

    const postData = $(this).serialize();
    const url = $(this).attr("action");

    axios.post(url, postData).then(function (response) {
      swal({
        title: 'Success',
        text: 'Logging In..',
        type: 'success',
        timer: 2000
      })
      .then(function () {},
        // When Timer is Complete, or item Closed
        function (dismiss) {
          if (dismiss === 'timer') {
            window.location = '/dashboard';
          }
          window.location = '/dashboard';
        });
      })
      .catch(function (error) {
        popError(error.msg);
      });

  });


  $("#formPasswordResetConfirm").submit(function(evt) {
    evt.preventDefault();

    const postData = $(this).serialize();
    const url = $(this).attr("action");

    axios.post(url, postData).then(function (response) {
      swal({
        title: 'Success',
        text: 'Please Check your Email',
        type: 'success',
      })
    })
    .catch(function (error) {
      popError(error.msg);
    });

  });


  $("#logout").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");

    axios.get(url, postData).then(function (response) {
      window.location = '/';
    });

  });

});
