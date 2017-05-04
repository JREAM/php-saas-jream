{% extends "templates/full.volt" %}

{% block title %}
<h1>Support</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url('dashboard') }}">Dashboard</a></li>
    <li class="active">Support</li>
</ol>
{% endblock %}

{% block content %}
<div class="container container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading"><span class="glyphicon glyphicon-comment opacity-50"></span> Support Form</div>
                <div class="panel-body">
                    <p>
                        <small>
                        Support is provided on an as-is basis for problems arising from content on
                        JREAM. This does not include any support for YouTube videos.
                        </small>
                    </p>
                    <form id="form-support" method="post" action="{{ url('dashboard/support/do') }}">
                        <div class="form-group">
                            <input type="text" name="title" class="form-control" placeholder="Title" value="<?=formData('title')?>">
                        </div>
                        <div class="form-group">
                            <select name="type" class="form-control">
                                {% for key, type in types %}
                                <option value="{{ key }}">{{ type }}</option>
                                {% endfor %}
                            </select>
                        </div>
                        <div class="form-group">
                            <textarea name="content" class="form-control" placeholder="Your Message" rows="5"><?=formData('content')?></textarea>
                        </div>
                        <p>
                            <small>
                            If this is related to a specific course, please include the course title and any relevant sections being affected.
                            </small>
                        </p>
                        <div class="form-group">
                            <input class="disable-click btn btn-lg btn-primary btn-block" type="submit" value="Submit">
                        </div>
                        <input type="hidden" name="{{ tokenKey }}" value="{{ token }}" />
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6 troubleshooting">
            <div class="panel panel-primary">
                <div class="panel-heading"><i class="fa fa-question opacity-50" aria-hidden="true"></i> Troubleshooting</div>
                <div class="panel-body">
                    <p>
                        <strong>I need source code</strong>
                        <br />Any course that contains coding will have a repository at <a href="https://github.com/jream-network" target="_blank"><i class="fa fa-github"></i> JREAM Network</a>.
                    </p>
                    <p>
                        <strong>Video or Download Missing</strong>
                        <br />Please fill out the form and provide the course series and video id or link.
                    </p>
                    <p>
                        <strong>Video Stopped Playback</strong>
                        <br />Refreshing the page will generate a new access token for your video.
                    </p>
                    <p>
                        <strong>Video won't Play</strong>
                        <br />Make sure you have <a href="http://get.adobe.com/flashplayer/"><i class="fa fa-bolt"></i> Flash Player</a> installed to stream the content.
                        Otherwise please provide the series and and video id or link.
                    </p>
                    <p>
                        <strong>Cannot Download Videos</strong>
                        <br />This is a streaming service only, downloads are not provided.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

{% endblock %}
