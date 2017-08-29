import axios from "../components/interceptors";
import formUtil from "./../components/forms";
import swal from "sweetalert2";

$(() => {

  $("#formContact").on('submit', function (evt) {
    evt.preventDefault();

    formUtil.disable(this.id);
    let url = $(this).attr('action');
    let postData = $(this).serialize();

    window.axios.post(url, postData).then(function (response) {


      if (reponse.result == 0) {
        throw Exception(response);
      }


      swal({
        title: 'Email Dispatched',
        text: response.msg,
        type: 'success',
        cancelButtonText: 'Close',
        timer: 2000
      }).then(function () {},
        // Promise Rejection
        function (dismiss) {
          if (dismiss === 'timer') {
            window.location = '/contact/thanks';
          }
          window.location = '/contact/thanks';
      });

    }).catch(function (error) {

      swal({
        title: 'Error',
        text: error.msg,
        type: 'error',
        showCancelButton: true,
        cancelButtonText: 'Close'
      });

      // Reset Recaptcha
      grecaptcha.reset();
      formUtil.enable(this.id);
    })

  });

});
