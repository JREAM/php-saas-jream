{% extends "templates/full.volt" %}

{% block head %}
{% endblock %}

{% block title %}
<h1>Admin: Email</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url('admin') }}">Admin</a></li>
    <li><a href="{{ url('admin/email') }}">Admin</a></li>
    <li class="active">Create</li>
</ol>
{% endblock %}


{% block content %}
<div class="container container-fluid">

    <div class="row">
        <div class="col-md-8">
            <h1>Create Email</h1>
            <form id="form-create-email" class="form-group" method="post" action="{{ url('admin/email/doSave') }}">
                <div class="form-group">
                    <label>Subject</label>
                    <input type="text" name="subject" class="form-control input-xl">
                </div>
                <div class="form-group">
                    <label>Content</label>
                    <textarea name="content" class="form-control" rows="15"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <h4>Purchase Status</h4>
                        <div class="checkbox">
                            <label><input type="radio" name="purchase_status" checked="checked" value="all"> All</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="radio" name="purchase_status" value="has_purchased"> Has Purchased</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="radio" name="purchase_status" value="not_purchased"> Has Not Purchased</label>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <h4>Login Status</h4>
                        <div class="checkbox">
                            <label><input type="radio" name="login_status" value="all" checked="checked"> All</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="radio" name="login_status" value="never"> Never Logged In</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="radio" name="login_status" value="gte30"> Login GTE 30 days</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="radio" name="login_status" value="gte60"> Logged GTE 60 days</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="radio" name="login_status" value="gte90"> Logged GTE 90 days</label>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="{{ tokenKey }}" value="{{ token }}" />

                <div class="form-group">
                    <input id="save" class="btn btn-primary btn-lg" type="submit" value="Save Email" />
                    <span id="loading" class="btn btn-default btn-lg hide"><img src="{{ url('img/ajax-loader.gif') }}" alt="Loading icon" /> Loading</span>
                </div>

            </form>
        </div>
        <div class="col-md-4">
            <h1>Info</h1>
            {{ user['total'] }}
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div id="errors">
            </div>
        </div>
    </div>
</div>

<hr />

<script>
$(function() {
    $("#form-create-email").submit(function(evt) {
        evt.preventDefault();

        $("#save").addClass('hide');
        $("#loading").removeClass('hide');

        $(this).serialize();
        $.post('{{ url("admin/email/docreate") }}', function(obj) {
            if (obj.result == 1) {
                obj.data.id
            } else {
                $("#errors").html(obj.result.error)
            }
            $("#save").removeClass('hide');
            $("#loading").addClass('hide');
        })
    });
});
</script>
{% endblock %}