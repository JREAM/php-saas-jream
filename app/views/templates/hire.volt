<<<<<<< HEAD
<!doctype html>
<html lang="en-US">
<head>
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

{% block intro %}{% endblock %}

<div id="title-bar">
    <div class="container container-fluid">
        <div class="col-md-12">
            {% block title %}{% endblock %}
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

{% block content %}{% endblock %}

</body>
</html>
=======
{% extends "templates/hire.volt" %}

{% block title %}
{% endblock %}

{% block intro %}
intro
{% endblock %}

{% block content %}
<div class="spacer-20"></div>


content
{% endblock %}

>>>>>>> 561e4ffac97385c4cdb8782734bc5d13b474b891
