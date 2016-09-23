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
    <li><a href="{{ url('admin/product/edit') }}/{{ product.id }}">{{ product.title }}</a></li>
    <li class="active">{{ course.name }}</li>
</ol>
{% endblock %}


{% block content %}
<div class="container container-fluid">

    <h2>{{ course.name }}</h2>

    <table class="table">
    <thead>
        <th>ID</th>
        <th>Title</th>
        <th>Section</th>
        <th>Course</th>
        <th></th>
    </thead>
    <tr>
        <td>{{ course.id }}</td>
        <td>{{ course.name }}</td>
        <td>{{ course.section }}</td>
        <td>{{ course.course }}</td>
        <td>{{ course.description }}</td>
        <td><a href="{{ url('admin/product/editcourse') }}/{{ course.id }}">Edit</a>
    </tr>
    </table>

</div>

<div class="container container-fluid">

    <h2>Meta</h2>

    <table class="table">
    <thead>
        <th>ID</th>
        <th>Type</th>
        <th>Source</th>
        <th>Content</th>
        <th>Description</th>
        <th></th>
    </thead>
    {% for m in meta %}
    <tr>
        <td>{{ m.id }}</td>
        <td>{{ m.type }}</td>
        <td>{{ m.resource }}</td>
        <td>{{ m.content }}</td>
        <td>{{ course.description }}</td>
        <td>
            <a href="{{ url('admin/product/editcoursemeta/') }}/{{ m.id }}">Edit</a>
            <a href="{{ url('admin/product/dodeletemeta/') }}/{{ m.id }}">Edit</a>
        </td>
    </tr>
    {% endfor %}
    </table>

</div>

<div class="container container-fluid">

    <div class="row">
        <div class="col-md-5">
            <h1>Add Meta</h1>
            <form id="form-meta-add" class="form" method="post" action="{{ url('admin/product/dometaadd') }}/{{ product.id }}/{{ course.id }}">
                <div class="form-group">
                    <select name="type" >
                        <option value="link">Type: Link</option>
                        <option value="link">Type: File</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" name="source" class="form-control input-xl" placeholder="Source">
                </div>
                <div class="form-group">
                    <input type="text" name="content" class="form-control input-xl" placeholder="Content">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="content" class="form-control" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <input class="btn btn-primary btn-lg" type="submit" value="Save" />
                </div>
                <input type="hidden" name="{{ tokenKey }}" value="{{ token }}" />
            </form>
        </div>

    </div>
</div>
{% endblock %}