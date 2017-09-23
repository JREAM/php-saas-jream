{% extends "templates/full.volt" %}

{% block title %}
<span class="title">Service Error</span>
{% endblock %}

{% block hero %}
<div id="hero">
    <div class="container container-fluid">
        <div class="row">
            <div class="col-xs-12 inner">
            </div>
        </div>
    </div>
</div>
{% endblock %}

{% block content %}
<div class="container container-fluid">

    <div class="spacer-60"></div>

    <div class="row">
        <div class="col-md-4">
            <img src="{{ url('images/icons/256/briefcase.png') }}" class="img-responsive">
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

<div class="spacer-80"></div>
{% endblock %}
