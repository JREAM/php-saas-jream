{% extends "templates/full.volt" %}

{% block head %}
{% endblock %}

{% block title %}
<h1>Admin: Live</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url('admin') }}">Admin</a></li>
    <li class="active">Live</li>
</ol>
{% endblock %}


{% block content %}
<div class="container container-fluid">

    <div class="row">

        <a href="{{ url('admin/live/create') }}" class="btn btn-primary btn-xl">Create Live</a>

        <div class="col-md-8">
            <h1>Current/Last Live</h1>
            list here..
        </div>
        <div class="col-md-4">
            <h1>Attendees</h1>
            list here..
        </div>
    </div>
</div>

<hr />
{% endblock %}