{% extends "templates/full.volt" %}

{% block head %}
    <style>
   .flowplayer {
        background: url('{{ config.url_static }}img/video-overlay/{{ productCourse.section }}.{{ productCourse.course }}.gif') no-repeat;
        background-size: cover;
    }
    </style>
{% endblock %}

{% block title %}
    <h1>{{ courseName }}</h1>
{% endblock %}

{% block breadcrumb %}
<div class="container container-fluid">
<div class="col-md-6">
    <ol class="breadcrumb">
        <li><a href="{{ url('dashboard') }}">Dashboard</a></li>
        <li><a href="{{ url('dashboard/course/index') }}/{{ productId }}">{{ productName }}</a></li>
        <li class="active">{{ courseName }}</li>
    </ol>
</div>
<div class="col-md-6 text-right pull-right">
    {% if prev %}
        <a class="btn" href="{{ url('dashboard/course/view') }}/{{ productId }}/{{ prev.id }}"><span class="glyphicon glyphicon-chevron-left"></span> {{ prev.name }}</a>
    {% endif %}
    {% if next %}
        <a class="btn" href="{{ url('dashboard/course/view') }}/{{ productId }}/{{ next.id }}">{{ next.name }} <span class="glyphicon glyphicon-chevron-right"></span></a>
    {% endif %}
</div>
{% endblock %}

{% block content %}
<div class="row above-video-buttons">
    <div class="col-md-4">
        <a data-value="0" class="course-mark course-action margin-bottom btn btn-large btn-success" href="{{ url('dashboard/course/action') }}"><span class="glyphicon glyphicon-ok"></span> Completed</a>
        <a data-value="1" class="course-unmark course-action margin-bottom btn btn-large" href="{{ url('dashboard/course/action') }}"><span class="glyphicon glyphicon-ok-sign"></span> Mark Complete</a>
        <a class="margin-bottom btn btn-info toggle-lights">Toggle Lights</a>
    </div>
    <div class="col-md-2">
        <div class="btn-group flowplayer-option-btns">

        </div>
    </div>

</div>


<div class="row">
    <div class="col-md-12">
        {% include "inc/section/flowplayer.volt" %}
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading"><i class="fa fa-align-justify opacity-50" aria-hidden="true"></i> Description</div>
            <div class="panel-body">
            {{ courseDescription }}
            </div>
        </div>

        {% if productCourse.getProductCourseMeta()|length !== 0 %}
        <div class="panel panel-primary">
            <div class="panel-heading">Resources</div>
            <div class="panel-body">
            {% for meta in productCourse.getProductCourseMeta() %}
                <div>
                {{ component.helper.getMetaIcon(meta.type) }}
                {% if meta.type == 'link' %}
                    <a target="_blank" href="{{ meta.resource }}">{{ meta.resource }}</a>
                {% else %}
                    {{ meta.resource }}
                {% endif %}
                {{ meta.content }}
                </div>
            {% endfor %}
            </div>
        </div>
        {% endif %}
    </div>
</div>

<script>
$(function() {


{% if hasCompleted %}
    $(".course-unmark").hide();
{% else %}
    $(".course-mark").hide();
{% endif %}

    $('.course-action').click(function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var postData = {
            'productId': '{{ productId }}',
            'contentId': '{{ contentId }}',
            'value': $(this).data('value'),
            'action': 'hasCompleted',
            '{{ tokenKey }}': '{{ token }}'
        };
        $.post(url, postData, function(obj) {
            if (obj.result == 1) {
                if (obj.data.value == 1) {
                    $(".course-unmark").hide();
                    $(".course-mark").show();
                } else {
                    $(".course-unmark").show();
                    $(".course-mark").hide();
                }
            } else {
                console.log('error');
            }
        }, 'json');
    })
});

var active = false;
$(function() {

    /**
     * Lights Off
     */
    $(".toggle-lights").click(function(evt) {
        evt.preventDefault();
        var overlay = $('.overlay');

        if (overlay.hasClass('hide')) {
            $(".overlay").removeClass('hide');
        } else {
            $(".overlay").addClass('hide');
        }
    });

});
</script>
</div>
{% endblock %}