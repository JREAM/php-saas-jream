{% extends "templates/full.volt" %}

{% block title %}
{% endblock %}

{% block intro %}
<div id="main-intro">
    <div class="container container-fluid">
        <div class="row">
            <div class="col-sm-12 inner">
                <div class="col-sm-8">
                    <h1 class="title">Programming Courses</h1>

                    <ul class="list-unstyled">
                        <li><i class="fa fa-check"></i> Sceencasts by a Developer</li>
                        <li><i class="fa fa-check"></i> Learn from 12+ years Experience</li>
                        <li><i class="fa fa-check"></i> Stream Lessons at your own pace</li>
                        <li><i class="fa fa-check"></i> No Recurring Payments, Free Courses too!</li>
                    </ul>

                </div>
                <div class="col-sm-4 vcenter">
                    {% if not session.has('id') %}
                        <div class="register">
                            <a href="{{ url('user/register') }}" class="btn btn-primary btn-xl">Create a Free Account</a>
                            <p class=""><i class="fa fa-user" data-title="JREAM Account" data-toggle="tooltip" data-placement="bottom"></i> <i class="fa fa-facebook-square" data-title="Facebook Account" data-toggle="tooltip" data-placement="bottom"></i> &mdash; Signup in under 15 seconds</p>
                        </div>
                    {% else %}
                    {% endif %}
                </div>
            </div>
        </div>
    </div>
</div>
{% endblock %}

{% block content %}
<div class="spacer-20"></div>

<div class="container container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <h2>Learn from a Developer</h2>
            <p>
            You will learn straight from a developer who's learned everything the hard way and has now made it easy for you.
            JREAM teaches programming skills rapidly through video courses that are straight to the point.
            Ever buy books you never read? Fall asleep reading them? These courses are <b>interactive</b> and <b>fun</b>!
            </p>
        </div>
    </div>
</div>

<hr>

<div class="container container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-offset featured-course text-center">
            <a href="{{ url('product/view/php-punch-in-the-face') }}" class="fadeover">
                <img class="img-responsive img-thumbnail" src="{{ config.url_static }}img/product/php-punch-in-the-face-sm.jpg" alt="PHP Punch in the Face">
            </a>
        </div>
        <div class="col-sm-3 featured-course text-center">
            <a href="{{ url('product/view/php-codeigniter') }}" class="fadeover">
                <img class="img-responsive img-thumbnail" src="{{ config.url_static }}img/product/php-codeigniter-sm.jpg" alt="Learn Codeigniter">
            </a>
        </div>
        <div class="col-sm-3 featured-course text-center">
            <a href="{{ url('product/view/phalcon-php') }}" class="fadeover">
                <img class="img-responsive img-thumbnail" src="{{ config.url_static }}img/product/phalcon-php-sm.jpg" alt="PhalconPHP">
            </a>
        </div>
        <div class="col-md-3 col-md-offset featured-course text-center">
            <a href="{{ url('product/view/python-for-rookies') }}" class="fadeover">
                <img class="img-responsive img-thumbnail" src="{{ config.url_static }}img/product/python-for-rookies-sm.jpg" alt="Python for Rookies">
            </a>
        </div>
    </div>

</div>


<div class="container container-fluid">
    <div class="row">
        <div class="col-sm-12 text-center">
        <h3>
            Want More? <a href="{{ url('product') }}">See All the Courses</a>
        </h3>
        </div>
    </div>
</div>

<hr>

<div class="container container-fluid">

    <div class="spacer-40"></div>

    <div class="row icon-set">
        <div class="col-sm-3 text-center">
            <i class="ico-light ico-lg ico-rounded ico-hover et-shield"></i>
            <h3 class="margin-20-top">Strong Userbase</h3>
            <p>Trusted by over 5,000 users.</p>
        </div>
        <div class="col-sm-3 text-center">
            <i class="margin-20-top ico-light ico-lg ico-rounded ico-hover et-strategy"></i>
            <h3 class="margin-20-top">Strategize</h3>
            <p>Grow by practicing alongside.</p>
        </div>
        <div class="col-sm-3 text-center">
            <i class="margin-20-top ico-light ico-lg ico-rounded ico-hover et-genius"></i>
            <h3 class="margin-20-top">Acquire Skills</h3>
            <p>Move in the correct programming direction.</p>
        </div>
        <div class="col-sm-3 text-center">
            <i class="margin-20-top ico-light ico-lg ico-rounded ico-hover et-trophy"></i>
            <h3 class="margin-20-top">Rewarding</h3>
            <p>Use your new skills for any opportunity or dream!</p>
        </div>
    </div>
</div>

<div class="spacer-40"></div>

<hr>

<div class="spacer-40"></div>

{% endblock %}

