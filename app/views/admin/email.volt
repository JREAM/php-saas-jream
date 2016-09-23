{% extends "templates/full.volt" %}

{% block head %}
{% endblock %}

{% block title %}
<h1>Admin: Email</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url('admin') }}">Admin</a></li>
    <li class="active">Email</li>
</ol>
{% endblock %}


{% block content %}
<div class="container container-fluid">

    <a href="{{ url('admin/email/create') }}" class="btn btn-primary btn-xl">Create Email</a>

    <div class="row">

        <div class="col-md-6">
            <h1>Emails Unsent</h1>
            <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Subject</th>
                    <th>Purchased</th>
                    <th>Login Status</th>
                    <th colspan="2">Options</th>
                </tr>
            </thead>
            {% for email in emails_unsent %}
                <tr>
                    <td>{{ email.id }}</td>
                    <td>{{ email.subject }}</td>
                    <td>{{ email.purchase_status }}</td>
                    <td>{{ email.login_status }}</td>
                    <td><a href="{{ url('admin/email/preview') }}/{{ email.id }}">Preview</a></td>
                    <td><a href="#">Send</a></td>
                </tr>
            {% endfor %}
            </table>
        </div>
        <div class="col-md-6">
            <h1>Emails Sent</h1>
            <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Subject</th>
                    <th>Purchased</th>
                    <th>Login Status</th>
                    <th colspan="2">Options</th>
                </tr>
            </thead>
            {% for email in emails_sent %}
                <tr>
                    <td>{{ email.id }}</td>
                    <td>{{ email.subject }}</td>
                    <td>{{ email.purchase_status }}</td>
                    <td>{{ email.login_status }}</td>
                    <td><a href="{{ url('admin/email/preview') }}/{{ email.id }}">Preview</a></td>
                    <td><a href="#">Send</a></td>
                </tr>
            {% endfor %}
            </table>
        </div>
    </div>
</div>

<hr />
{% endblock %}