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
<h1>Discover</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url() }}">Home</a></li>
    <li class="active">Discover</li>
</ol>

<div class="social-share">
{% include 'inc/addthis.volt' %}
</div>
{% endblock %}

{% block content %}
<div class="container container-fluid">
    <div class="row">
        <h2>Discover Programming</h2>
        <p>
            Courses at JREAM are developed for the most widely used technology today. You have to opportunity
            to take to discover your untapped skill. Let JREAM help you nurture that skill with applicable
            lessons. <span class="highlight">Below are some topics you will discover!</span>
        </p>
    </div>
    <div class="row">
        <div class="col-md-6">

            <ul class="list-unstyled discover-list">
                <li><h3>FrontEnd</h3></li>
                <li><span class="text-info devicons devicons-html5"></span> <b>HTML5</b> - The Standard for website structure.</li>
                <li><span class="text-info devicons devicons-css3"></span> <b>CSS3</b> - The Standard for website appearance.</li>
                <li><span class="text-info devicons devicons-javascript_badge"></span> <b>Javascript</b> - The Standard for website interactivity.</li>
                <li><span class="text-info devicons devicons-jquery"></span> <b>jQuery</b> - The #1 JavaScript Wrapper which makes JS easier.</li>

                <li><h3>System-Wide</h3></li>
                <li><span class="text-info devicons devicons-python"></span> <b>Python</b> - The easiest to learn cross-platform language.</li>
                <li><span class="text-info devicons devicons-terminal"></span> <b>Terminals</b> - For Server Administration, SSH, Git, etc.</li>
            </ul>

        </div>
        <div class="col-md-6">
            <ul class="list-unstyled discover-list">
                <li><h3>Back-End Programming</h3></li>
                <li><span class="text-info devicons devicons-php"></span> <b>PHP</b> - The Most widely used web language on the internet.</li>
                <li><span class="text-info devicons devicons-codeigniter"></span> <b>CodeIgniter</b> - Popular &amp; the easiest MVC framework to begin with.</li>
                <li><span class="text-info devicons devicons-opensource"></span> <b>PhalconPHP</b> - Fastest &amp; Powerful, an advanced MVC framework.</li>
            </ul>

            <ul class="list-unstyled discover-list">
                <li><h3>Databases</h3></li>
                <li><span class="text-info devicons devicons-mysql"></span> <b>MySQL</b> - #1 used Database Engine for permanent storage.</li>
                <li><span class="text-info devicons devicons-redis"></span> <b>Redis</b> - Fastest and the best in-memory database.</li>
            </ul>
        </div>
    </div>
</div>

<div class="spacer-40"></div>

{% include 'inc/section/call-to-action.volt' %}

<div class="spacer-40"></div>

{% endblock %}
