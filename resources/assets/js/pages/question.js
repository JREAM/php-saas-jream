// -----------------------------------------------------------------------------
// Document Ready
// -----------------------------------------------------------------------------
$(() => {
  // -----------------------------------------------------------------------------
  // Only Apply to proper Page
  // -----------------------------------------------------------------------------
  if (routes.current.controller != 'question') {
    return false;
  }

  // -----------------------------------------------------------------------------

  xhr.stdForm('#form-question-create', {
    success (resp, evt) {
      $('#form-question-create textarea').val('');
    }
  });

  // Must be a class for now in order to submit below any of the comments,
  // @TODO Eventually remove the DOM and re-create
  xhr.stdForm('#form-question-create', function (resp, evt) {
    $('textarea', this).val('');
  });

  // -----------------------------------------------------------------------------


  // -----------------------------------------------------------------------------

  $('#form-question-delete').submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr('action');
    const postData = $(this).serialize();

    axios.post(url, postData).then((resp) => {
      alert(resp.data.msg);
      // $(this).notify(resp.data.msg, resp.data.type);
    })
      .catch((err) => {
        alert(err.msg);
        // $(this).notify(err.msg, err.type);
      });
  });

  // -----------------------------------------------------------------------------
});
