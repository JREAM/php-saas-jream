{% extends "templates/full.volt" %}

{% block head %}
{% endblock %}

{% block title %}
<h1>Admin: Email Previe</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url('admin') }}">Admin</a></li>
    <li><a href="{{ url('admin/email') }}">Email</a></li>
    <li class="active">Preview</li>
</ol>
{% endblock %}


{% block content %}
<div class="container container-fluid">

    <div class="row">
        <div class="col-md-12">
            <h1>Previewing Email</h1>
            {{ email.id }}
            {{ email.subject }}
            {{ email.purchase_status }}
            {{ email.login_status }}
        </div>
    </div>

</div>

{% endblock %}