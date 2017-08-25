{% extends "templates/sidebar.volt" %}

{% block hero %}
<div id="hero">
    <div class="container container-fluid">
        <div class="row">
            <div class="col-md-12 inner">
                <h2><i class="fa fa-play-circle"></i> Unlimited Streaming &amp; Code available via Git.</h2>
            </div>
        </div>
    </div>
</div>
{% endblock %}

{% block title %}
<span class="title">{{ product.title }}</span>
{% endblock %}

{% block breadcrumb %}


<a href="https://get.adobe.com/flashplayer/"><img src="{{ url('icons/get-flash.png') }}" alt="Get Adobe Flash Player"></a>

<ol class="breadcrumb">
    <li><a href="{{ url() }}">Home</a></li>
    <li><a href="{{ url('product') }}">Products</a></li>
    <li class="active">{{ product.title }}</li>
</ol>

<div class="social-share">
{% include 'templates/partials/addthis.volt' %}
</div>
{% endblock %}

{% block content %}
<div class="row">
    <div class="col-md-12">

    {% if !user %}
    <div class="panel panel-default panel-primary">
        <div class="panel-heading">
            <strong>Create an Account</strong> or <strong>Login</strong>
        </div>
        <div class="panel-body text-center">
            <a href="{{ url('user/register') }}"><img src="{{ url('images/buttons/jream-login.png') }}" alt="JREAM Login"></a>
            <a href="{{ fbLoginUrl }}"><img src="{{ url('images/buttons/facebook-login.png') }}" alt="Facebook Login" /></a>
        </div>
    </div>
    {% endif %}

    <div class="panel panel-default panel-primary">
        <div class="panel-heading">
            <strong>Selected Item(s)</strong>
        </div>
        <div class="panel-body text-center">
            <ul>
                <li>Product 1</li>
                <li>Product 2</li>
                <li>Product 3</li>
                <li>Product 4</li>
            </ul>
        </div>
    </div>

    <div class="panel panel-default panel-primary">
        <div class="panel-heading">
            <strong>Checkout Summary</strong>
        </div>
        <div class="panel-body text-center">
            <ul>
                <li>Item 1</li>
                <li>Item 2</li>
                <li>Item 3</li>
                <li>Discount</li>
                <li>Total: 111</li>
            </ul>
        </div>
    </div>

    <div class="panel panel-default panel-primary">
        <div class="panel-heading">
            <strong>Checkout Summary</strong>
        </div>
        <div class="panel-body text-center">
          Stripe / Paypal
        </div>
    </div>

</div>


<div class="spacer-80"></div>

{% endblock %}

{% block script %}
<script>
$(function() {

    {% if user %}

    {% else %}
    $(".checkout-purchase-card").hide();
    $(".checkout-purchase-paypal").hide();

    // $(".checkout-purchase-card, .checkout-purchase-paypal").css('opacity', 0.5);
        $(".checkout-purchase-card").find('input, select').prop('disabled', true);
        $(".checkout-purchase-paypal").find('a').css('cursor', 'default');
        $(".checkout-purchase-paypal").find('a').click(function(evt) {
            evt.preventDefault();
        });
    {% endif %}
})
</script>
{% endblock %}
