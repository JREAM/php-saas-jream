{% extends "templates/sidebar.volt" %}

{% block head %}
{% endblock %}

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
<ol class="breadcrumb">
    <li><a href="{{ url() }}">Home</a></li>
    <li><a href="{{ url('product') }}">Products</a></li>
    <li class="active">{{ product.title }}</li>
</ol>

<div class="social-share">
{% include 'partials/addthis.volt' %}
</div>
{% endblock %}

{% block content %}
<div class="row">
    <div class="col-md-12">
        <div class="relative">
            <img class="img-thumbnail-lg {% if product.status == constant('\Product::STATUS_PLANNED') %}grayscale{% endif %}" src="{{ product.img_lg }}" alt="{{ product.title }}" />
        </div>

        <div class="margin-10-left margin-10-top">
            <h3 class="margin-0 pull-right">
                {% if product.hasPurchased() %}
                    Purchased
                {% else %}
                    {% if product.status == constant('\Product::STATUS_DEVELOPMENT') %}
                        <sup>$</sup>{{ product.price }} &mdash; Development
                    {% elseif product.status == constant('\Product::STATUS_PLANNED') %}
                        Planned
                    {% elseif product.price != 0 and product.status != constant('\Product::STATUS_DEVELOPMENT') %}
                        {% if discount_price %}
                            <span class="old-price"><sup>$</sup>{{ product.price }}</span>
                            <span class="discount"><sup>$</sup>{{ discount_price }}</span>
                        {% else %}
                            <sup>$</sup>{{ product.price }}
                        {% endif %}
                    {% else %}
                        Free
                    {% endif %}
                {% endif %}
            </h3>
            <span class="label label-difficulty">Difficulty</span> {{ component.helper.productDifficulty(product.difficulty) }}

            <div class="clear"></div>

            {% if not product.hasPurchased() %}
            <form class="form-inline pull-right" method="get" action="{{ url('product/view') }}/{{ product.slug }}">
                <div class="form-group">
                    <input type="hidden" name="product_id" value="{{ product.id }}">
                    <input type="text" name="promotion_code" class="form-control input-sm" placeholder="Promo Code" {% if promotion_code %}value="{{ promotion_code }}"{% endif %}>
                    <input type="submit" class="btn btn-default btn-sm" value="Apply">
                </div>
            </form>
            {% endif %}

        </div>

        <div class="clear spacer-40"></div>

        <a id="jump-to-checkout" href="#checkout-area"><i class="fa fa-arrow-circle-down"></i> Jump to Checkout Area</a>

        <h2>{{ product.title }} Overview</h2>
        {{ product.description }}

        {% if product.price != 0 %}
        <div class="spacer-40"></div>
        {% endif %}

    </div>

    {% if !product.hasPurchased() %}
        {% if product.price != 0 %}
        <div class="col-md-12">
            <ul class="list-group">
                <li class="list-group-item active"><h4>Included in Every Course</h4></li>
                <li class="list-group-item"><span class="glyphicon glyphicon-th-large"></span> Full Series</li>
                <li class="list-group-item"><span class="glyphicon glyphicon-film"></span> Unlimited Streaming</li>
                <li class="list-group-item"><span class="glyphicon glyphicon-star"></span> Track your Progress</li>
                <li class="list-group-item"><span class="glyphicon glyphicon-user"></span> Questions Area</li>
                <li class="list-group-item"><span class="glyphicon glyphicon-cloud-download"></span> Source Code via GIT</li>
                <li class="list-group-item"><span class="glyphicon glyphicon-record"></span> Hardened Account Security</li>
                <li class="list-group-item"><span class="glyphicon glyphicon glyphicon-ok-sign"></span> One Time Price <sup>$</sup><strong>{{ product.price }}</strong></li>
            </ul>
        </div>
        {% endif %}
    {% endif %}
</div>


{% if !product.hasPurchased() %}

    <h2 id="course-content">Course Content</h2>
    {% include "partials/section/course-list.volt" %}

    {% if !session.has('id') %}
    <hr />
        <a class="btn btn-block btn-lg btn-primary" href="{{ url('user/register') }}"><strong>Ready to Signup?</strong></a>
    <hr />
    {% endif %}

    {% if product.price != 0 %}
        {% include "partials/section/payment/requirements.volt" %}
    {% endif %}
{% endif %}

<div class="spacer-80"></div>

{% endblock %}

{% block sidebar %}

<div class="col-md-12">
    {% if !user %}
    <div class="panel panel-default panel-primary">
        <div class="panel-heading">
            <strong>Create an Account</strong>
        </div>
        <div class="panel-body text-center">
            <p class="text-center">
                <a href="{{ url('user/register') }}" class="btn btn-block btn-lg btn-social btn-jream">
                    <img src="{{ config.url_static }}img/logo/icon-sm.svg" alt="JREAM"> Sign in with JREAM
                </a>
            </p>
            <p class="text-center">
                <a href="{{ fbLoginUrl }}" class="btn btn-block btn-lg btn-social btn-facebook">
                    <i class="fa fa-facebook"></i> Sign in with Facebook
                </a>
            </p>
        </div>
    </div>
    {% endif %}
    {% if hasPurchased %}
        <div>
            <a class="btn btn-xl btn-primary" href="{{ url('dashboard/course/index') }}/{{ product.id }}"><i class="fa fa-arrow-right"></i> Go to Course</a>
        </div>
    {% else %}
        {% if product.price != 0 %}
            {% include "partials/section/payment/checkout.volt" %}
        {% elseif user %}
            <h2>Free Course</h2>
            <a class="btn btn-primary btn-lg" href="{{ url('product/dofreecourse') }}/{{ product.id }}">Add Free Course</a>
        {% endif %}
    {% endif %}
</div>

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
