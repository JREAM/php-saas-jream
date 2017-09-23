{% extends "templates/full.volt" %}

{% block title %}
<span class="title">An Error Occured</span>
{% endblock %}

{% block hero %}
<div id="hero">
    <div class="container container-fluid">
        <div class="row">
            <div class="col-xs-12 inner">
                {#<canvas id="error-page-canvas"></canvas>#}
            </div>
        </div>
    </div>
</div>
{% endblock %}

{% block content %}
<div class="container container-fluid">

    <div class="spacer-60"></div>

    <div class="row">
        <div class="col-md-12">
            <h1>An Error Occured</h1>
            <p>
                {% if message is defined %}
                    {{ message }}
                {% else %}
                    Please return to JREAM and try again.
                {% endif %}
            </p>
        </div>
    </div>
</div>

<div class="spacer-80"></div>

{% endblock %}
