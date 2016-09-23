{% extends "templates/full.volt" %}

{% block title %}
<h1>Thanks</h1>
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
                Your email has been delivered. I will will reply to you at a generally within 48 hours.
            </p>
        </div>
    </div>

</div>

{% endblock %}