{% extends "templates/full.volt" %}

{% block title %}
<span class="title">{{ youtube.title }}</span>
{% endblock %}

{% block breadcrumb %}
<div class="container container-fluid">
<div class="col-md-6">
    <ol class="breadcrumb">
        <li><a href="{{ url('dashboard') }}">Dashboard</a></li>
        <li><a href="{{ url('dashboard#youtube') }}">Youtube Videos</a></li>
        <li class="active">{{ youtube.title }}</li>
    </ol>
</div>
{% endblock %}

{% block content %}
<div class="row">
    <div class="col-md-12">
    <iframe width="1000" height="563" src="https://www.youtube.com/embed/{{ youtube.video_id }}?rel=0" frameborder="0" allowfullscreen></iframe>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">Description</div>
            <div class="panel-body">
            {{ youtube.description }}
            </div>
        </div>
    </div>
</div>

</div>


<div class="spacer-80"></div>
{% endblock %}
