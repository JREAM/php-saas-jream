{% extends "templates/full.volt" %}

{% block title %}
<span class="title">Questions</span>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url('dashboard') }}">Dashboard</a></li>
    <li><a href="{{ url('dashboard/course/index/') }}/{{ product.id }}">{{ product.title }}</a></li>
    <li class="active">Questions</li>
</ol>
{% endblock %}

{% block content %}
<div class="container container-fluid">
<div class="row">
    <div class="col-md-8">
    <h2>Questions</h2>
    {% if threads|length == 0 %}
    <p>
        It looks like nobody has gotten stuck yet!
    </p>
    <p>
    You can be the first to ask a question!
    </p>
    {% else %}
        {% for thread in threads %}
            <div class="panel panel-primary" id="thread-id-{{ thread.id }}">
                <div class="panel-heading">
                    <h1 class="panel-title">{{ thread.title }}</h1>
                </div>
                <div class="panel-body">
                <?php
                    $user = $thread->getUser();
                    $userId = $user->id;
                    $icon = $user->getIcon($userId, 20);
                    $alias = $user->getAlias();
                ?>
                <div class="thread-item">
                    <?=$icon?> <strong><?=$alias?></strong>
                    <div>{{ thread.getOffset('created_at') }}</div>
                    <div>
                    {{ thread.markdown('content') }}
                    </div>
                </div>
                <hr />
                <div class="row reply-list">
                {% for reply in thread.getProductThreadReply() %}
                    <?php
                        $user = $reply->getUser();
                        $userId = $user->id;
                        $icon = $user->getIcon($userId, 20);
                        $alias = $user->getAlias($userId);
                    ?>
                    <div class="reply-list-item">
                        <?=$icon?> <strong><?=$alias?></strong>
                        <div>{{ reply.getOffset('created_at') }}</div>
                        <?php if ($this->session->get('id') == $userId):?>
                            <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> -->
                        <?php endif?>

                        <div>
                            {{ reply.markdown('content') }}
                        </div>
                    </div>
                {% endfor %}
                </div>
                <div class="reply-container">
                    <a class="reply-btn btn btn-xs btn-info" href="#">Reply</a>
                    <a class="cancel-btn hide btn btn-xs btn-info" href="#">Cancel</a>
                    <form class="hide reply-form" method="post" action="{{ url('api/question/reply') }}/{{ product.id }}/{{ thread.id }}">
                        <div class="form-group">
                            <textarea name="content" class="autosize form-control" placeholder="Your Message" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <input class="btn btn-lg btn-primary btn-block" type="submit" value="Submit">
                        </div>

                       <input class="csrf-field" type="hidden" name="{{ tokenKey }}" value="{{ token }}" />

                    </form>
                </div>
            </div>
        </div>
        {% endfor %}
    {% endif %}
    </div>

    <div class="col-md-4">
        <h2>Ask</h2>
        <div id="ask-error"></div>
        <form id="ask" method="post" action="{{ url('api/question/create') }}/{{ product.id }}">
            <div class="form-group">
                <input type="text" name="title" class="form-control input-lg" value="<?=formData('title')?>" placeholder="Title">
            </div>
            <div class="form-group">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#question" data-type="question" data-toggle="tab">Question</a></li>
                    <li><a href="#preview" data-type="preview" data-toggle="tab">Preview</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="question">
                        <textarea name="content" class="form-control" rows="12" placeholder="Markdown Tips: Use `ticks` for code, use ```three ticks for multiline code```, **for bold**, [Link Text](http://url) etc."><?=formData('content')?></textarea>
                    </div>
                    <div class="tab-pane fade" id="preview">
                        <div class="question-preview"></div>
                    </div>
                </div>
                <small>Use Markdown Syntax to format code</small>
            </div>
            <div class="form-group">
                <input class="btn btn-lg btn-primary btn-block" type="submit" value="Submit">
            </div>

            <input class="csrf-field" type="hidden" name="{{ tokenKey }}" value="{{ token }}" />

        </form>
    </div>
</div>
</div>


<div class="spacer-80"></div>

{% endblock %}

{% block script %}

{#
###########################
    @TODO Move JS to File
###########################
#}

<script>
$(function() {

    $(".reply-btn").click(function(evt) {
        evt.preventDefault();
        $(this).addClass('hide');
        var parent = $(this).parent('.reply-container');
        parent.find('.cancel-btn').removeClass('hide');
        parent.find('.reply-form').removeClass('hide');
    });

    $(".cancel-btn").click(function(evt) {
        evt.preventDefault();
        $(this).addClass('hide');
        var parent = $(this).parent('.reply-container');
        parent.find('.reply-btn').removeClass('hide');
        parent.find('.reply-form').addClass('hide');
    });

    $("#ask").submit(function(evt) {
        evt.preventDefault();

        var submitBtn = $("input[type=submit]", this);
        submitBtn.prop('disabled', true);

        var postData = $(this).serialize();
        var content = $("textarea[name=content]", this).val();
        postData += '&content='+content;
        var url = $(this).attr('action');

        $.post(url, postData, function(obj) {
            if (obj.result == 1) {
                window.location.href = obj.data.redirect;
            } else {
                $("#ask-error").html('<div class="alert alert-danger">'+obj.error+'</div>');
            }
            submitBtn.prop('disabled', false);
        }, 'json');
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var type = $(e.target).data('type');
        if (type != 'preview') return;

        var postData = {
            content: $("#ask textarea[name=content]").val()
        };

        $.post("{{ url('api/utils/markdown') }}", postData, function(obj) {
            $('.question-preview').html(obj.data)
        }, 'json');
    });

});
</script>
{% endblock %}
