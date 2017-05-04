{% extends "templates/full.volt" %}

{% block head %}
{% endblock %}

{% block title %}
    <h1>{{ product.title }}</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url('dashboard') }}">Dashboard</a></li>
    <li class="active">{{ product.title }}</li>
</ol>
{% endblock %}

{% block content %}
<div class="container container-fluid">

<div class="row">
    <div class="col-md-8">
        <h1>Streaming Content</h1>
    </div>
</div>
<div class="row">
    <div class="col-md-8">

        <?php
        $set = [];
        ?>
        {% for course in courses %}

            <?php if (!in_array($course->section, $set)):?>

                <?php if (!empty($set)):?>
                    </div>
                <?php endif;?>

                <div class="list-group">
                <div class="list-group-item active">
                    <h4><i class="fa fa-folder-open opacity-50" aria-hidden="true"></i> Section {{ course.section }}</h4>
                </div>
            <?php endif;?>

            <?php
            $hasComplete = $userAction->getAction('hasCompleted', $this->session->get('id'), $course->id);
            if ($hasComplete) {
                $hasComplete = $hasComplete->value;
            } else {
                $hasComplete = 0;
            }
            ?>

            <?php $name = str_replace('-', ' ', $course->name)?>
            <div class="list-group-item <?php if ($hasComplete): ?>course-complete<?php endif;?>">
                <span class="glyphicon glyphicon-film opacity-50"></span>
                <a class="course-list" href="{{ url('dashboard/course/view/') }}{{ product.id }}/{{ course.id }}"><?=ucwords($name)?></a>
                <div class="pull-right">

                    <a {% if !hasComplete %}style="display:none;"{% endif %} data-value="0" data-content-id="{{ course.id }}" id="btn-course-complete-{{ course.id }}" class="course-mark course-action label btn-mini btn-success" href="{{ url('dashboard/course/action') }}"><span class="glyphicon glyphicon-ok"></span> <span class="action-text">Completed</span></a>
                    <a {% if hasComplete %}style="display:none;"{% endif %} data-value="1" data-content-id="{{ course.id }}" id="btn-course-mark-complete-{{ course.id }}" class="course-unmark course-action text-muted" href="{{ url('dashboard/course/action') }}"><span class="glyphicon glyphicon-ok-sign"></span> <span class="action-text">Mark Complete</span></a>

                </div>
            </div>

            <?php
            $set[] = $course->section;
            ?>

        {% endfor %}
        </div><!-- The closing DIV -->
    </div>

    <div class="col-md-4">

        <div style="margin-bottom: 15px">
            <img class="fadeover img-thumbnail img-responsive" src="{{ product.img_sm }}" alt="{{ product.path }}" />
        </div>

        <div class="expandable">
            {{ product.description }}
        </div>

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h1 class="panel-title"><i class="fa fa-code opacity-50" aria-hidden="true"></i> Source Code</h1>
            </div>
            <div class="panel-body">
                <a href="https://github.com/jream-network">
                    <i class="fa fa-github"></i>
                </a>
                <a href="https://github.com/jream-network">JREAM Network</a> 
            </div>
        </div>


        <div>
            <a class="btn btn-lg btn-block btn-primary" href="{{ url('dashboard/question/index') }}/{{ product.id }}">Ask A Question</a>
        </div>

        <br />

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h1 class="panel-title"><i class="fa fa-line-chart opacity-50" aria-hidden="true"></i> Course Progress</h1>
            </div>
            <div class="panel-body">
                <p>
                You are <strong><?=round($percent)?>%</strong> Complete.
                </p>
                <div class="progress" style="margin-top: 5px">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?=$percent?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$percent?>%"></div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
$(function() {

    $('.course-action').click(function(e) {
        e.preventDefault();

        var url = $(this).attr('href');
        var contentId = $(this).data('content-id');
        var postData = {
            'productId': '{{ product.id }}',
            'contentId': contentId,
            'value': $(this).data('value'),
            'action': 'hasCompleted',
            '{{ tokenKey }}': '{{ token }}'
        };

        var self = $(this);

        $.post(url, postData, function(obj) {
            if (obj.result == 1) {
                if (obj.data.value == 1) {
                    self.parents('.list-group-item').addClass('course-complete');
                    $("#btn-course-complete-" + contentId).show();
                    $("#btn-course-mark-complete-" + contentId).hide();
                } else {
                    self.parents('.list-group-item').removeClass('course-complete');
                    $("#btn-course-complete-" + contentId).hide();
                    $("#btn-course-mark-complete-" + contentId).show();
                }
            } else {
                console.log('error');
            }
        }, 'json');
    });

});
</script>
</div>
{% endblock %}
