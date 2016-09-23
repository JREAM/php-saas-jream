{% extends "templates/full.volt" %}

{% block title %}
{% endblock %}

{% block intro %}
<div id="login-intro">

    <div class="container container-fluid inner">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                {{ flash.output() }}
            </div>
        </div>
        <div class="row">
        <div class="col-md-4 col-md-offset-2">
            <form class="form-login" method="post" action="{{ url('user/doLogin') }}">
                <div class="panel panel-primary">
                <div class="panel-heading">
                    <img class="jream-icon-login" src="{{ config.url_static }}img/jream-ico-24.png">
                    <h4>Login</h4>
                </div>
                <div class="panel-body">

                    <div class="form-group">
                        {{ form.render('email') }}
                    </div>

                    <div class="form-group">
                        {{ form.render('password') }}
                    </div>

                    {{ form.render('submit') }}
                    <input type="hidden" name="{{ tokenKey }}" value="{{ token }}" />

                    <br />
                    <p class="text-center font-14"">
                        <a href="{{ url('user/register')}}">Register</a>
                        &nbsp; &nbsp;
                        <a href="{{ url('user/password')}}">Forgot Password</a>
                    </p>

                </div>
                </div>
            </form>
        </div>
        <div class="col-md-4">
            <div class="panel panel-primary">
            <div class="panel-heading">
                <h4><i class="fa fa-facebook-official opacity-50" aria-hidden="true"></i> Social Login</h4>
            </div>
            <div class="panel-body">
                <p class="text-center">
                    <a href="{{ fbLoginUrl }}" class="btn btn-block btn-lg btn-social btn-facebook">
                        <i class="fa fa-facebook"></i> Sign in with Facebook
                    </a>
                </p>
                <p class="text-center">
                    <a href="{{ url('index/terms#facebook-privacy') }}"><small>Facebook Privacy</small></a>
                </p>
            </div>
            </div>

        </div>
        </div>
    </div>
</div>
{% endblock %}

{% block content %}
{% endblock %}