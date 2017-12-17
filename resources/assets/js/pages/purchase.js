// ─────────────────────────────────────────────────────────────────────────────
// Document Ready
// ─────────────────────────────────────────────────────────────────────────────
$(() => {
  // ─────────────────────────────────────────────────────────────────────────────
  // Only Apply to proper Page
  // ─────────────────────────────────────────────────────────────────────────────
  if (_.indexOf(['product', 'course'], routes.current.controller == -1)) {
    return false;
  }

  $('#form-purchase-stripe').submit((evt) => {
    evt.preventDefault();
    $(this).find('input[type=submit]').prop('disabled', true);

    Stripe.card.createToken($(this), (status, response) => {
      if (response.error) {
        $(this).find('.payment-errors')
          .html(`<div class="alert alert-danger">${response.error.message}</div>`);
        $(this).find('input[type=submit]').prop('disabled', false);
      } else {
        const token = response.id;
        // Insert the token into the form so it gets submitted to the server
        $(this).append($('<input type="hidden" name="stripeToken" />').val(token));
        $(this).get(0).submit();
      }
    });
  });

  // ─────────────────────────────────────────────────────────────────────────────
});
