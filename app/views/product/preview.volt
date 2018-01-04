{% extends "templates/full.volt" %}

{% block head %}
    <style>
   .flowplayer {
        background: url('{{ config.url_static }}img/video-overlay/{{ productCourse.section }}.{{ productCourse.course }}.gif');
    }
    </style>
{% endblock %}

{% block title %}
<span class="title">Preview: {{ courseName }}</span>
{% endblock %}


{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url() }}">Home</a></li>
    <li><a href="{{ url('product') }}">Products</a></li>
    <li><a href="{{ url('product/course') }}/{{ product.slug }}">{{ product.title }}</a></li>
    <li class="active">{{ courseName }}</li>
</ol>
{% endblock %}

{% block content %}
<div class="container container-fluid">
<div class="row">

{% if error %}
    <div class="row">
        <div class="col-md-12 panel">
            {{ error }}
        </div>
    </div>
{% else %}
    <div class="row">
        <div class="col-md-12">
            {% include "partials/flowplayer.volt" %}
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">Description</div>
                <div class="panel-body">
                {{ productCourse.description }}
                </div>
            </div>

            {% if productCourse.getProductCourseMeta()|length !== 0 %}
                <small>* Resources are not Available for Previews</small>
            {% endif %}
        </div>
    </div>
{% endif %}

</div>
</div>

<div class="spacer-80"></div>
{% endblock %}
