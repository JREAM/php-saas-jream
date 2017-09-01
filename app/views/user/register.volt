{% extends "templates/sidebar.volt" %}

{% block title %}
<span class="title">Register</span>
{% endblock %}

{% block content %}
<div id="full" class="container container-fluid inner">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            {{ flash.output() }}
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-2">
            <form id="formUserRegister" class="form-signin" method="post" action="{{ url('api/auth/register') }}">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <img class="jream-icon-login" src="{{ config.url_static }}img/logo/icon-sm.svg">
                        <h4>Register</h4>
                    </div>
                    <div class="panel-body">
                        <p class="text-center">
                        Register a JREAM account.
                        </p>

                        <div class="form-group">
                            {{ form.render('alias') }}
                        </div>

                        <div class="form-group">
                            {{ form.render('email') }}
                        </div>

                        <div class="form-group">
                            {{ form.render('password') }}
                        </div>

                        <div class="form-group">
                            {{ form.render('confirm_password') }}
                        </div>

                        <div class="form-group">
                            <div class="form-results"></div>
                        </div>

                        {{ form.render('submit') }}

                        <hr />

                        <p class="muted font-14">
                        By Registering you agree to the <a target="_blank" href="{{url('index/terms')}}">Terms</a>.
                        </p>

                        <input class="csrf-field" type="hidden" name="{{ tokenKey }}" value="{{ token }}" />

                    </div>
                </div>

            </form>
        </div>
        <div class="col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4><i class="fa fa-facebook-official opacity-50" aria-hidden="true"></i> Social Register</h4>
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
{% endblock %}
