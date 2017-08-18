{% extends "templates/full.volt" %}

{% block title %}
<span class="title">Test</span>
{% endblock %}

{% block content %}
AJAX TEST

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div id="result"></div>
         </div>
     </div>
 </div>
{% endblock %}


{% block script %}
<script>
$(function() {
    $.post('{{  url('api/v1/test') }}', function(e) {
        console.log(e);
    }, 'json');

});
</script>
{% endblock %}


