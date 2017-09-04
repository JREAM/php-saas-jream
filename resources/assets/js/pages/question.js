// -----------------------------------------------------------------------------
// Document Ready
// -----------------------------------------------------------------------------
$(() => {

  // -----------------------------------------------------------------------------

  $("#formQuestionCreate").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(resp => {
      $(this).notify(resp.data.msg, resp.data.type);
      // Clear textarea
      $('textarea', this).val('');
    })
      .catch(err => {
        $(this).notify(err.msg, err.type);
      });
  });

  // -----------------------------------------------------------------------------

  $("#formQuestionReply").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(resp => {
      $(this).notify(resp.data.msg, resp.data.type);
      // Clear textarea
      $('textarea', this).val('');
    })
      .catch(err => {
        $(this).notify(err.msg, err.type);
      });
  });

  // -----------------------------------------------------------------------------

  $("#formQuestionDelete").submit(function (evt) {
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
