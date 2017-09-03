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
      $(this).notify('Your question was posted', "success");
      // Clear textarea
      $('textarea', this).val('');
    })
      .catch(err => {
        $(this).notify(err.msg, "error");
      });
  });

  // -----------------------------------------------------------------------------

  $("#formQuestionReply").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(resp => {
      $(this).notify('Your reply was posted.', "success");
      // Clear textarea
      $('textarea', this).val('');
    })
      .catch(err => {
        $(this).notify(err.msg, "error");
      });
  });

  // -----------------------------------------------------------------------------

  $("#formQuestionDelete").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(resp => {
      $(this).notify('Your question was removed', "success");
    })
      .catch(err => {
        $(this).notify(err.msg, "error");
      });
  });

  // -----------------------------------------------------------------------------

});
