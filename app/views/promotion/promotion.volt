{% extends "templates/sidebar.volt" %}

{% block title %}
<span class="title">Promotions</span>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url() }}">Home</a></li>
    <li class="active">Promotions</li>
</ol>

<div class="social-share">
{% include 'templates/partials/addthis.volt' %}
</div>
{% endblock %}

{% block content %}
<div class="row">
    <div class="col-md-12">

    {% if !has_promotion %}

        <h2>Promotion? Uh oh!</h2>

        <p>
        Sorry! There a no promotions running, they are few and far between!
        </p>

    {% else %}

        <h2>Active Promotion(s)</h2>

            {% for promo in promotions %}
                <div>
                    <b>{{ promo.code }}</b>
                    <p>
                        {{ promo.description }}
                    </p>
                    <p>
                        {{ promo.expires_at }}
                    </p>
                    <p>
                        {{ promo.percent_off }} Percent off
                    </p>
                    <p>
                        Old Price HERE, New {{ promo.price }} Price
                    </p>
                </div>
                {{ promo.id }}
                {{ promo.product_id }}
                {{ promo.product_id_list }}
                {{ promo.code }}
                {{ promo.price }}
                {{ promo.expires_at }}
                {{ promo.use_limit }}
                {{ promo.use_count }}
                <br>
            {% endfor %}



        <div class="clear spacer-40"></div>

    {% endif %}

    </div>

</div>


<div class="spacer-80"></div>


{% endblock %}



{% block sidebar %}

<div class="col-md-12">

    {% if has_promotion %}
        <h2>Details</h2>

    {% endif %}
</div>


{% endblock %}
