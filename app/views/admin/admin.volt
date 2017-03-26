{% extends "templates/full.volt" %}

{% block head %}
{% endblock %}

{% block title %}
<h1>Admin</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li class="active">Admin</li>
</ol>
{% endblock %}


{% block content %}
<div class="container container-fluid">

    <div class="row">

        <div class="col-md-3">

            <ul class="admin-menu">
                <li><a href="{{ url('admin/user') }}" class="btn btn-block btn-xl btn-primary">Users</a></li>
                <li><a href="{{ url('admin/product') }}" class="btn btn-block btn-xl btn-primary">Products</a></li>
                <!-- <li><a href="{{ url('admin/notification') }}" class="btn btn-block btn-xl btn-primary">Notifications</a></li> -->
                <li><a href="{{ url('admin/promo') }}" class="btn btn-block btn-xl btn-primary">Promo Codes</a></li>
                <li><a href="{{ url('admin/transaction') }}" class="btn btn-block btn-xl btn-info">Transactions</a></li>
                <li><a href="{{ url('admin/email') }}" class="btn btn-block btn-xl btn-warning">Email Customers</a></li>
                <!-- <li><a href="{{ url('admin/quiz') }}" class="btn btn-block btn-xl btn-default">Quizzes</a></li> -->
            </ul>

        </div>
        <div class="col-md-8">

            <table class="table">
            <thead>
                <th>Stats</th>
                <th>Users Registered</th>
                <th>Sales</th>
            </thead>
            <tr>
                <td>Today</td>
                <td>{{ registered_today }}</td>
                <td>{{ sales_today }}</td>
            </tr>
            <tr>
                <td>Last Week</td>
                <td>{{ registered_week }}</td>
                <td>{{ sales_week }}</td>
            </tr>
            <tr>
                <td>Last Month</td>
                <td>{{ registered_month }}</td>
                <td>{{ sales_month }}</td>
            </tr>
            <tr>
                <td>Last 3 Months</td>
                <td>{{ registered_3_month }}</td>
                <td>{{ sales_3_month }}</td>
            </tr>
            <tr>
                <td>Last 6 Months</td>
                <td>{{ registered_6_month }}</td>
                <td>{{ sales_6_month }}</td>
            </tr>

            <tr>
                <td>All Time</td>
                <td>{{ registered_all_time }}</td>
                <td>{{ sales_all_time }}</td>
            </tr>
            </table>


        </div>

    </div>

</div>
{% endblock %}
