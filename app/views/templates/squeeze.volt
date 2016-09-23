<!doctype html>
<html lang="en-US">
<head>
<link rel="stylesheet" href="{{ url('css/squeeze.min.css') }}" type="text/css">
{% block head %}{% endblock %}
</head>
<body id="page-{{ router.getControllerName()|lower }}{% if router.getActionName() is not '' %}-action-{{ router.getActionName()|lower }}{% endif %}">

<div class="overlay hide"></div>
<div id="top"></div>
<div id="header">
    <div class="navbar" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="{{ url() }}">
                    <img id="logo" src="{{ config.url_static }}img/logo-md.png" alt="JREAM">
                </a>
            </div>
        </div>
    </div>
</div>

{% block content %}{% endblock %}

</body>
</html>
