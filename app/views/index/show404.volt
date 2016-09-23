{% extends "templates/full.volt" %}

{% block title %}
<h1>Page Not Found</h1>
{% endblock %}

{% block content %}
<div class="container container-fluid">

    <div class="spacer-60"></div>

    <div class="row">
        <div class="col-md-4">
            <img src="{{ url('img/ico/256/coffee.png') }}" class="img-responsive">
        </div>
        <div class="col-md-8">
        <h1>This Page Doesn't Exist</h1>
            <p>
            Head over to <a href="{{ url() }}">JREAM</a> and you may find what you were looking for.
            </p>
        </div>
    </div>
</div>
{% endblock %}
