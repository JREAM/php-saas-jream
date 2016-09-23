{% extends "templates/full.volt" %}

{% block title %}
<h1>Updates</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url() }}">Home</a></li>
    <li class="active">Updates</li>
</ol>
{% endblock %}

{% block content %}
<div class="container">
    <div class="row">
        <div class="col-md-9">

        <h2>Updates</h2>
        {{ updates }}

        </div>
    </div>
</div>

{% endblock %}