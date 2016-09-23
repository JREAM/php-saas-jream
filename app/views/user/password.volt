{% extends "templates/full.volt" %}

{% block title %}
    <h1>Forgot Password</h1>
{% endblock %}

{% block head %}
{% endblock %}

{% block breadcrumb %}
<div class="spacer-40"></div>
{% endblock %}

{% block content %}
<div class="container container-fluid">
    <div class="row">
    <div class="col-md-4 col-md-offset-2">
        <form id="form-password-reset" class="form-signin" method="post" action="{{ url('user/doPasswordReset') }}">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4><i class="fa fa-key opacity-50" aria-hidden="true"></i> Forgot Password</h4>
                </div>
                <div class="panel-body">

                    <p class="muted text-center font-14">
                        Please provide the email you signed up with.
                    </p>

                    <div class="form-group">
                        {{ form.render('email') }}
                    </div>

                        {{ form.render('submit') }}

                    <input type="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}" />
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-4">
        <h2 class="margin-0-top">Recovery is Easy!</h2>
        <p>
            Provide your email and you will receive a link to reset your password.
            For your security, your reset link be valid for 10 minutes.
        </p>
        <p>
            If you do not receive an email, please check your <strong>spam</strong>.
        </p>
        <p>
            <b>This does not apply to Facebook logins.</b>
        </p>
    </div>
    </div>
</div>

<div class="spacer-80"></div>

{% endblock %}