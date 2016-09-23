{% extends "templates/full.volt" %}

{% block title %}
    <h1>Create New Password</h1>
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
        <form class="form-signin" method="post" action="{{ url('user/doPasswordCreate') }}">
            <div class="panel panel-primary">
            <div class="panel-heading">
                <h4><i class="fa fa-key opacity-50" aria-hidden="true"></i>  Create New Password</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <input type="text" name="email" value="<?=formData('email')?>" class="form-control input-lg" placeholder="Confirm Email" autofocus>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control input-lg" placeholder="Password">
                </div>
                <div class="form-group">
                    <input type="password" name="confirm_password" class="form-control input-lg" placeholder="Confirm Password">
                </div>
                <input type="hidden" name="reset_key" value="{{ reset_key }}">
                <input class="btn btn-lg btn-primary btn-block" type="submit" value="Submit">
                <input type="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}" />
            </div>
            </div>
        </form>
    </div>
    <div class="col-md-4">
        <h2 class="margin-0-top">Last Step!</h2>
        <p>
            Great! We've confirmed your account based on the temporary random code
            you've verified through your email.
        </p>
        <p>
            For added security, please confirm the email address you received the email
            at and create your new password.
        </p>
    </div>
    </div>
</div>

<div class="spacer-80"></div>

{% endblock %}