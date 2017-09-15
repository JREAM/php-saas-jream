{% extends "templates/full.volt" %}

{% block title %}
<span class="title">Account</span>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url('dashboard') }}">Dashboard</a></li>
    <li class="active">Account</li>
</ol>
{% endblock %}

{% block content %}
<div class="container container-fluid">
<div class="row">
    <div class="col-md-7">
        <div class="list-group">
            <div class="list-group-item active">
                <h4><span class="glyphicon glyphicon-user opacity-50"></span> Profile</h4>
            </div>
                {% if session.has('fb_user_id') %}
                <div class="list-group-item">
                    {{ user.getIcon() }} <strong>{{ user.facebook_alias }}</strong>
                </div>
                <div class="list-group-item">
                    {{ user.facebook_email }} (facebook)
                </div>
            {% else %}
                <div class="list-group-item">
                        {{ user.getIcon() }} <strong>{{ user.alias }}</strong>
                        <a target="_blank" href="http://gravatar.com" class="pull-right">(change gravatar)</a>
                </div>
                <div class="list-group-item">
                    {{ user.email }}
                </div>
            {% endif %}
                <div class="list-group-item">
                    Timezone <strong>{{ session.get('timezone') }}</strong> <a href="#" id="toggle-timezone" class="pull-right">(change timezone)</a>

                    <form id="formDashboardAccountTimezone" class="form-inline pull-right hide" method="post" action="{{ url('api/user/updateTimezone') }}">
                        <div class="form-group">
                            <select name="timezone" class="form-control">
                            {% for timezone in timezones %}
                                <option {% if session.get('timezone') == timezone %}selected="selected" {% endif %}value="{{ timezone }}">{{ timezone }}</option>
                            {% endfor %}
                            </select>
                        </div>
                        <div class="form-group">
                            <input class="disable-click btn btn-sm btn-primary" type="submit" value="Update" />
                        </div>

                        <input class="csrf-field" type="hidden" name="{{ tokenKey }}" value="{{ token }}" />

                    </form>
                    <div class="clearfix"></div>
                </div>
                <div class="list-group-item">
                    Joined: <?=date('F dS, Y', strtotime($user->created_at))?>
                </div>

        </div>

        {% include "dashboard/account/purchase-history.volt" %}

    </div>
    <div class="col-md-5">

        {% if !session.has('fb_user_id') %}
        <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-primary">
                <div class="panel-heading"><h4><span class="glyphicon glyphicon-envelope opacity-50" aria-hidden="true"></span> Change Email</h4></div>
                <div class="panel-body">
                    <form id="formDashboardAccountEmail" method="post" action="{{ url('api/user/updateEmail') }}">
                        <div class="form-group">
                            {{ changeEmailForm.render('email') }}
                        </div>
                        <div class="form-group">
                            {{ changeEmailForm.render('confirm_email') }}
                        </div>
                        <div class="form-group">
                            <span class="muted font-10">* You will have to confirm your previous email for the change.</span>
                        </div>
                        <div class="form-group">
                            {{ changeEmailForm.render('submit', ["class": "btn btn-lg btn-primary pull-right"]) }}
                        </div>

                        <input class="csrf-field" type="hidden" name="{{ tokenKey }}" value="{{ token }}" />

                    </form>
                </div>
            </div>
        </div>
        </div>
        <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-primary">
                <div class="panel-heading"><h4><i class="fa fa-key opacity-50" aria-hidden="true"></i> Change Password</h4></div>
                <div class="panel-body">
                    <form id="formDashboardAccountPassword" method="post" action="{{ url('api/user/updatePassword') }}">
                        <div class="form-group">
                            {{ changePasswordForm.render('current_password') }}
                        </div>
                        <div class="form-group">
                            {{ changePasswordForm.render('password') }}
                        </div>
                        <div class="form-group">
                            {{ changePasswordForm.render('confirm_password') }}
                        </div>
                        <div class="form-group">
                            {{ changePasswordForm.render('submit', ["class": "btn btn-lg btn-primary pull-right"]) }}
                        </div>

                        <input class="csrf-field" type="hidden" name="{{ tokenKey }}" value="{{ token }}" />

                    </form>
                </div>
            </div>
        </div>
        </div>
        {% endif %}

        <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-primary">
                <div class="panel-heading"><h4><i class="fa fa-cog opacity-50" aria-hidden="true"></i> Email Settings</h4></div>
                <div class="panel-body">
                    <form id="formDashboardAccountNotification"  class="form" method="post" action="{{ url('api/user/updateNotifications') }}">
                        <div class="form-group">
                            <label class="control-label">Email Notifications</label>
                                <select class="form-control" name="email_notifications">
                                    <option value="1">On</option>
                                    <option value="0" {% if user.email_notifications == 0 %}selected="selected"{% endif %}>Off</option>
                                </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">System Notifications</label>
                                <select class="form-control" name="system_notifications">
                                    <option value="1">On</option>
                                    <option value="0" {% if user.system_notifications == 0 %}selected="selected"{% endif %}>Off</option>
                                </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Newsletter/Promotions</label>
                                <select class="form-control" name="newsletter_subscribe">
                                    <option value="1">Yes, Receive</option>
                                    <option value="0" {% if user.newsletter_subscribe == 0 %}selected="selected"{% endif %}>No Thanks</option>
                                </select>
                        </div>
                        <div class="form-group">
                            <input class="disable-click btn btn-lg btn-primary pull-right" type="submit" value="Update">
                        </div>

                        <input class="csrf-field" type="hidden" name="{{ tokenKey }}" value="{{ token }}" />

                    </form>
                </div>
            </div>

        </div>
        </div>


        <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <a href="{{ url('dashboard/account/delete') }}" class="pull-right"><small>Delete My Account</small></a>
        </div>
        </div>

    </div>
</div>


</div>

<div class="spacer-80"></div>
{% endblock %}
