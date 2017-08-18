{% extends "templates/full.volt" %}

{% block title %}
<span class="title">Newsletter</span>
{% endblock %}

{% block hero %}
<div id="hero">
    <div class="container container-fluid">
        <div class="row">
            <div class="col-xs-12 inner">
            </div>
        </div>
    </div>
</div>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url() }}">Home</a></li>
    <li class="active">Newsletter</li>
</ol>
{% endblock %}

{% block content %}
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Newsletter</h1>
            <p>
            {{ result }}
            </p>

            <form class="form-login" id="newsletter-subscribe-form" method="post" action="{{ url('newsletter/dosubscribe') }}">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                        {{ form.render('email') }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {% if constant('STAGE') != 'local' %}
                            <div class="g-recaptcha" data-sitekey="6LfHCAYTAAAAALb7zfhNEaWLklfHO-MMoIjsKlHV"></div>
                            {% else %}
                            <b>Not Showing Captcha, in LOCAL mode.</b>
                            {% endif %}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ form.render('submit') }}
                            <input type="hidden" name="{{ tokenKey }}" value="{{ token }}" />
                        </div>
                    </div>
                </div>
            </form>

         </div>
     </div>
 </div>
{% endblock %}


{% block script %}
<script>
var url_recaptcha = "{{ url('apilegacy/recaptcha') }}";
var url_action = "{{ url('newsletter/dosubscribe') }}";

// So sloppy but gets it done.
// Need that event listener trigger in JS forgot how to do it
$(function() {
    $("#newsletter-subscribe-form").submit(function(evt) {

        var postData = $(this).serialize();
        var submit_btn = $(this).find("input[type=submit]");

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

            $.post(url_action, postData, function(obj) {
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

            }, 'json');
        }, 'json');
    });
});
</script>
{% endblock %}


