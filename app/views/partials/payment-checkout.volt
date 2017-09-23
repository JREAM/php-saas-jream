<script src="https://js.stripe.com/v2/" type="text/javascript"></script>
<script>
// @TODO Not sure why this wont load elsewhere
Stripe.setPublishableKey('{{ api.stripe.publishableKey }}');

$(function() {
    $("#formPurchase").submit(function(evt) {
        evt.preventDefault();

        var self = $(this);
        self.find('input[type=submit]').prop('disabled', true);

        Stripe.card.createToken($(this), function(status, response) {
            if (response.error) {
                self.find('.payment-errors').html('<div class="alert alert-danger">' + response.error.message + '</div>');
                self.find('input[type=submit]').prop('disabled', false);
            } else {
                var token = response.id;
                // Insert the token into the form so it gets submitted to the server
                self.append($('<input type="hidden" name="stripeToken" />').val(token));
                self.get(0).submit();
            }
        });
    });
});
</script>

<!-- Used to reference smaller screens href# -->
<div id="checkout-area"></div>

{% if user %}
<div class="panel panel-default panel-primary checkout-purchase-paypal">
    <div class="panel-heading">
        <strong>PayPal</strong>
    </div>
    <div class="panel-body text-center">
        <a href="{{ url('product/dopaypal') }}/{{ product.id }}">
            <img src="{{ url('images/payments/checkout-with-paypal.jpg') }}" alt="PayPal Checkout" />
        </a>
    </div>
</div>

<div class="panel panel-default panel-primary checkout-purchase-card">
    <div class="panel-heading">
        <strong>Credit Card</strong>
    </div>
    <div class="panel-body">
        <i class="fa fa-lock"></i> Security
        <form id="formPurchaseStripe" action="{{ url('product/doStripe') }}/{{ product.id }}" method="post">
        <div class="payment-errors"></div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="cc-name">Name on Card</label>
                        <input type="text" name="name" class="form-control" placeholder="Name on Card" value="<?=formData('name')?>" id="cc-name">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label for="cc-number">Card Number</label>
                        <input data-stripe="number" class="form-control" placeholder="Card Number" value="" id="cc-number">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="cc-exp-month">Exp. Month</label>
                        <select data-stripe="exp-month" class="form-control" id="cc-exp-month">
                            {% for number, name in months %}
                                <option {% if date('m') == number %}selected="selected"{% endif %} value="{{ number }}">{{ name }} - {{ number }}</option>
                            {% endfor %}
                        </select>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="cc-exp-year">Exp. Year</label>
                        <select data-stripe="exp-year" class="form-control" id="cc-exp-year">
                            {% for year in years %}
                            <option>{{ year }}</option>
                            {% endfor %}
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label>Zip <i class="fa fa-question-circle-o" data-toggle="tooltip" title="Required for security verification."></i></label>
                        <input data-stripe="address_zip" class="form-control" value="<?=formData('zip')?>">
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label>CVC <i class="fa fa-question-circle-o" data-toggle="tooltip" title="3 Digits on the back of your card. (Also known as CVV, CID, or CSC)."></i></label>
                        <input data-stripe="cvc" class="form-control" value="<?=formData('cvc')?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
F                        <input class="btn btn-lg btn-primary btn-block popover-sm" type="submit" value="Purchase" data-toggle="popover" data-placement="top" data-original-title="Purchase for ${{ product.price }} USD" data-content="Please double check your information. If you enter incorrect information you will have to re-enter it. For security, no Credit Card data ever touches our servers.">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 text-center">
                {% include "partials/payment-icons.volt" %}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 text-center">
                    <img src="{{ url('images/payments/stripe.png') }}" alt="Powered by Stripe" />
                </div>
            </div>

            <input class="csrf-field" type="hidden" name="{{ tokenKey }}" value="{{ token }}" />

        </form>

    </div>
</div>
{% endif %}

<ul class="text-right" style="list-style-type: none;">
    <li><a href="#course-content">Course Content</a></li>
    <li><a href="#purchase-security">Purchase Security</a></li>
    <li><a href="#system-requirements">System Requirements</a></li>
    <li><a href="#discrepancies">Discrepancies</a></li>
</ul>
