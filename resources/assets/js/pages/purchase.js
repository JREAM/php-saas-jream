// -----------------------------------------------------------------------------
// Document Ready
// -----------------------------------------------------------------------------
$(() => {
  // -----------------------------------------------------------------------------
  // Only Apply to proper Page
  // -----------------------------------------------------------------------------
  if (_.indexOf(['product', 'course'], routes.current.controller == -1)) {
    return false;
  }

  $('#form-purchase-stripe').submit(function (evt) {
    evt.preventDefault();

    const url = $(this).attr('action');

    axios.get(url).then((resp) => {

    })
      .catch((err) => {
        alert(err.msg);
        // $(this).notify(err.msg, err.type);
      });
  });

  // -----------------------------------------------------------------------------
});
