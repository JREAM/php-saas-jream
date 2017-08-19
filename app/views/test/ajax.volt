{% extends "templates/full.volt" %}

{% block title %}
<span class="title">Test</span>
{% endblock %}

{% block content %}
<br>
    <br><br><br><br><br><br><br><br>AJAX TEST

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div id="result"></div>

            <input type="button" id="retry" value="Retry">
         </div>
     </div>
 </div>
{% endblock %}


{% block script %}
<script>
$(function() {
    var data = {
        nothing: 1,
        csrf: window.csrf
    };
    $("#retry").click(function(evt) {
        evt.preventDefault();
        $.post('{{  url('api') }}/test', data, function(data) {},'json');
    })
    $.post('{{  url('api') }}/test', data, function(data) {
        console.log(data);

    }, 'json');

});
</script>
{% endblock %}


