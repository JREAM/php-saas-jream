{% extends "templates/full.volt" %}

{% block head %}
<link rel="stylesheet" href="{{ config.url_static }}third-party/jquery-datatables/css/jquery.dataTables.min.css" type="text/css">
<script src="{{ config.url_static }}third-party/jquery-datatables/js/jquery.dataTables.min.js"></script>
{% endblock %}

{% block title %}
<h1>Admin: User Edit</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url('admin') }}">Admin</a></li>
    <li><a href="{{ url('admin/user') }}">Users</a></li>
    <li class="active">Edit</li>
</ol>
{% endblock %}


{% block content %}
<div class="container container-fluid">

    <form id="form-edit-user" >
        <div class="form-group">
            <!-- <input type="text" name="email" value="{{ user.email }}" class="form-control input-lg" placeholder="" autofocus> -->
        </div>
        <div class="form-group">
            <label>Banned</label>
            <select class="form-control input-lg">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary input-lg" value="Save">
        </div>
    </form>


    {% include "inc/section/purchase-history.volt" %}

</div>


{% endblock %}