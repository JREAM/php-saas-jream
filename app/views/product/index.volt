{% extends "templates/full.volt" %}

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
    <div class="container container-fluid product-row {% if product.hasPurchased() %}purchased{% endif %}">

        {% if product.hasPurchased() %}
            <div class="ribbon"><span>Purchased</span></div>
        {% elseif product.is_free == 1 %}
            <div class="ribbon free"><span>Free</span></div>
        {% endif %}

        <div class="row product-header">
            <div class="col-sm-offset-4 col-sm-8">
                <div class="inline-block">
                    <a href="{{ url('product/course/') }}{{ product.slug}}">{{ product.title }}</a>
                </div>
                <div class="inline-block flright">
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
        </div>
        <div class="row product-content">
            <div class="col-sm-4 inner">
                {% if product.hasPurchased() %}
                <a href="{{ url('dashboard/course/index') }}/{{ product.id }}" class="relative img-thumbnail mar-right-10">
                    <img src="{{ product.img_sm }}" alt="{{ product.title }}" />
                </a>
                {% else %}
                <a href="{{ url('product/course/') }}{{ product.slug}}" class="relative img-thumbnail mar-right-10 {% if product.status == constant('\Product::STATUS_PLANNED') %}grayscale{% endif %}">
                    <img src="{{ product.img_sm }}" alt="{{ product.title }}" />
                </a>
                {% endif %}
                <div class="difficulty">
                    <span class="label label-lg label-difficulty">Difficulty</span> {{ product.getDifficulty() }}
                </div>
                <div>
                    <span class="label label-lg label-duration">{{ product.getDuration() }}</span>
                </div>
            </div>
            <div class="col-sm-8 relative public-product-description inner">
                <p class="expandable">
                    <?=strip_tags($product->description);?>
                </p>

                {% if product.hasPurchased() %}
                    <a class="btn btn-success" href="{{ url('dashboard/course/index') }}/{{ product.id }}"><i class="fa fa-arrow-right"></i> Go to My Course</a>
                {% elseif product.is_free %}
                    <a class="btn btn-info" href="{{ url('product/course/') }}{{ product.slug}}"><i class="fa fa-arrow-right"></i> View Course</a>
                {% endif %}
            </div>
        </div>
    </div>
    {% endfor %}

{% if not session.has('is_logged_in') %}
    {% include "partials/call-to-action.volt" %}
{% endif %}

<div class="spacer-80"></div>

{% endblock %}
