{% extends "templates/full.volt" %}

{% block head %}
{% endblock %}

{% block title %}
<h1>Admin: Notifications</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url('admin') }}">Admin</a></li>
    <li class="active">Notifications</li>
</ol>
{% endblock %}


{% block content %}
<div class="container container-fluid">

<div class="row">
    <div class="col-md-5">
        <h1>Create Notification</h1>
        <form id="form-create-notification" class="form" method="post" action="{{ url('admin/doNotification') }}">
            <div class="form-group">
                <label>(Optional) Only show to people Purchased:</label>
                <select name="product_id" class="form-control">
                    <option value="">(None)</option>
                    {% for product in products %}
                        <option value="{{ product.id }}">{{ product.title }}</option>
                    {% endfor %}
                </select>
            </div>
            <div class="form-group">
                <label>Notification</label>
                <textarea name="content" class="form-control" rows="5"></textarea>
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="submit" value="Save" />
                <br /><small>* Will not show for new registrations</small>
            </div>
            <input type="hidden" name="{{ tokenKey }}" value="{{ token }}" />
        </form>
    </div>
    <div class="col-md-7">
    <h1>Existing Notifications</h1>
    <table class="table table-striped">
    {% for notification in notifications %}
    <tr>
        <td>
            {{ notification.getOffset('created_at') }}
        </td>
        <td>
            <a class="delete" href="{{ url('admin/doNotificationDelete') }}/{{ notification.id }}">Delete</a>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            {{ notification.content }}
        </td>
    </tr>
    {% endfor %}
    </table>
</div>
<hr />

<script>
$(function() {
    $('.delete').click(function(evt) {
        var c = confirm('Are you sure you want to delete?');
        if (!c) return false;
    });
});
</script>
</div>
{% endblock %}