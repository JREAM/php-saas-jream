{% extends "templates/sidebar.volt" %}

{% block head %}
{% endblock %}

{% block intro %}
<div id="product-intro">
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
    <h1>{{ product.title }}</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url() }}">Home</a></li>
    <li><a href="{{ url('product') }}">Products</a></li>
    <li class="active">{{ product.title }}</li>
</ol>

<div class="social-share">
{% include 'inc/addthis.volt' %}
</div>
{% endblock %}

{% block content %}
<div class="row">
    <div class="col-md-12">
        <div class="relative">
            <img class="img-thumbnail {% if product.status == constant('\Product::STATUS_PLANNED') %}grayscale{% endif %}" src="{{ product.img_lg }}" alt="{{ product.title }}" />
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
                    <input type="text" name="promo" class="form-control input-sm" placeholder="Promo Code" {% if promo_code %}value="{{ promo_code }}"{% endif %}>
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

<div class="spacer-20"></div>

{% if !product.hasPurchased() %}
    <h2 id="course-content">Course Content</h2>
    {% include "inc/section/course-list.volt" %}

    {% if !session.has('id') %}
    <hr />
        <a class="btn btn-block btn-lg btn-primary" href="{{ url('user/register') }}"><strong>Ready to Signup?</strong></a>
    <hr />
    {% endif %}

        {% if product.price != 0 %}
        <div class="row">
            <div class="col-md-12">
            <h2 id="purchase-security">Purchase Security</h2>
                {% include "inc/section/purchase-security.volt" %}
                <p>
                For more details on security used click <a href="{{ url('terms#misc-security') }}">here</a>.
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
            <h2 id="system-requirements">System Requirements</h2>
                <ul>
                    <li><strong>A Modern Web Browser</strong>
                        <ul>
                            <li><a href="http://google.com/chrome" target="_blank"><strong>Google Chrome</strong></a> (Recommended)</li>
                            <li><a href="http://getfirefox.net" target="_blank"><strong>Mozilla Firefox</strong></a></li>
                            <li>Internet Explorer 9 and Above (Not Recommended)</li>
                        </ul>
                    <li><a href="http://get.adobe.com/flashplayer/" target="_blank"><strong>Adobe Flash Player</strong></a> 9.0 or above.
                        <ul>
                            <li>Used for RTMP Service for Streaming Media</li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
            <h2 id="discrepencies">Discrepencies</h2>
            <p>
            Please be aware of the following before you decide to purchase:
            </p>
                <ul>
                    <li>
                        The streaming service is <strong>NOT</strong> fully mobile compatible due to the Flash Player requirement.
                    </li>
                    <li>
                        Streaming Media is <strong>NOT</strong> downloadable. However, you can stream as often as you like.
                    </li>
                </ul>
            </div>
        </div>
        {% endif %}
{% endif %}

{% endblock %}

{% block sidebar %}

<div class="col-md-12">
    {% if !user %}
    <div class="panel panel-default panel-primary">
        <div class="panel-heading">
            <strong>Create an Account</strong>
        </div>
        <div class="panel-body text-center">
            <a href="{{ url('user/register') }}"><img src="{{ url('img/jream-login.png') }}" class="fadeover" alt="JREAM Login"></a>
            <a href="{{ fbLoginUrl }}"><img src="{{ url('img/facebook-login.png') }}" class="fadeover" alt="Facebook Login" /></a>
        </div>
    </div>
    {% endif %}
    {% if hasPurchased %}
        <div>
            <a class="btn btn-xl btn-primary" href="{{ url('dashboard/course/index') }}/{{ product.id }}"><i class="fa fa-arrow-right"></i> Go to Course</a>
        </div>
    {% else %}
        {% if product.status == constant('\Product::STATUS_PLANNED') %}
            <p>
            This course cannot yet be purchased. Curriculum is still being planned.
            </p>
        {% elseif product.price != 0 %}
            {% include "inc/section/checkout.volt" %}
        {% elseif user %}
            <h2>Free Course</h2>
            <a class="btn btn-primary btn-lg" href="{{ url('product/dofreecourse') }}/{{ product.id }}">Add Free Course</a>
        {% endif %}
    {% endif %}
</div>


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
