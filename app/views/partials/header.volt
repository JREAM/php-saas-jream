<div class="overlay hide"></div>
<div id="top"></div>
<div id="header">
    <div class="navbar" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ url() }}">
                    <img id="logo" src="{{ config.url_static }}img/logo/logo-full-xs.svg" alt="JREAM">
                    <img id="logo-ico" class="hide" src="{{ config.url_static }}img/logo/icon-sm.svg" alt="JREAM">
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-responsive-collapse">
                {% include "partials/nav.volt" %}
            </div><!-- /navbar-collapse -->
        </div>
    </div>
</div>
