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
      if (resp.result == 0) {
        throw resp.data;
      }
      swal({
        title: 'Success',
        text: 'Your Question was Posted',
        type: 'success',
        timer: 3000
      })
    })
    .catch(err => {
      popError(err.msg);
    });
  });

  // -----------------------------------------------------------------------------

  $("#formQuestionReply").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(resp => {
      if (resp.result == 0) {
        throw resp.data;
      }
      swal({
        title: 'Success',
        text: 'Your reply was Posted.',
        type: 'success',
        timer: 3000
      })
    })
    .catch(err => {
      popError(err.msg);
    });
  });

  // -----------------------------------------------------------------------------

  $("#formQuestionDelete").submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr("action");
    const postData = $(this).serialize();

    axios.post(url, postData).then(resp => {
      if (resp.result == 0) {
        throw resp.data;
      }

      swal({
        title: 'Success',
        text: 'Your question was removed.',
        type: 'success',
        timer: 3000
      })
    })
    .catch(err => {
      popError(err.msg);
    });
  });

  // -----------------------------------------------------------------------------

});
