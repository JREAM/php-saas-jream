{% extends "templates/full.volt" %}

{% block title %}
<h1>Admin: Quiz</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url('admin') }}">Admin</a></li>
    <li class="active">Quiz</li>
</ol>
{% endblock %}


{% block content %}
<div class="container container-fluid">
<div class="row">

    <div class="col-md-9">

        <a class="btn btn-lg btn-primary" href="{{ url('admin/quiz/edit') }}">New Quiz</a>

        <table class="table">
        <thead>
            <tr>
                <td>ID</td>
            </tr>
        </thead>
        {% if quizzes|length == 0 %}
            <tr>
                <td>No Results</td>
            </tr>
        {% else %}
            {% for quiz in quizzes %}
            <tr>
                <td>{{ quiz.id }}</td>
            </tr>
            {% endfor %}
        {% endif %}
        </table>

        <br>


    </div>


</div>
</div>

{% endblock %}