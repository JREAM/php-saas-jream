{% extends "templates/sidebar.volt" %}

{% block title %}
<h1>Lab</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url() }}">Home</a></li>
    <li class="active">Lab</li>
</ol>

<div class="social-share">
{% include 'inc/addthis.volt' %}
</div>
{% endblock %}

{% block content %}
<div class="row">
    <div class="col-md-4">
        <img src="{{ url('img/ico/256/lab.png') }}" class="pull-right img-responsive home-intro-icon">
    </div>
    <div class="col-md-8">
        <h1>What is the Lab?</h1>
        <p>
            A storage facility containing remnants of code written for your convenience.
            All other open-source code can be found at
            <a href="https://bitbucket.org/JREAM" target="_blank">BitBucket</a>.
        </p>
        <p>
            The code below is not maintained, it was done as learning examples.
            If you have any issues with the code you must solve your own bugs.
        </p>
    </div>
</div>

<div class="spacer-40"></div>

<h2>Create your own MVC</h2>
<p>
    The MVC series was an introduction for writing your own Model, View, Controller in PHP.
    It was to teach you to use a re-usable code structure. The goal of this series was to exercise
    ones mind to think in an object oriented fashion.
    Please realize this is not a real project, rather, it is a barebones application for learning.
</p>

<div class="spacer-20"></div>

<ul class="list-group">
    <li class="list-group-item">PHP: MVC Tutorial Part 1
        <span class="pull-right">
            <a class="btn btn-xs btn-primary" href="{{ config.url_static }}lab/mvc/mvc.tutorial.part.1.zip"><span class="glyphicon glyphicon-download"></span> Download</a>
            <a class="btn btn-xs btn-primary" href="http://www.youtube.com/watch?v=Aw28-krO7ZM"><span class="glyphicon glyphicon-film"></span> Video</a>
        </span>
    </li>
    <li class="list-group-item">PHP: MVC Tutorial Part 2
        <span class="pull-right">
            <a class="btn btn-xs btn-primary" href="{{ config.url_static }}lab/mvc/mvc.tutorial.part.2.zip"><span class="glyphicon glyphicon-download"></span> Download</a>
            <a class="btn btn-xs btn-primary" href="http://www.youtube.com/watch?v=bQxvYs9yO7Y"><span class="glyphicon glyphicon-film"></span> Video</a>
        </span>
    </li>
    <li class="list-group-item">PHP: MVC Tutorial Part 3
        <span class="pull-right">
            <a class="btn btn-xs btn-primary" href="{{ config.url_static }}lab/mvc/mvc.tutorial.part.3.zip"><span class="glyphicon glyphicon-download"></span> Download</a>
            <a class="btn btn-xs btn-primary" href="http://www.youtube.com/watch?v=2Eu0Nkpo6vM"><span class="glyphicon glyphicon-film"></span> Video</a>
        </span>
    </li>
    <li class="list-group-item">PHP: MVC Tutorial Part 4
        <span class="pull-right">
            <a class="btn btn-xs btn-primary" href="{{ config.url_static }}lab/mvc/mvc.tutorial.part.4.zip"><span class="glyphicon glyphicon-download"></span> Download</a>
            <a class="btn btn-xs btn-primary" href="http://www.youtube.com/watch?v=4hh2IXrdT4g"><span class="glyphicon glyphicon-film"></span> Video</a>
        </span>
    </li>
    <li class="list-group-item">PHP: MVC Tutorial Part 5
        <span class="pull-right">
            <a class="btn btn-xs btn-primary" href="{{ config.url_static }}lab/mvc/mvc.tutorial.part.5.zip"><span class="glyphicon glyphicon-download"></span> Download</a>
            <a class="btn btn-xs btn-primary" href="http://youtu.be/4gDLBMs_9ng"><span class="glyphicon glyphicon-film"></span> Video</a>
        </span>
    </li>
    <li class="list-group-item">PHP: MVC Tutorial Part 6
        <span class="pull-right">
            <a class="btn btn-xs btn-primary" href="{{ config.url_static }}lab/mvc/mvc.tutorial.part.6.zip"><span class="glyphicon glyphicon-download"></span> Download</a>
            <a class="btn btn-xs btn-primary" href="http://youtu.be/JmPgJXS7uxA"><span class="glyphicon glyphicon-film"></span> Video</a>
        </span>
    </li>
    <li class="list-group-item">PHP: MVC Tutorial Part 7
        <span class="pull-right">
            <a class="btn btn-xs btn-primary" href="{{ config.url_static }}lab/mvc/mvc.tutorial.part.7.zip"><span class="glyphicon glyphicon-download"></span> Download</a>
            <a class="btn btn-xs btn-primary" href="http://youtu.be/Pz3Oj_fYMn8"><span class="glyphicon glyphicon-film"></span> Video</a>
        </span>
    </li>
    <li class="list-group-item">PHP: MVC Tutorial Part 8
        <span class="pull-right">
            <a class="btn btn-xs btn-primary" href="{{ config.url_static }}lab/mvc/mvc.tutorial.part.8.zip"><span class="glyphicon glyphicon-download"></span> Download</a>
            <a class="btn btn-xs btn-primary" href="http://www.youtube.com/watch?v=2XZ4c6QoW2I"><span class="glyphicon glyphicon-film"></span> Video</a>
        </span>
    </li>
    <li class="list-group-item">PHP: MVC Tutorial Part 9
        <span class="pull-right">
            <a class="btn btn-xs btn-primary" href="{{ config.url_static }}lab/mvc/mvc.tutorial.part.9.zip"><span class="glyphicon glyphicon-download"></span> Download</a>
            <a class="btn btn-xs btn-primary" href="http://www.youtube.com/watch?v=7-_MaymNHMM"><span class="glyphicon glyphicon-film"></span> Video</a>
        </span>
    </li>
    <li class="list-group-item">PHP: MVC Tutorial Part 10
        <span class="pull-right">
            <a class="btn btn-xs btn-primary" href="{{ config.url_static }}lab/mvc/mvc.tutorial.part.10.zip"><span class="glyphicon glyphicon-download"></span> Download</a>
            <a class="btn btn-xs btn-primary" href="http://youtu.be/mtSxs-wT4Q4"><span class="glyphicon glyphicon-film"></span> Video</a>
        </span>
    </li>
    <li class="list-group-item">PHP: MVC Tutorial Part 11
        <span class="pull-right">
            <a class="btn btn-xs btn-primary" href="{{ config.url_static }}lab/mvc/mvc.tutorial.part.11.zip"><span class="glyphicon glyphicon-download"></span> Download</a>
            <a class="btn btn-xs btn-primary" href="http://youtu.be/Z3-c82u5vP4"><span class="glyphicon glyphicon-film"></span> Video</a>
        </span>
    </li>
</ul>

<div class="spacer-40"></div>

<h2>Game Framework</h2>
<p>
    The Game Framework is an exercise using a few Design Patterns. This is intended
    for beginners. This is not meant to create an actual game. Instead,
    it's was to teach you to think in objects rather than procedural programming.
</p>

<div class="spacer-20"></div>

<ul class="list-group">
    <li class="list-group-item">PHP: Game Framework Tutorial Part 1
        <span class="pull-right">
            <a class="btn btn-xs btn-primary" href="{{ config.url_static }}lab/game/game.tutorial.part.1.zip"><span class="glyphicon glyphicon-download"></span> Download</a>
            <a class="btn btn-xs btn-primary" href="http://www.youtube.com/watch?v=YzmTcqAjh-U"><span class="glyphicon glyphicon-film"></span> Video</a>
        </span>
    </li>
    <li class="list-group-item">PHP: Game Framework Tutorial Part 2
        <span class="pull-right">
            <a class="btn btn-xs btn-primary" href="{{ config.url_static }}lab/game/game.tutorial.part.2.zip"><span class="glyphicon glyphicon-download"></span> Download</a>
            <a class="btn btn-xs btn-primary" href="http://www.youtube.com/watch?v=EtoEaPMuMZs"><span class="glyphicon glyphicon-film"></span> Video</a>
        </span>
    </li>
    <li class="list-group-item">PHP: Game Framework Tutorial Part 3
        <span class="pull-right">
            <a class="btn btn-xs btn-primary" href="{{ config.url_static }}lab/game/game.tutorial.part.3.zip"><span class="glyphicon glyphicon-download"></span> Download</a>
            <a class="btn btn-xs btn-primary" href="http://www.youtube.com/watch?v=rGvfqM75trU"><span class="glyphicon glyphicon-film"></span> Video</a>
        </span>
    </li>
</ul>
{% endblock %}

{% block sidebar %}
<h1>Wild Things</h1>
<div>
    <a href="http://www.youtube.com/user/JREAMdesign" target="_blank">
    <img alt="Youtube" src="{{ config.url_static }}img/social/youtube.png" /></a>
    <a href="http://www.youtube.com/user/JREAMdesign" target="_blank">YouTube Channel</a>
</div>
<div>
    <a href="https://bitbucket.org/JREAM" target="_blank">
    <img alt="Bitbucket" src="{{ config.url_static }}img/social/bitbucket.png" /></a>
    <a href="https://bitbucket.org/JREAM" target="_blank">BitBucket Repository</a>
</div>
{% endblock %}