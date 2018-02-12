{% extends "templates/full.volt" %}

{% block head %}
{% endblock %}

{% block intro %}
<div id="product-intro">
    <div class="container container-fluid">
        <div class="row">
            <div class="col-xs-12 inner">
                <h2><i class="fa fa-play-circle"></i> Unlimited Streaming &amp; Code available via Git.</h2>
            </div>
        </div>
    </div>
</div>
{% endblock %}

{% block title %}
    <h1>Products</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url() }}">Home</a></li>
    <li class="active">Products</li>
</ol>

{% include "inc/section/discount.volt" %}
<div class="spacer-40"></div>

{% endblock %}

{% block content %}

    {% for product in products %}

    <div class="container container-fluid container-product-row {% if product.hasPurchased() %}purchased{% endif %}">
        {% if product.hasPurchased() %}
            <div class="ribbon"><span>Purchased</span></div>
        {% endif %}
        <div class="row">
            <div class="col-sm-6">
                {% if product.hasPurchased() %}
                <a href="{{ url('dashboard/course/index') }}/{{ product.id }}" class="relative img-thumbnail fadeover mar-right-10">
                    <img src="{{ product.img_sm }}" alt="{{ product.title }}" />
                </a>
                {% else %}
                <a href="{{ url('product/view/') }}{{ product.slug}}" class="relative img-thumbnail fadeover mar-right-10 {% if product.status == constant('\Product::STATUS_PLANNED') %}grayscale{% endif %}">
                    <img src="{{ product.img_sm }}" alt="{{ product.title }}" />
                </a>
                {% endif %}
            </div>
            <div class="col-sm-6 relative public-product-description">

                <div class="row">
                    <div class="col-sm-8">
                        <h3 class="public-product-title"><a href="{{ url('product/view/') }}{{ product.slug}}">{{ product.title }}</a></h3>
                    </div>
                    <div class="col-sm-4">
                        <h3 class="margin-0 pull-right">
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
                        </h3>
                    </div>
                </div>

                <div>
                    <span class="label label-difficulty">Difficulty</span> {{ component.helper.productDifficulty(product.difficulty) }}
                </div>

                <div class="expandable">
                    <?=strip_tags($product->description);?>
                </div>

                {% if product.hasPurchased() %}
                    <a class="full-product" href="{{ url('dashboard/course/index') }}/{{ product.id }}"><i class="fa fa-arrow-right"></i> Go to My Course</a>
                {% else %}
                    <a class="full-product" href="{{ url('product/view/') }}{{ product.slug}}"><i class="fa fa-arrow-right"></i> View Course</a>
                {% endif %}
            </div>
        </div>
    </div>
    {% endfor %}

<div class="spacer-40"></div>

{% endblock %}
