{% extends "templates/full.volt" %}

{% block intro %}
<div id="discover-intro">
    <div class="container container-fluid">
        <div class="row">
            <div class="col-md-12 inner">
                {% include 'inc/section/discover-steps.volt' %}
            </div>
        </div>
    </div>
</div>
{% endblock %}

{% block title %}
<h1>Watch &amp; Learn</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url() }}">Home</a></li>
    <li><a href="{{ url('discover') }}">Discover</a></li>
    <li class="active">Watch &amp; Learn</li>
</ol>

<div class="social-share">
{% include 'inc/addthis.volt' %}
</div>
{% endblock %}


{% block content %}
<div class="container container-fluid">
<div class="row">
    <div class="col-md-12">
        <h1>Watch and Learn</h1>
        <p>
            There's no better way to absorb a skill than to watch an expert show you
            how it's done. It's proven in sports, the arts, and music. The same can be
            said about learning computer proper software development
        </p>

            <ul class="list-unstyled discover-list">
                <li><h2>The Process</h2></li>
                <li><span class="text-info fa fa-play"></span> <b>Watch</b> the series at your own pace.</li>
                <li><span class="text-info fa fa-code"></span> <b>Code</b> along with the courses.</li>
                <li><span class="text-info fa fa-check"></span> <b>Complete</b> the course by marking it off.</li>

                <li><h3>Stuck?</h3></li>
                <li><span class="text-info fa fa-backward"></span> <b>Pause/Replay</b> as needed.</li>
                <li><span class="text-info fa fa-bitbucket"></span> <b>Get the Code</b> from the courses repository.</li>
                <li><span class="text-info fa fa-comments"></span> <b>Ask</b> a question in the course.</li>

                <li><h3>Finish Line</h3></li>
                <li><span class="text-info fa fa-trophy"></span> <b>Congrats!</b> You've acquired and practiced valuable new skills.</li>
                <li><span class="text-info fa fa-heart"></span> <b>Dream Big</b> and build anything from your imagination.</li>
            </ul>


        </div>
    </div>
</div>

{% include 'inc/section/call-to-action.volt' %}

<div class="spacer-40"></div>

{% endblock %}
