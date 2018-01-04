// ─────────────────────────────────────────────────────────────────────────────
// Document Ready
// ─────────────────────────────────────────────────────────────────────────────

const stripe = Stripe(window.api.stripe);
const elements = stripe.elements();

$(() => {
  // ─────────────────────────────────────────────────────────────────────────────
  // Only Apply to proper Page
  // ─────────────────────────────────────────────────────────────────────────────
  if (_.indexOf([
    'product', 'course'
  ], routes.current.controller == -1)) {
    return false;
  }

  // Stripe Styles
  const style = {
    base: {
      fontSize: '16px',
      color: '#32325d'
    }
  };
  const card = elements.create('card', { style });
  card.mount('#stripe-card-element');

  card.addEventListener('change', ({ error }) => {
    const displayError = document.getElementById('card-errors');
    if (error) {
      displayError.textContent = error.message;
    } else {
      displayError.textContent = '';
    }
  });

  const form = document.getElementById('form-purchase-stripe');
  form.addEventListener('submit', async (event) => {
    event.preventDefault();

    const { token, error } = await stripe.createToken(card);

    if (error) {
      // Inform the customer that there was an error
      const errorElement = document.getElementById('stripe-card-errors');
      errorElement.textContent = error.message;
    } else {
      // Send the token to your server
      stripeTokenHandler(token);
    }
  });

  const stripeTokenHandler = (token) => {
    // Insert the token ID into the form so it gets submitted to the server
    const form = document.getElementById('stripe-payment-form');
    const hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    form.appendChild(hiddenInput);

    // Submit the form
    form.submit();
  };

  $('#form-purchase-stripe').submit((evt) => {
    evt.preventDefault();
    $(this)
      .find('input[type=submit]')
      .prop('disabled', true);

    stripe.createToken($(this), (status, response) => {
      if (response.error) {
        $(this)
          .find('.payment-errors')
          .html(`<div class="alert alert-danger">${response.error.message}</div>`);
        $(this)
          .find('input[type=submit]')
          .prop('disabled', false);
      } else {
        const token = response.id;
        // Insert the token into the form so it gets submitted to the server
        $(this).append($('<input type="hidden" name="stripeToken" />').val(token));
        $(this)
          .get(0)
          .submit();
      }
    });
  });

  // ─────────────────────────────────────────────────────────────────────────────
});
