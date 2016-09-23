{% extends "templates/full.volt" %}

{% block head %}
{% endblock %}

{% block title %}
<h1>Admin: Live</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url('admin') }}">Admin</a></li>
    <li><a href="{{ url('admin/live') }}">Live</a></li>
    <li class="active">Create</li>
</ol>
{% endblock %}


{% block content %}
<div class="container container-fluid">

    <div class="row">
        <div class="col-md-8">
            <h1>Create Live Session</h1>
            <form id="form-create-email" class="form-group" method="post" action="{{ url('admin/live/doSave') }}">
                <div class="form-group">
                    <label>Subject</label>
                    <input type="text" name="subject" class="form-control input-xl">
                </div>
                <div class="form-group">
                    <label>Content</label>
                    <textarea name="content" class="form-control" rows="15"></textarea>
                </div>

                <input type="hidden" name="{{ tokenKey }}" value="{{ token }}" />

                <div class="form-group">
                    <input class="btn btn-primary btn-lg" type="submit" value="Save Live Session" />
                </div>


                Published?
                (Only ONE can be at a time)

            </form>
        </div>
    </div>
</div>

{% endblock %}