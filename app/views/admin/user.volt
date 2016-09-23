{% extends "templates/full.volt" %}

{% block head %}
<link rel="stylesheet" href="{{ config.url_static }}third-party/jquery-datatables/css/jquery.dataTables.min.css" type="text/css">
<script src="{{ config.url_static }}third-party/jquery-datatables/js/jquery.dataTables.min.js"></script>
{% endblock %}

{% block title %}
<h1>Admin: Users</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url('admin') }}">Admin</a></li>
    <li class="active">Users</li>
</ol>
{% endblock %}


{% block content %}
<div class="container container-fluid">

    <table class="table">
    <thead>
        <th>ID</th>
        <th>Alias</th>
        <th>FB Alias</th>
        <th>Timezone</th>
        <th>Banned</th>
        <th>Created At</th>
        <th></th>
    </thead>

    {% for user in page.items %}
    <tr>
        <td>{{ user.id }}</td>
        <td>{{ user.alias }}</td>
        <td>{{ user.facebook_alias }}</td>
        <td>{{ user.timezone }}</td>
        <td>
            {% if user.banned == 1 %}
                <span class="badge badge-danger">Yes</span>
            {% else %}
                <span class="badge badge-default">No</span>
            {% endif %}
        </td>
        <td>{{ user.created_at }}</td>
        <td>
            <a href="{{ url('admin/user/edit') }}/{{user.id}}">Edit</a>
        </td>
    </tr>
    {% endfor %}
    </table>

    <div class="text-center">
        <ul class="pagination pagination-large">
            <li {% if page.current == 1 %}class="disabled"{% endif %}><a href="{{ url('admin/transaction') }}"><span class="glyphicon glyphicon-fast-backward"></span></a></li>
            <li {% if page.current == 1 %}class="disabled"{% endif %}><a href="{{ url('admin/transaction') }}?page=<?= $page->before; ?>"><span class="glyphicon glyphicon-chevron-left"></span></a></li>
            <li {% if page.current == page.total_pages %}class="disabled"{% endif %}><a href="{{ url('admin/transaction') }}?page=<?= $page->next; ?>"><span class="glyphicon glyphicon-chevron-right"></span></a></li>
            <li {% if page.current == page.total_pages %}class="disabled"{% endif %}><a href="{{ url('admin/transaction') }}?page=<?= $page->last; ?>"><span class="glyphicon glyphicon-fast-forward"></span></a></li>
        </ul>
        <p>
            You are on page {{ page.current}} of {{ page.total_pages }}
        </p>
    </div>

</div>
{% endblock %}