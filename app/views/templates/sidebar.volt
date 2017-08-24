<!DOCTYPE html>
<html lang="en-US">
<head>
{% include 'templates/partials/head.volt' %}
{% block head %}{% endblock %}
{% block style %}{% endblock %}
</head>
<body id="page-{{ router.getControllerName()|lower }}{% if router.getActionName() is not '' %}-action-{{ router.getActionName()|lower }}{% endif %}">

{% include 'templates/partials/header.volt' %}
{% block hero %}{% endblock %}
<div class="point"></div> {# For WayPoint Detection #}

<div id="title-bar">
    <div class="container container-fluid">
        <div class="col-md-12">
            {% block title %}{% endblock %}
        </div>
    </div>
</div>

<div id="breadcrumb-wrapper" class="container container-fluid">
    <div class="col-md-12">
        {% block breadcrumb %}{% endblock %}
    </div>
</div>

<div class="container container-fluid">
    <div class="col-md-12">
        {{ flash.output() }}
    </div>
</div>

<div class="container container-fluid" id="content">
    <div class="col-md-8">
        {% block content %}{% endblock %}
    </div>
    <div class="col-md-4">
        {% block sidebar %}{% endblock %}
    </div>
</div>

{% include "templates/partials/footer.volt" %}

{% block script %}{% endblock %}
</body>
</html>
