{% extends "templates/sidebar.volt" %}

{% block title %}
<h1>Development Services</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url() }}">Home</a></li>
    <li class="active">Services</li>
    <li class="active">Development Services</li>
</ol>

<div class="social-share">
{% include 'inc/addthis.volt' %}
</div>
{% endblock %}

{% block content %}
<div class="row">
    <div class="col-md-11">
        <h1>Development Services</h1>
        <p>
            JREAM typically works solo yet has a small local team when needed.
            This is for complete and advanced development rather than plug-and-play
            pre-fabricated systems.
        </p>
        <ul class="list-indent">
            <li><b>Complete Projects</b>
                <ul>
                    <li>Django Development (<b>Recommended</b>)</li>
                    <li>PHP Development</li>
                </ul>
            </li>
            <li><b>DevOps</b>
                <ul>
                    <li>Server Configuration(s) (Included in Projects)</li>
                    <li>Amazon Web Services (AWS)</li>
                    <li>Deployments</li>
                    <li>Database Design</li>
                </ul>
            </li>
            <li><b>Optional</b>
                <ul>
                    <li>Unit Testing and Code Coverage (Python/PHP)</li>
                    <li>Front-End Development / Build Tools (NodeJS + Gulp)</li>
                    <li>Deployment Tools: Flask, Git Hook, CI, etc.</li>
                </ul>
            </li>
        </ul>

        <h1>Recommended Service</h1>
        <p>
            The most beneficial development for clients is a Django Project due it's simplicity,
            speed of development, and comprehensive libraries.
        </p>
        <p>
            What does this mean to you? It simply means that you will end up with the
            same end result only with a different framework.
        </p>

        <h1>Pricing</h1>
        <p>
            Projects are estimated based on the complexity and total amount of time needed.
            A typical project ranges from $2000 - $5000. Cost can go up if the timeframe is
            shortened, complexity, and/or features needed increase.
        </p>

        <h1>Project Management</h1>
        <p>
            You will be setup with a project management questionnaire with all aspects
            asking you what you'd like to accomplish. There will be a back-end login which will
            handle everything, you don't need to worry about paper work. We move from that point to settle on
            and agreement and timeframe.
        </p>
        <p>
            Every project works in milestones. The larger the project the more milestones. A milestone
            is where JREAM delivers the agreed upon completed work, and a payment is made. This keeps cost
            down and confindence.
        </p>

        <h1>Communication</h1>
        <p>
            The preferred way of communication is through Skype. This allows
            us to share screen easily and keep in contact. Other options include Google Hangouts,
            Appear.In, The Project Manager, Phone, and Email.
        </p>

        <p>
            If you are interested in consultation services contact <span class="highlight">hello@jream.com</span> with your inquiry.
            Availability is subject to calendar date/times based on EST time.
        </p>

    </div>
</div>

<div class="spacer-40"></div>

{% endblock %}

{% block sidebar %}
<h1>Contact</h1>
<div>
    <p>
        If you are interested or have questions on a project, please
        provide any necessary information below.
    </p>

    <div id="form-errors" class="alert alert-danger hidden">
    </div>

    <form id="contact-form" method="post" action="{{ url('api/contact?type=services/consulting') }}">
        <div class="form-group">
            {{ form.render('name') }}
        </div>

        <div class="form-group">
            {{ form.render('email') }}
        </div>

        <div class="form-group">
            {{ form.render('message', ["rows": 15]) }}
        </div>
        <div class="form-group">
            {% if constant('STAGE') != 'local' %}
            <div class="g-recaptcha" data-sitekey="6LfHCAYTAAAAALb7zfhNEaWLklfHO-MMoIjsKlHV"></div>
            {% else %}
            <b>Not Showing Captcha, in LOCAL mode.</b>
            {% endif %}
        </div>

        <div class="form-group">
            <input type="hidden" name="{{ tokenKey }}" value="{{ token }}" />
            {{ form.render('submit') }}
        </div>
    </form>

</div>

<script>
var url_recaptcha = "{{ url('api/recaptcha') }}";
var url_contact = "{{ url('api/contact') }}";
var url_redirect = "{{ url('contact/thanks') }}";

// So sloppy but gets it done.
// Need that event listener trigger in JS forgot how to do it
$(function() {
    $("#contact-form").submit(function(evt) {
        var postData = $(this).serialize();

        submit_btn = $(this).find("input[type=submit]");

        evt.preventDefault();
        $("#form-errors").html();
        $("#form-errors").addClass('hidden');

        $.post(url_recaptcha, postData, function(obj) {
            submit_btn.attr('disabled', 'disabled');
            if (obj.result == 0) {
                $("#form-errors").html(obj.error).removeClass('hidden');
                submit_btn.removeAttr('disabled');
                return;
            }

            $.post(url_contact, postData, function(obj) {
                if (obj.result == 0)
                {
                    // string
                    if (typeof obj.error != 'object') {
                        $("#form-errors").html(obj.error).removeClass('hidden');
                        submit_btn.removeAttr('disabled');
                        return;
                    }

                    // list
                    elems = '<ul>';
                    for (var i = 0; i < obj.error.length; i++) {
                        elems += '<li>' + obj.error[i] + '</li>';
                    }
                    elems += '</ul>';
                    $("#form-errors").html(elems).removeClass('hidden');
                    submit_btn.removeAttr('disabled');
                    return;
                }

                $(location).attr('href', url_redirect);

            }, 'json');
        }, 'json');
    });
});
</script>
{% endblock %}
