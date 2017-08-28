function validateRecaptcha() {
  let recaptcha = $("#recaptcha").attr('data-site-key')
  let url = '/api/contact'
  axios.post(url, postData).then(function (response) {
    // DEBUG
    console.log(response);

    if (response.result == 1) {
      // Another AJAX call to Contact Send?
      return true;
    }

    // Error
    throw response.msg;

  }).catch(function (error) {
      console.log(error);
      if (!!error.data) {

      }
  });
}
$(() => {

  $("#formContact").on('submit', function (evt) {
    evt.preventDefault();

    let url = $(this).attr('action');
    let postData = $(this).serialize();
    // let recaptcha = $(this).
    axios.post(url, postData).then(function (response) {
      if (reponse.result == 1) {
        alert('make a message that its been sent, redirect?');
        return true;
      }

      throw Exception(response);
      // g-recaptcha-response
    }).catch(function (error) {
      alert('must show the error');
      alert(error);
      console.log(error);
    })

  });

});
