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
      console.log(response);
        swal({
          title: 'Success',
          text: 'Logging In..',
          type: 'success',
          timer: 2000
        })
        .then(function () {},
          // handling the promise rejection
          function (dismiss) {
            if (dismiss === 'timer') {
              window.location = '/dashboard';
            }
            window.location = '/dashboard';
        });
    }).catch(function (error) {
      console.log(error);
        swal({
          title: 'Error',
          text: error.msg,
          type: 'error',
          showCancelButton: true,
          cancelButtonText: 'Close'
        });
    });

  });

  $("#formRegister").submit(function (evt) {
    evt.preventDefault();

    const postData = $(this).serialize();
    const url = $(this).attr("action");

    axios.post(url, postData).then(function (response) {

    });

  });


  $("#formPasswordResetConfirm").submit(function(evt) {
    evt.preventDefault();

    const postData = $(this).serialize();
    const url = $(this).attr("action");

    axios.post(url, postData).then(function (response) {

    });

  });


  $("#logout").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");

    axios.get(url, postData).then(function (response) {

    });

  });

});
