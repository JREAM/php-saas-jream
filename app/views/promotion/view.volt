{% extends "templates/sidebar.volt" %}

{% block head %}
{% endblock %}

{% block hero %}
<div id="hero">
    <div class="container container-fluid">
        <div class="row">
            <div class="col-xs-12 inner">
                <h2><i class="fa fa-hand-o-up"></i> Promotions</h2>
            </div>
        </div>
    </div>
</div>
{% endblock %}

{% block title %}
<span class="title">Promotion: -TITLE-</span>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url() }}">Home</a></li>
    <li><a href="{{ url('promotion') }}">Promotions</a></li>
    <li class="active">-TITLE-</li>
</ol>
{% endblock %}

{% block content %}
<div class="row">
    <div class="col-md-12">

    {% if !has_promotion %}

        <div class="alert alert-warning">
            <p>
            Sorry! There a no promotions running, they are few and far between!
            </p>
        </div>

    {% else %}

        <div class="margin-10-left margin-10-top">

            <h3 class="margin-0 pull-right">Active Promotion</h3>

            <div class="list-group">
                <div class="list-group-item primary">
                Highlight the Courses You'd Like
                </div>
            {% for product in products %}
                <a class="list-group-item selectable">
                    <i class="fa fa-check"></i>
                    <input type="hidden" value="{{ product.id }}" name="item[{{ product.id }}]">
                    {{ product.title }}
                    <strike>{{ product.price }}</strike>
                </a>
            {% endfor %}
            </div>

            <button class="btn btn-success btn-lg">Checkout and Start Learning</button>


            <div class="clear"></div>

        </div>

        <div class="clear spacer-40"></div>

    {% endif %}

    </div>

</div>

<div class="spacer-80"></div>

{% endblock %}



{% block sidebar %}

<div class="col-md-12">

    {% if has_promotion %}
    <h2>Promotion</h2>
    <p>
        The current promotion is offering {{ promotion.percent_off }} for a limited time.
    </p>

    <p>
        This offer ends soon, get it before {{ promotion.description }}.
    </p>
    {% endif %}
</div>


{% endblock %}
