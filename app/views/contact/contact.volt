{% extends "templates/full.volt" %}

{% block title %}
<span class="title">Contact JREAM</span>
{% endblock %}

{% block hero %}
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url() }}">Home</a></li>
    <li class="active">Contact</li>
</ol>
{% endblock %}


{% block content %}

<div class="container container-fluid">
    <div class="row">
        <div id="form-errors" class="alert alert-danger hidden">
        </div>

    </div>
    <div class="row">
        <div class="col-md-11">
            <h1>Contact JREAM</h1>
            <p class="muted col-md-8">
                If you are interested in custom development, please provide any applicable details such as:
                Service, Language and/or Framework, Goals, and Budget.
            </p>
            <hr>
            <div class="row">
                <div class="col-md-8">
                    <form id="formContact" class="form-login" method="post" action="{{ url('api/contact/send') }}">
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">
                                {{ form.render('name') }}
                                </div>

                                <div class="form-group">
                                {{ form.render('email') }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {% if constant('\APPLICATION_ENV') != constant('\APP_DEVELOPMENT') %}
                                    <div id="recaptcha" class="g-recaptcha" data-sitekey="6LfHCAYTAAAAALb7zfhNEaWLklfHO-MMoIjsKlHV"></div>
                                    {% else %}
                                    <b>Not Showing Captcha, in LOCAL mode.</b>
                                    {% endif %}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ form.render('message', ["rows": 15]) }}
                                </div>

                                <div class="form-group">
                                    <div class="alert alert-warning text-center">
                                        <span class="glyphicon glyphicon-warning-sign"></span> Please do not use this form for regarding YouTube tutorials.
                                    </div>
                                </div>

                                <div class="form-group">
                                    {{ form.render('submit') }}

                                    <input class="csrf-field" type="hidden" name="{{ tokenKey }}" value="{{ token }}" />

                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="margin-none">Services Offered</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="list-group">
                                <li class="list-group-item">Full Stack Services</li>
                                <li class="list-group-item">Python Development</li>
                                <li class="list-group-item">PHP Development</li>
                                <li class="list-group-item">Server Architecture</li>
                                <li class="list-group-item">System Enhancements</li>
                            </ul>
                        </div>
                        <div class="panel-footer text-center">Remotely Working from FL, USA</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>


<div class="spacer-80"></div>
{% endblock %}
