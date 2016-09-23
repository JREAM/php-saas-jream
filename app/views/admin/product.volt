{% extends "templates/full.volt" %}

{% block head %}
<link rel="stylesheet" href="{{ config.url_static }}third-party/jquery-datatables/css/jquery.dataTables.min.css" type="text/css">
<script src="{{ config.url_static }}third-party/jquery-datatables/js/jquery.dataTables.min.js"></script>
{% endblock %}

{% block title %}
<h1>Admin: Products</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url('admin') }}">Admin</a></li>
    <li class="active">Products</li>
</ol>
{% endblock %}


{% block content %}
<div class="container container-fluid">

    <a class="btn btn-primary btn-lg" href="{{ url('admin/product/create') }}">Create</a>

    <table class="table">
    <thead>
        <th>ID</th>
        <th>Type</th>
        <th>Source</th>
        <th>Price</th>
        <th>Status</th>
        <th></th>
    </thead>
    {% for product in products %}
    <tr>
        <td>{{ product.id }}</td>
        <td>{{ product.type }}</td>
        <td>{{ product.path }}</td>
        <td>{{ product.price }}</td>
        <td>{{ product.status }}</td>
        <td><a href="{{ url('admin/product/edit') }}/{{ product.id }}">Edit</a>
    </tr>
    {% endfor %}
    </table>
</div>
{% endblock %}