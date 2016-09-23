{% extends "templates/full.volt" %}

{% block head %}
{% endblock %}

{% block title %}
<h1>Admin: Promo</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url('admin') }}">Admin</a></li>
    <li class="active">Promo</li>
</ol>
{% endblock %}


{% block content %}
<div class="container container-fluid">

    <a href="{{ url('admin/promo/create') }}" class="btn btn-primary btn-xl">Create Promo</a>

    <div class="row">

        <div class="col-md-8">
            <h1>Promos</h1>
            <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Code</th>
                    <th>Percent Off</th>
                    <th>Expires On</th>
                    <th>Is Visible</th>
                    <th>Is Deleted</th>
                    <th>Use Count</th>
                    <th colspan="2">Options</th>
                </tr>
            </thead>
            {% for promo in promos %}
                <tr>
                    <td>{{ promo.id }}</td>
                    <td>{{ promo.code }}</td>
                    <td>{{ promo.percent_off }}</td>
                    <td>{{ promo.is_visible }}</td>
                    <td>{{ promo.is_deleted }}</td>
                    <td>{{ promo.expires_on }}</td>
                    <td>?</td>
                    <td><a href="#">Delete</a></td>
                </tr>
            {% endfor %}
            </table>
        </div>
    </div>

</div>

<hr />
{% endblock %}