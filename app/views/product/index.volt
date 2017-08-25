{% extends "templates/full.volt" %}

{% block head %}
{% endblock %}

{% block hero %}
<div id="hero">
    <div class="container container-fluid">
        <div class="row">
            <div class="col-xs-12 inner">
                <h2><i class="fa fa-play-circle"></i> Unlimited Streaming &amp Code available via Git.</h2>
            </div>
        </div>
    </div>
</div>
{% endblock %}

{% block title %}
<span class="title">Products</span>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url() }}">Home</a></li>
    <li class="active">Products</li>
</ol>

<div class="social-share">
{% include 'templates/partials/addthis.volt' %}
</div>
{% endblock %}

{% block content %}

    {% for product in products %}

    <div class="container container-fluid container-product-row {% if product.hasPurchased() %}purchased{% endif %}">
        <div class="row product-header">
            <div class="col-sm-6">
                <a href="{{ url('product/course/') }}{{ product.slug}}">{{ product.title }}</a>
            </div>
            <div class="col-sm-6">
                <span class="price">
                    {% if product.hasPurchased() %}

                    {% else %}
                        {% if product.status == constant('\Product::STATUS_DEVELOPMENT') %}
                            <sup>$</sup>{{ product.price }} &mdash; Development
                        {% elseif product.status == constant('\Product::STATUS_PLANNED') %}
                            Planned
                        {% elseif product.price != 0 and product.status != constant('\Product::STATUS_DEVELOPMENT') %}
                            <sup>$</sup>{{ product.price }}
                        {% else %}
                            Free
                        {% endif %}
                    {% endif %}
                </span>
            </div>
        </div>
        {% if product.hasPurchased() %}
            <div class="ribbon"><span>Purchased</span></div>
        {% elseif product.is_free == 1 %}
            <div class="ribbon free"><span>Free</span></div>
        {% endif %}
        <div class="row">
            <div class="col-sm-6 inner">
                {% if product.hasPurchased() %}
                <a href="{{ url('dashboard/course/index') }}/{{ product.id }}" class="relative img-thumbnail mar-right-10">
                    <img src="{{ product.img_sm }}" alt="{{ product.title }}" />
                </a>
                {% else %}
                <a href="{{ url('product/course/') }}{{ product.slug}}" class="relative img-thumbnail mar-right-10 {% if product.status == constant('\Product::STATUS_PLANNED') %}grayscale{% endif %}">
                    <img src="{{ product.img_sm }}" alt="{{ product.title }}" />
                </a>
                {% endif %}
                <br>
                <span class="label label-difficulty">Difficulty</span> {{ product.getDifficulty() }}
            </div>
            <div class="col-sm-6 relative public-product-description inner">
                <p class="expandable">
                    <?=strip_tags($product->description);?>
                </p>

                {% if product.hasPurchased() %}
                    <a class="full-product" href="{{ url('dashboard/course/index') }}/{{ product.id }}"><i class="fa fa-arrow-right"></i> Go to My Course</a>
                {% elseif product.is_free %}
                    <a class="full-product" href="{{ url('product/course/') }}{{ product.slug}}"><i class="fa fa-arrow-right"></i> View Course</a>
                {% endif %}
            </div>
        </div>
    </div>
    {% endfor %}

{% if !session.has('id') and !session.has('fb_user_id') %}
    {% include "partials/call-to-action.volt" %}
{% endif %}

<div class="spacer-80"></div>

{% endblock %}
