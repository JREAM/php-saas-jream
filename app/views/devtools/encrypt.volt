{% extends "templates/full.volt" %}

{% block title %}
<span class="title">DevTools</span>
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

{% block content %}
<div class="container container-fluid">
    <div class="row">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs dashboard-tabs">
            <li><a href="{{ url('devtools') }}">Dev Tools</a></li>
            <li><a href="{{ url('devtools/encode') }}">Encode</a></li>
            <li class="active"><a href="{{ url('devtools/encrypt') }}">Encrypt</a></li>
            <li><a href="{{ url('devtools/strings') }}">Strings</a></li>
            <li><a href="{{ url('devtools/fakedata') }}">Fake Data</a></li>
            <li><a href="{{ url('devtools/utf8chars') }}">UTF8 Chars</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active">
                <div class="margin-20-top"></div>
                <form class="form-horizontal" action="{{ url('devtools/doencrypt') }}">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <textarea id="textarea"  name="text" class="form-control" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Hash</label>
                        <div class="col-sm-2">
                            <select class="form-control" name="method">
                                <?php foreach ($methods as $key => $method):?>
                                    <option value="<?=$key?>"><?=$method?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="salt" class="form-control" placeholder="Salt (Appended) (Optional)" maxlength="1000">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-6">
                            <input type="submit" class="btn btn-success btn-lg">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-12">
            <div class="output-error">
            <!-- dynamic data -->
            </div>
            <div class="output">
            <!-- dynamic data -->
            </div>
        </div>

    </div>

</div>

{% endblock %}

{% block script %}
<script>
$(function() {

    var success_count = 0;

    $("form").submit(function(evt) {
        evt.preventDefault();

        var highlight;
        var url      = $(this).attr('action');
        var postData = $(this).serialize();
        console.log(postData);

        $.post(url, postData, function(obj) {
            console.log(obj);
            if ( ! obj.result) {
                $(".output-error").html('<div class="">' + obj.data.result + '</div>');
                return;
            }

            highlight = (success_count % 2 == 0) ? 'success-row-alt': 'success-row';
            is_salted = (obj.data.salt) ? 'salted' : 'no salt';

            $(".output").prepend('<div class="'+ highlight +'"> <span class="label salt">' + is_salted + '</span>' + obj.data.hash + '</div>');
            console.log(success_count)
            success_count++;

        }, 'json');
    });
});
</script>
{% endblock %}
