{% extends "templates/full.volt" %}

{% block title %}
<span class="title">Newsletter</span>
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
    <li><a href="{{ url('newsletter') }}">Newsletter</a></li>
    <li class="active">Newsletter Subscribe Verification</li>
</ol>
{% endblock %}

{% block content %}
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Newsletter Subscribe Verification</h1>
            <p>
            {{ result }}
            </p>
         </div>
     </div>
 </div>
{% endblock %}

