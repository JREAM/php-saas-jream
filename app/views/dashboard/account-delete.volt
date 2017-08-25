{% extends "templates/full.volt" %}

{% block title %}
<span class="title">Account</span>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url('dashboard') }}">Dashboard</a></li>
    <li><a href="{{ url('dashboard/account') }}">Account</a></li>
    <li class="active">Delete</li>
</ol>
{% endblock %}

{% block content %}
<div class="container container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-primary">
                <div class="panel-heading">Delete Account</div>
                <div class="panel-body">
                    <ul>
                        <li><b>Removing your account is PERMANENT.</b></li>
                        <li>Purchase records/transaction ID's will remain.</li>
                        <li>However, you will not be able to reclaim any previous purchase(s).</li>
                        <li>You will not be able to re-create or login with the same credentials ever again.</li>
                    </ul>

                    <hr>

                    <form id="form-account-delete" class="form" method="post" action="{{ url('dashboard/account/doDelete') }}">
                        <div class="form-group">
                            <label class="control-label">To confirm type: <span class="txt-delete-confirm">&nbsp; delete {{ user.getAlias() }} &nbsp;</span></label>
                            <input type="text" class="form-control" name="confirm">
                        </div>
                        <div class="form-group">
                            <label></label>
                            <input type="checkbox" name="understand"> <small> I have read the warning(s) above and agree.</small>
                        </div>
                        <div class="form-group">
                            <input class="disable-click btn btn-lg btn-danger pull-right" type="submit" value="Delete Account">
                        </div>

                        <input class="csrf-field" type="hidden" name="{{ tokenKey }}" value="{{ token }}" />

                    </form>
                </div>
            </div>

        </div>
        </div>


    </div>
</div>

</div>

<div class="spacer-80"></div>
{% endblock %}
