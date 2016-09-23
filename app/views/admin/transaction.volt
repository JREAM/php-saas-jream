{% extends "templates/full.volt" %}

{% block head %}
<link rel="stylesheet" href="{{ config.url_static }}third-party/jquery-datatables/css/jquery.dataTables.min.css" type="text/css">
<script src="{{ config.url_static }}third-party/jquery-datatables/js/jquery.dataTables.min.js"></script>
{% endblock %}

{% block title %}
<h1>Admin: Transactions</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url('admin') }}">Admin</a></li>
    <li class="active">Transactions</li>
</ol>
{% endblock %}


{% block content %}
<div class="container container-fluid">

    <table class="table">
    <thead>
        <th>ID</th>
        <th>User ID</th>
        <th>Transaction ID</th>
        <th>Type</th>
        <th>Gateway</th>
        <th>Amount</th>
        <th>Amount after Discount</th>
        <th>Created At</th>
    </thead>
    {% for transaction in page.items %}
    <tr>
        <td>{{ transaction.id }}</td>
        <td>{{ transaction.user_id }}</td>
        <td>{{ transaction.transaction_id }}</td>
        <td>{{ transaction.type }}</td>
        <td>{{ transaction.gateway }}</td>
        <td>{{ transaction.amount }}</td>
        <td>{{ transaction.amount_after_discount }}</td>
        <td>{{ transaction.created_at }}</td>
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


<div class="container container-fluid">
    <div class="row">
        <div class="col-md-4">
            <h2>Create Transaction</h2>
            <form id="form-transaction-create" class="form" method="post" action="{{ url('admin/transaction/create') }}">
                <div class="form-group">
                    <select name="user_id" class="form-control select2">
                        {% for user in users %}
                            <option value="{{ user.id }}">{{ user.getAlias(user.id) }} | {{ user.getEmail(user.id) }} ({{ user.id }})   </option>
                        {% endfor %}
                    </select>
                </div>
                <div class="form-group">
                    <select name="product_id" class="form-control select2">
                        {% for product in products %}
                            <option value="{{ product.id }}">{{ product.title }} ({{ product.id }})</option>
                        {% endfor %}
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" name="transaction_id" class="form-control" placeholder="Transaction ID">
                </div>
                <div class="form-group">
                    <input type="text" name="amount" class="form-control" placeholder="Amount">
                </div>
                <div class="form-group">
                    <label></label>
                    <input type="submit" class="btn btn-lg btn-primary" class="form-control">
                </div>

            </form>
        </div>
    </div>
</div>
{% endblock %}