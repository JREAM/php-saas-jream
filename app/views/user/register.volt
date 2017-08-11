{% extends "templates/sidebar.volt" %}

{% block title %}
<span class="title">Register</span>
{% endblock %}

{% block hero %}
<div id="hero">

    <div class="container container-fluid inner">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                {{ flash.output() }}
            </div>
        </div>
        <div class="row">
        <div class="col-md-4 col-md-offset-2">
            <form id="form-register" class="form-signin" method="post" action="{{ url('user/doRegister') }}">
                <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4><i class="fa fa-user-plus opacity-50" aria-hidden="true"></i> Register</h4>
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

                    {{ form.render('submit') }}

                    <hr />

                    <p class="muted font-14">
                    By Registering you agree to the <a target="_blank" href="{{url('index/terms')}}">Terms</a>.
                    </p>

                    <input type="hidden" name="{{ tokenKey }}" value="{{ token }}" />
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
</div>

<div class="spacer-80"></div>
{% endblock %}
