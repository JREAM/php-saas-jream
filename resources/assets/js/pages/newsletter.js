import axios from "../components/interceptors";

$(() => {

  $("#formNewsletterSubscribe").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(function (response) {
      swal({
        title: 'Success',
        text: 'Your email has been registered, please verify you email address in your inbox!',
        type: 'success',
        timer: 3000
      })
    })
    .catch(function (error) {
      popError(error.msg);
    });

  });

  $("#formNewsletterVerify").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(function (response) {
      swal({
        title: 'Success',
        text: 'Your email has been validated',
        type: 'success',
        timer: 3000
      })
    })
    .catch(function (error) {
      popError(error.msg);
    });
  });

  $("#formNewsletterUnSubscribe").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(function (response) {
      swal({
        title: 'Success',
        text: 'Your email has been unsubscribed from future newsletters!',
        type: 'success',
        timer: 3000
      })
    })
    .catch(function (error) {
      popError(error.msg);
    });

  });

});
