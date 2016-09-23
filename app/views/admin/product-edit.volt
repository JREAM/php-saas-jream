{% extends "templates/full.volt" %}

{% block head %}
{% endblock %}

{% block title %}
<h1>Admin: Products</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url('admin') }}">Admin</a></li>
    <li><a href="{{ url('admin/product') }}">Products</a></li>
    <li class="active">{{ product.title }}</li>
</ol>
{% endblock %}


{% block content %}
<div class="container container-fluid">

    <h2>{{ product.title }}</h2>
    <table class="table">
    <thead>
        <th>ID</th>
        <th>Title</th>
        <th>Section</th>
        <th>Course</th>
        <th></th>
    </thead>
    {% for course in courses %}
    <tr>
        <td>{{ course.id }}</td>
        <td>{{ course.name }}</td>
        <td>{{ course.section }}</td>
        <td>{{ course.course }}</td>
        <td>{{ course.description }}</td>
        <td><a href="{{ url('admin/product/editcourse') }}/{{ product.id}}/{{ course.id }}">Edit</a>
    </tr>
    {% endfor %}
    </table>

    <div>
        <form>

    </div>

</div>
{% endblock %}