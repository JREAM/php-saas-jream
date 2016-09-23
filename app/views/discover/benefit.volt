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
<h1>Benefits</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url() }}">Home</a></li>
    <li><a href="{{ url('discover') }}">Discover</a></li>
    <li class="active">Benefits</li>
</ol>

<div class="social-share">
{% include 'inc/addthis.volt' %}
</div>
{% endblock %}

{% block content %}

<div class="container container-fluid">
    <div class="row">
        <h2>Benefits You'll Get</h2>
        <p>
        From my own personal life, freelancing, and professional corporate experience &mdash; the benefits of programming are
        almost unbelievable. <span class="highlight">With skills you acquire, only you can apply them.</span>
        I cannot do more than one job as one person, this is why I train people.
        </p>

        <div class="col-md-12">
            <ul class="list-unstyled discover-list">
                <li><h3>Knowledge</h3>
                <li><i class="fa fa-code text-info"></i> With ease, you'll learn the foundations of the most In-Demand programming languages.</li>
                <li><i class="fa fa-pencil text-info"></i> Establish practical experience and knowledge in software development.</li>
                <li><i class="fa fa-university text-info"></i> Move into increasingly advanced topics transforming you into a professional developer.</li>

                <li><h3>Career</h3>
                <li><i class="fa fa-suitcase text-info"></i> Build your own portfolio and Git account to add to your Resume (CV) and LinkedIn.</li>
                <li><i class="fa fa-road text-info"></i> Start a new career in software development, which is one of the most in-demand jobs in the USA.</li>
                <li><i class="fa fa-graduation-cap text-info"></i> Experience &amp; Skills are far more important than any college degree to get a job. (I have no degree)</li>
                <li><i class="fa fa-search text-info"></i> Recruiters will hound you with opportunities once they find out you are a developer.</li>


                <li><h3>Options</h3>
                <li><i class="fa fa-home text-info"></i> Programmers can work remotely, as a freelancer, or create your own company. </li>
                <li><i class="fa fa-money text-info"></i> Based on your person growth, it's not difficult to increase your salary yearly.</li>
            </ul>
        </div>
    </div>
</div>

<div class="spacer-40"></div>

{% include 'inc/section/call-to-action.volt' %}

<div class="spacer-40"></div>

{% endblock %}
