{% set current_action = router.getActionName()|lower %}
<div class="discover-mini">

    <div class="container container-fluid">

        <div class="row">

            <div class="col-xs-2 col-xs-offset-2">
                <div class="item {% if current_action == '' %}active{% endif %}">
                    <a href="{{ url('discover') }}"><img src="{{ url('img/ico/64/lamp.png') }}" class="img-responsive" alt="Discover"></a>
                    <a href="{{ url('discover') }}">Discover</a>
                </div>
            </div>

            <div class="col-xs-2">
                <div class="item {% if current_action == 'learn' %}active{% endif %}">
                    <a href="{{ url('discover/learn') }}"><img src="{{ url('img/ico/64/thinking-gears.png') }}" class="img-responsive" alt="Learn"></a>
                    <a href="{{ url('discover/learn') }}">Watch &amp; Learn</a>
                </div>
            </div>

            <div class="col-xs-2">
                <div class="item {% if current_action == 'benefit' %}active{% endif %}">
                    <a href="{{ url('discover/benefit') }}"><img src="{{ url('img/ico/64/application-gears.png') }}" class="img-responsive" alt="Benefit"></a>
                    <a href="{{ url('discover/benefit') }}">Benefits</a>
                </div>
            </div>

        </div>

    </div>

</div>