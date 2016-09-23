{% extends "templates/sidebar.volt" %}

{% block title %}
<h1>Live Training</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url() }}">Home</a></li>
    <li class="active">Services</li>
    <li class="active">Live Training</li>
</ol>

<div class="social-share">
{% include 'inc/addthis.volt' %}
</div>
{% endblock %}

{% block intro %}
<div id="live-intro">
    <div class="container container-fluid">
        <div class="row">
            <div class="col-md-8 inner">
                <h1 class="title">Live Training</h1>
                <p>
                    JREAM is offering <b>live training</b> sessions for groups of individuals
                    interested in a specific software topic. These sessions are set for specific dates and
                    time. A live training session will duration ranges between one to two hours.
                </p>
                <p class="pull-right">
                    The host of these sessions is by yours truly, Jesse Boyer.
                </p>
            </div>
        </div>
    </div>
</div>
{% endblock %}

{% block content %}
<div class="row">
    <div class="col-md-11">
        <h1>Development Webinars</h1>
        <p>
            For every live event, there is an outline for the session. If there is
            any course material it is provided ahead of time. These courses can be
            informal, but to get the most out of them it's strongly encouraged that
            you follow along when doing server or code training.
        </p>
        <ul>
            <li><b>Once you reserve a seat, this flow applies:</b></li>
            <li>You will receive an email for any course material, including a unique URL
            to you.</li>
            <li>You will receive an email the day of the event.</li>
            <li>You will receive an email when the event starts.</li>
            <li>Briefly go over the Course Outline.</li>
            <li>Cover informal topics.</li>
            <li>Hands on training and participation.</li>
            <li>Questions &amp; Answers at the end.</li>
        </ul>

        <h1>Interest Survey</h1>
        <p>
            There are many technology topics we can cover. It's important to target
            topics that interest you. If anything specific interests you please fill out
            short form below.
        </p>

        <iframe src="https://docs.google.com/forms/d/1NbkY7UU4NnrQu4Cw3jFJzJtzJoEpb-qrYHibaQPqbyA/viewform?embedded=true" width="760" height="500" frameborder="0" marginheight="0" marginwidth="0">Loading...</iframe>

    </div>
</div>

<div class="spacer-40"></div>

<div class="spacer-20"></div>

{% endblock %}

{% block sidebar %}
<h1>Reserve a Seat</h1>
<div>
<p>
    There are no scheduled events at this time.
    <em>This is currently in a beta testing phase.</em>
</p>
<p>
    If you'd like to be notified when a course is available,
    simply <a href="{{ url('register') }}">create an account</a> if you do not have one. You will receive an email
    when this a non-beta event is available.
</p>
</div>
{% endblock %}