{% extends "templates/full.volt" %}

{% block title %}
<h1>Live Training</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url() }}">Home</a></li>
    <li class="active">Services</li>
</ol>

<div class="social-share">
{% include 'inc/addthis.volt' %}
</div>
{% endblock %}


{% block content %}
<div class="row">
    <div class="col-md-11">
        <h1>Services</h1>
        <p>
            Please select a service from the sub-menu.
        </p>
    </div>

    <div class="spacer-40"></div>

</div>
{% endblock %}