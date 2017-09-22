{% extends "templates/sidebar.volt" %}

{% block head %}
{% endblock %}


{% block title %}
<span class="title">Tool</span>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url() }}">Home</a></li>
    <li><a href="{{ url('promotion') }}">Tool</a></li>
</ol>

<div class="social-share">
{% include 'templates/partials/addthis.volt' %}
</div>
{% endblock %}

{% block content %}
<div class="row">
    <div class="col-md-12">

        <h3>em to px</h3>
        <p>
        <code>
            font-size: 100%;
            font-size: 16px; // Default
            font-size: 1em;  // 1em = 16px
        </code>
        </p>
        Px:
        <input type="text" class="input" id="calc-px">
        Em:
        <input type="text" class="input" id="calc-em">
        <input type="submit">


        px to %

        (target / context) * 100 = % result
        - Percent is relative to it's parent container, eg:
        - #wrapper of 90% at 960px is kept at 90%, remember the 960px
        - child of #wrapper at 460 we divide: (460 / 960) * 100 = 47.916666667%
        - Keep in mind if you have borders and padding, if you keep them in px just add it to the total of the first number.
    </div>

</div>

<div class="spacer-80"></div>

{% endblock %}



{% block sidebar %}

<div class="col-md-12">

    {% if has_promotion %}
    <h2>Promotion</h2>
    <p>
        The current promotion is offering {{ promotion.percent_off }} for a limited time.
    </p>

    <p>
        This offer ends soon, get it before {{ promotion.description }}.
    </p>
    {% endif %}
</div>


{% endblock %}
