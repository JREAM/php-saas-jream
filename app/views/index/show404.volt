{% extends "templates/full.volt" %}

{% block title %}
<span class="title">Page Not Found</span>
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
            <img src="{{ url('images/icons/256/coffee.png') }}" class="img-responsive">
        </div>
        <div class="col-md-8">
        <h1>This Page Doesn't Exist</h1>
            <p>
            Head over to <a href="{{ url() }}">JREAM</a> and you may find what you were looking for.
            </p>
        </div>
    </div>
</div>

<div class="spacer-80"></div>

{% endblock %}
