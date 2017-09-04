// -----------------------------------------------------------------------------
// Document Ready
// -----------------------------------------------------------------------------
$(() => {

  // -----------------------------------------------------------------------------

  $("#formNewsletterSubscribe").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(resp => {
      $(this).notify(resp.data.msg, resp.data.type);
    })
    .catch(err => {
      $(this).notify(err.msg, err.type);
    });

  });

  // -----------------------------------------------------------------------------

  $("#formNewsletterVerify").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(resp => {
      $(this).notify(resp.data.msg, resp.data.type);
    })
    .catch(err => {
      $(this).notify(err.msg, err.type);
    });

  });

  // -----------------------------------------------------------------------------

  $("#formNewsletterUnsubscribe").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(resp => {
      $(this).notify(resp.data.msg, resp.data.type);
    })
    .catch(err => {
      $(this).notify(err.msg, err.type);
    });

  });

  // -----------------------------------------------------------------------------

});
