{% extends "templates/full.volt" %}

{% block title %}
    <h1>DevTools</h1>
{% endblock %}

{% block breadcrumb %}
{% endblock %}

{% block content %}
<div class="container container-fluid">
    <div class="row">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs dashboard-tabs">
            <li><a href="{{ url('devtools') }}">Dev Tools</a></li>
            <li><a href="{{ url('devtools/encode') }}">Encode</a></li>
            <li><a href="{{ url('devtools/encrypt') }}">Encrypt</a></li>
            <li><a href="{{ url('devtools/strings') }}">Strings</a></li>
            <li class="active"><a href="{{ url('devtools/fakedata') }}">Fake Data</a></li>
            <li><a href="{{ url('devtools/utf8chars') }}">UTF8 Chars</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active">

            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="output-error">
        <!-- dynamic data -->f
        </div>
        <div class="output">
        <!-- dynamic data -->f
        </div>
    </div>

</div>
<script type="text/javascript">
$(function() {
    $("form").submit(function(evt) {
        evt.preventDefault();

        var url      = $(this).attr('action');
        var postData = $(this).serialize();
        console.log(postData);

        $.post(url, postData, function(obj) {
            console.log(obj);
            if ( ! obj.result) {
                $(".output-error").html(obj.data.result);
                return;
            }

            $(".output").html(obj.data);

        }, 'json');
    });
});
</script>
{% endblock %}