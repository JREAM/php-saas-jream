<ul class="nav navbar-nav pull-left nav-public">
    <li><a href="{{ url() }}">Home</a></li>
    <li><a href="{{ url('blog') }}">Blog</a></li>
    <li><a href="{{ url('product') }}">Products</a></li>
    <li>
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            Third-Party <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" aria-labelledby="dropdownThirdParty">
            <li><a href="{{ url('third-party') }}">Brief Summary</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Web Development</a></li>
            <li><a href="#">Service Providers</a></li>
          </ul>
    </li>
</ul>

<ul class="nav navbar-nav pull-right">
    {% if session.has('id') or session.has('fb_user_id') %}
    <li class="margin-10-right">
        <a class="btn btn-primary" href="{{ url('dashboard') }}">Dashboard</a>
    </li>
    <li class="dropdown">
        <a data-toggle="dropdown" class="btn btn-primary" href="#">{{ session.get('alias') }} <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <li><a href="{{ url('dashboard/account') }}"><span class="glyphicon glyphicon-user opacity-50"></span> My Account</a></li>
            <li><a href="{{ url('contact') }}"><span class="glyphicon glyphicon-comment opacity-50"></span> Support</a></li>
            <li class="divider"></li>
            <li><a href="{{ url('user/logout') }}"><span class="glyphicon glyphicon-log-out opacity-50"></span> Logout</a></li>
        </ul>
    </li>

    {% else %}
        <li><a class="btn btn-login" href="{{ url('user/login') }}">Login</a></li>
    {% endif %}
</ul>
