{% extends "templates/sidebar.volt" %}

{% block title %}
<span class="title">{{ product.title }}</span>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url() }}">Home</a></li>
    <li><a href="{{ url('product') }}">Products</a></li>
    <li class="active">{{ product.title }}</li>
</ol>
{% endblock %}

{% block content %}
<div class="row">
    <div class="col-md-12">
        <div class="relative">
            <img class="img-thumbnail-lg {% if product.status == constant('\Product::STATUS_PLANNED') %}grayscale{% endif %}" src="{{ product.img_lg }}" alt="{{ product.title }}" />
        </div>

        <div class="price-and-status row">
            <div class="col-xs-4">
                <span class="label label-lg label-difficulty">Difficulty</span> {{ product.getDifficulty() }}
            </div>
            <div class="col-xs-4">
                <span class="label label-lg label-duration">{{ product.getDuration() }}</span>
            </div>
            <div class="col-xs-4">
                <h3 class="margin-0 txt-right">
                    {% if product.hasPurchased() %}
                        <span class="purchased">Purchased</span>
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
                                <span class="price"><sup>$</sup>{{ product.price }}</span>
                            {% endif %}
                        {% else %}
                            <span class="free">Free</span>
                        {% endif %}
                    {% endif %}
                </h3>
            </div>

            <div class="clear"></div>


        </div>
            {% if not product.hasPurchased() %}
            <div class="promotion-container">
                <form id="formProductPromotionCode" class="form-inline pull-right" method="post" action="{{ url('auth/purchase/applyPromotion') }}/{{ product.slug }}">
                    <div class="form-group">
                        <input type="hidden" name="product_id" value="{{ product.id }}">
                        <input type="text" name="promotion_code" class="form-control input-sm" placeholder="Promo Code" {% if promotion_code %}value="{{ promotion_code }}"{% endif %}>
                        <input type="submit" class="btn btn-default btn-sm" value="Apply">
                    </div>
                </form>
            </div>
            {% endif %}

        <div class="clear spacer-40"></div>

        <br>

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


{% if not product.hasPurchased() %}

    <h2 id="course-content">Course Content</h2>
    {% include "partials/course-list.volt" %}

    {% if not session.has('is_logged_in') %}
    <hr />
        <a class="btn btn-block btn-lg btn-primary" href="{{ url('user/register') }}"><strong>Ready to Signup?</strong></a>
    <hr />
    {% endif %}

    {% if product.price != 0 %}
        {% include "partials/payment-requirements.volt" %}
    {% endif %}
{% endif %}

<div class="spacer-80"></div>

{% endblock %}

{% block sidebar %}

<div class="col-md-12">
    {% if not session.has('is_logged_in') %}
    <div class="panel panel-default panel-primary">
        <div class="panel-heading">
            <strong>Create an Account</strong>
        </div>
        <div class="panel-body text-center">
            <p class="text-center">
                <a href="{{ url('user/login') }}" class="btn btn-block btn-lg btn-social btn-jream">
                    <img src="{{ config.url_static }}img/logo/icon-sm.svg" alt="JREAM"> Sign in with JREAM
                </a>
            </p>
            {% include 'partials/social-login.volt' %}
            <hr>
        <p class="muted">
            <a href="{{ url('user/login') }}">Login</a> or <a href="{{ url('user/register') }}">Create an Account</a> to Purchase.
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
            {% include "partials/payment-checkout.volt" %}
        {% elseif session.has('is_logged_in') %}
            <h2>Free Course</h2>
            <a class="btn btn-primary btn-lg" href="{{ url('product/dofreecourse') }}/{{ product.id }}">Add Free Course</a>
        {% endif %}
    {% endif %}
</div>

{% endblock %}

