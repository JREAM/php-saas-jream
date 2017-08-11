{% extends "templates/full.volt" %}

{% block title %}
<span class="title">Thanks</span>
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

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url() }}">Home</a></li>
    <li>Contact</li>
    <li class="active">Thanks</li>
</ol>
{% endblock %}


{% block content %}

<div class="container container-fluid">

    <div class="row">
        <div class="col-md-11">
            <h1>Your Email was sent.</h1>
            <p>
               JREAM will will reply generally within 48 hours.
            </p>
        </div>
    </div>

</div>


<div class="spacer-80"></div>

{% endblock %}
