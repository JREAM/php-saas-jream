{% extends "templates/full.volt" %}

{% block title %}
<span class="title">Updates</span>
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

<div class="spacer-80"></div>

{% endblock %}
