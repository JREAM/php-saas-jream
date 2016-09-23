{% extends "templates/full.volt" %}

{% block title %}
<h1>Service Error</h1>
{% endblock %}

{% block content %}
<div class="container container-fluid">

    <div class="spacer-60"></div>

    <div class="row">
        <div class="col-md-4">
            <img src="{{ url('img/ico/256/briefcase.png') }}" class="img-responsive">
        </div>
        <div class="col-md-8">
        <h1>There was an Internal Service Error</h1>
            <p>
                There was a problem in the application. We have logged it and will
                take care of it as soon as possible.
            </p>
        </div>
    </div>
</div>
{% endblock %}
