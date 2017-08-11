{% extends "templates/full.volt" %}

{% block title %}
<span class="title">DevTools</span>
{% endblock %}

{% block hero %}
<div id="hero">
    <div class="container container-fluid">
        <div class="row">
            <div class="col-xs-12 inner">
            </div>
        </div>
    </div>
</div>
{% endblock %}

{% block content %}
<div class="container container-fluid">
    <div class="row">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs dashboard-tabs">
            <li class="active"><a href="{{ url('devtools') }}">Dev Tools</a></li>
            <li><a href="{{ url('devtools/encode') }}">Encode</a></li>
            <li><a href="{{ url('devtools/encrypt') }}">Encrypt</a></li>
            <li><a href="{{ url('devtools/strings') }}">Strings</a></li>
            <li><a href="{{ url('devtools/fakedata') }}">Fake Data</a></li>
            <li><a href="{{ url('devtools/utf8chars') }}">UTF8 Chars</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <p>
            Text text text text text text text text text text text text
            Text text text text text text text text text text text text
            </p>
            <p>
            Text text text text text text text text text text text text
            Text text text text text text text text text text text text
            </p>
            <p>
            Text text text text text text text text text text text text
            Text text text text text text text text text text text text
            </p>

        </div>
    </div>

</div>
{% endblock %}
