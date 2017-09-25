// -----------------------------------------------------------------------------
// Document Ready
// -----------------------------------------------------------------------------
(() => {

  // -----------------------------------------------------------------------------
  // Footer Newsletter Subscribe
  // -----------------------------------------------------------------------------
  $('#formFooterNewsletterSubscribe').on('submit', function(evt) {
    evt.preventDefault();

    const url = $(this).attr('action');
    const postData = $(this).attr('postData');

    axios.post(url, postData).then(resp => {
      if (resp.result == 0) {
        throw resp.data;
      }

      swal({
        title: 'Success',
        text: 'Your email has been registered, please verify you email address in your inbox!',
        type: 'success',
      });

    }).catch(err => {
      $(this).notify(err.msg, 'error');
    });

  });

});
