{% extends "templates/full.volt" %}

{% block title %}
<h1>Admin: Quiz Edit</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb">
    <li><a href="{{ url('admin') }}">Admin</a></li>
    <li><a href="{{ url('admin/quiz') }}">Quiz</a></li>
    <li class="active">Edit</li>
</ol>
{% endblock %}


{% block content %}
<div class="container container-fluid">
<div class="row">

    <div class="admin-quiz-control">
        <button data-type="single-choice" class="btn-default add-question">New Single Choice</button>
        <button data-type="multiple-choice" class="btn-default add-question">New Multiple Choice</button>
        <button data-type="text" class="btn-default add-question">New Text Question</button>
        <button id="save" class="btn-primary" value="Save Form" />Save Form</div>
    </div>

    <div class="col-md-9">

        <input type="text" name="title" class="form-control input-lg" placeholder="Quiz Name">
        <div class="checkbox pull-right">
            <label>
                <input type="checkbox" name="published"> Published
            </label>
        </div>
        <form id="form-create-quiz">
            <p>
                Use an option on the right to start adding questions!
            </p>
            <!-- Dynamic Content -->
        </form>
    </div>


</div>
</div>

<script>
$(function() {

    var questionCount = 0;

    /**
     * Probably to hold and build the form with
     */
    var internalStorage = {};

    function addQuestion(type) {
        var answerable;

        switch (type) {
            case 'single-choice':
                answerable = '\n\
                <div class="radio">\n\
                    <label><input type="radio" name="answer_'+questionCount+'_0" value="0"> <input class="form-control" name="answer_text_'+questionCount+'_0" type="text" placeholder="Possible Answer"></label>\n\
                </div>\n\
                <div class="radio">\n\
                    <label><input type="radio" name="answer_'+questionCount+'_1" value="0"> <input class="form-control" name="answer_text_'+questionCount+'_1" type="text" placeholder="Possible Answer"></label>\n\
                </div>\n\
                <div class="radio">\n\
                    <label><input type="radio" name="answer_'+questionCount+'_2" value="0"> <input class="form-control" name="answer_text_'+questionCount+'_2" type="text" placeholder="Possible Answer"></label>\n\
                </div>\n\
                <div class="radio">\n\
                    <label><input type="radio" name="answer_'+questionCount+'_3" value="0"> <input class="form-control" name="answer_text_'+questionCount+'_3" type="text" placeholder="Possible Answer"></label>\n\
                </div>\n\
                ';
                break;
            case 'multiple-choice':
                answerable = '\n\
                <div class="checkbox">\n\
                    <label><input type="checkbox" name="answer_'+questionCount+'_0" value="0"> <input class="form-control" name="answer_text_'+questionCount+'_0" type="text" placeholder="Possible Answer"></label>\n\
                </div>\n\
                <div class="checkbox">\n\
                    <label><input type="checkbox" name="answer_'+questionCount+'_1" value="0"> <input class="form-control" name="answer_text_'+questionCount+'_1" type="text" placeholder="Possible Answer"></label>\n\
                </div>\n\
                <div class="checkbox">\n\
                    <label><input type="checkbox" name="answer_'+questionCount+'_2" value="0"> <input class="form-control" name="answer_text_'+questionCount+'_2" type="text" placeholder="Possible Answer"></label>\n\
                </div>\n\
                <div class="checkbox">\n\
                    <label><input type="checkbox" name="answer_'+questionCount+'_3" value="0"> <input class="form-control" name="answer_text_'+questionCount+'_3" type="text" placeholder="Possible Answer"></label>\n\
                </div>\n\
                ';
                break;
            case 'text':
                answerable = 'The Correct Answer Will Be: <br> <input type="text" name="answer_'+questionCount+'">';
                break;
            default:
                alert('Invalid Option');
                return false;
        }

        var tpl = '\n\
        <div class="question-container" id="question-'+ questionCount +'">\n\
            <h3>Question ' + questionCount + '<button type="button" class="close pull-right delete-question" data-id="'+ questionCount +'">&times;</button></h3>\n\
            <div class="form-group">\n\
                <textarea name="question" class="form-control input-lg" placeholder="Content For the Question"></textarea>\n\
            </div>\n\
            <div class="form-group" class="dynamic-content">\n\
                '+ answerable +'\n\
            </div>\n\
            <div>\n\
                <button class="btn-primary" class="edit-question">Edit</button>\n\
                <button class="btn-primary" class="save-question">Save</button>\n\
            </div>\n\
        </div>\n\
        ';

        // Increment for uniques
        questionCount++;

        return tpl;
    }

    /**
     * Add a dang question.
     */
    $(".add-question").click(function(evt) {
        var output = '';

        // @TODO Check if ANY items are NOT saved, then say -- hey you gotta save your question to add another.

        var type = $(this).data('type');
        var data = addQuestion(type)

        $("#form-create-quiz").append(data);
    });

    /**
     * Delete a dang question
     */
    $(document).on('click', '.delete-question', function(evt) {
        var c = confirm("Are you sure you want to remove this question?");
        if (c == false) return false;

        var id = $(this).data('id');
        $("#question-" + id).remove();
    });

    $("#save").click(function(evt) {
        var c = confirm("Are you ready to save?");
        if (c == false) return false;

        alert('Pretend save.')
    })

});
</script>
{% endblock %}