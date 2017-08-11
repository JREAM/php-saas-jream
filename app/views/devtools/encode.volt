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
            <li class="active"><a href="{{ url('devtools/encode') }}">Encode</a></li>
            <li><a href="{{ url('devtools/encrypt') }}">Encrypt</a></li>
            <li><a href="{{ url('devtools/strings') }}">Strings</a></li>
            <li><a href="{{ url('devtools/fakedata') }}">Fake Data</a></li>
            <li><a href="{{ url('devtools/utf8chars') }}">UTF8 Chars</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active">
                <div class="margin-20-top"></div>
                <form class="form-horizontal" action="{{ url('devtools/doencode') }}">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <textarea id="textarea" name="text" class="form-control" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Encode/Decode</label>
                        <div class="col-sm-2">
                            <select class="form-control" name="method">
                                <?php foreach ($methods as $key => $method):?>
                                    <option value="<?=$key?>"><?=$method?></option>
                                <?php endforeach;?>
                            </select>
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

{% endblock %}

{% block script %}
<script>
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
