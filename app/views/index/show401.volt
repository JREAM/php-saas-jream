{% extends "templates/full.volt" %}

{% block title %}
<span class="title">Unauthorized Access</span>
{% endblock %}

{% block hero %}
{% endblock %}

{% block content %}
<div class="container container-fluid">

    <div class="spacer-60"></div>

    <div class="row">
        <div class="col-md-4">
            <img src="{{ url('images/icons/256/coffee.png') }}" class="img-responsive">
        </div>
        <div class="col-md-8">
        <h1>Unauthorized Access</h1>
            <p>
            Head over to <a href="{{ url() }}">JREAM</a> and you may find what you were looking for.
            </p>
        </div>
    </div>
</div>

<div class="spacer-80"></div>

{% endblock %}
