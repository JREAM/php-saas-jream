<!DOCTYPE html>
<html lang="en-US">
<head>
{% include 'partials/head.volt' %}
{% block head %}{% endblock %}
{% block style %}{% endblock %}
</head>
<body id="page-{{ router.getControllerName()|lower }}{% if router.getActionName() is not '' %}-action-{{ router.getActionName()|lower }}{% endif %}">

{% if router.getControllerName() == 'index' and router.getActionName() == '' %}
    {% set is_home = true %}
{% else %}
    {% set is_home = false %}
{% endif %}

{% if router.getControllerName() == 'user' and (router.getActionName() == 'login' or router.getActionName() == 'register') %}
    {% set is_login = true %}
{% else %}
    {% set is_login = false %}
{% endif %}

{% include 'partials/header.volt' %}

{% block hero %}{% endblock %}

<div class="point"></div> {# For WayPoint Detection #}

<div id="title-bar">
    <div class="container container-fluid">
        <div class="col-md-12">
            {% block title %}{% endblock %}
        </div>
    </div>
</div>

<div id="global-promotion" style="background: maroon; color: #fff;">
    <div class="container container-fluid">
        <div class="col-md-12">
            Promotion: 75% off until 10/25, 00:01:23 Remaining.
        </div>
    </div>
</div>

<div class="container container-fluid {% if is_home or is_login %}hide{% endif %}">
    <div class="col-md-12">
        {% block breadcrumb %}{% endblock %}
    </div>
</div>

{% if is_login == false %}
<div class="container container-fluid {% if is_home %}hide{% endif %}">
    <div class="col-md-12">
        {{ flash.output() }}
    </div>
</div>
{% endif %}

<div id="content">
{% block content %}{% endblock %}
</div>

{% include "partials/footer.volt" %}

{% block script %}{% endblock %}
</body>
</html>
