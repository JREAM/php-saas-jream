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

    <div class="row">

        <div class="col-md-6">
            <h1>Create Promo</h1>
            <form id="form-create-promo" class="form" method="post" action="{{ url('admin/promo/docreate') }}">
                <div class="form-group">
                    <label>Product</label>
                    <select name="product_id" class="form-control">
                        <option value="">(None)</option>
                        {% for product in products %}
                            <option value="{{ product.id }}">{{ product.title }}</option>
                        {% endfor %}
                    </select>
                </div>
                <div class="form-group">
                    <label>Is Visible</label>
                    <select name="is_visible" class="form-control">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Code</label>
                    <input type="text" name="code">
                </div>
                <div class="form-group">
                    <label>Percent Off</label>
                    <input type="text" name="code">
                </div>
                <div class="form-group">
                    <label>Expires On</label>
                    <input type="text" name="expires_on">
                <input type="hidden" name="{{ tokenKey }}" value="{{ token }}" />
                </div>
                <div class="form-group">
                    <label></label>
                    <input type="submit" class="btn btn-primary" value="Save">
                </div>
            </form>
        </div>
    </div>

</div>

<hr />
{% endblock %}