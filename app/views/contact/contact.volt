{% extends "templates/full.volt" %}

{% block title %}
<span class="title">Contact JREAM</span>
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
            {{ constant('STAGE') }}
            <hr>
            <div class="row">
                <div class="col-md-8">
                    <form class="form-login" id="contact-form" method="post" action="{{ url('api/contact') }}">
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
                                    {% if constant('STAGE') != 'local' %}
                                    <div class="g-recaptcha" data-sitekey="6LfHCAYTAAAAALb7zfhNEaWLklfHO-MMoIjsKlHV"></div>
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
                                    <input type="hidden" name="{{ tokenKey }}" value="{{ token }}" />
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

{% block script %}
<script>
var url_recaptcha = "{{ url('api/recaptcha') }}";
var url_contact = "{{ url('api/contact') }}";
var url_redirect = "{{ url('contact/thanks') }}";

// So sloppy but gets it done.
// Need that event listener trigger in JS forgot how to do it
$(function() {
    $("#contact-form").submit(function(evt) {
        var postData = $(this).serialize();

        var submit_btn = $(this).find("input[type=submit]");

        evt.preventDefault();
        $("#form-errors").html();
        $("#form-errors").addClass('hidden');

        $.post(url_recaptcha, postData, function(obj) {
            submit_btn.attr('disabled', 'disabled');
            if (obj.result == 0) {
                $("#form-errors").html(obj.error).removeClass('hidden');
                submit_btn.removeAttr('disabled');
                return;
            }

            $.post(url_contact, postData, function(obj) {
                if (obj.result == 0)
                {
                    // string
                    if (typeof obj.error != 'object') {
                        $("#form-errors").html(obj.error).removeClass('hidden');
                        submit_btn.removeAttr('disabled');
                        return;
                    }

                    // list
                    elems = '<ul>';
                    for (var i = 0; i < obj.error.length; i++) {
                        elems += '<li>' + obj.error[i] + '</li>';
                    }
                    elems += '</ul>';
                    $("#form-errors").html(elems).removeClass('hidden');
                    submit_btn.removeAttr('disabled');
                    return;
                }

                $(location).attr('href', url_redirect);

            }, 'json');
        }, 'json');
    });
});
</script>
{% endblock %}
