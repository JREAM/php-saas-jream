<ul class="nav navbar-nav pull-left nav-public">
    <li><a href="{{ url() }}">Home</a></li>
    <li><a href="{{ url('product') }}">Products</a></li>
</ul>

<ul class="nav navbar-nav pull-right">
    {# @TODO Need to fix, cuz we'll have many options #}
    {% if session.has('is_logged_in') %}
    <li class="margin-10-right">
        <a class="btn btn-primary" href="{{ url('dashboard') }}">Dashboard</a>
    </li>
    <li class="dropdown">
        <a data-toggle="dropdown" class="btn btn-primary" href="#">{{ session.get('alias') }} <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <li><a href="{{ url('dashboard/account') }}"><span class="glyphicon glyphicon-user opacity-50"></span> My Account</a></li>
            <li><a href="{{ url('contact') }}"><span class="glyphicon glyphicon-comment opacity-50"></span> Support</a></li>
            <li class="divider"></li>
            <li><a href="{{ url('api/auth/logout') }}"><span class="glyphicon glyphicon-log-out opacity-50"></span> Logout</a></li>
        </ul>
    </li>

    {% else %}
    <li>
        <a class="btn btn-primary" href="{{ url('user/login') }}">Login</a>
         {#<ul id="login-dropdown" >#}
                {#<li class="dropdown">#}
                    {#<button type="button" id="dropdownMenu1" data-toggle="dropdown" class="btn btn-info navbar-btn dropdown-toggle"><i class="glyphicon glyphicon-user"></i> Login <span class="caret"></span></button>#}
                    {#<ul class="dropdown-menu">#}
                      {#<li>#}
                            {#<a href="{{ url('api/auth/github') }}" class="btn btn-lg btn-social-icon btn-github" title="Sign in with Github">#}
                                {#<i class="fa fa-github"></i> Sign in with Github#}
                            {#</a>#}
                            {#<a href="{{ url('api/auth/google') }}" class="btn btn-inline btn-lg btn-social-icon btn-github" title="Sign in with Google">#}
                                {#<i class="fa fa-google"></i>#}
                            {#</a>#}
                            {#<a href="{{ url('api/auth/facebook') }}" class="btn btn-inline btn-lg btn-social-icon btn-github" title="Sign in with Facebook">#}
                                {#<i class="fa fa-facebook"></i>#}
                            {#</a>#}

                            {#<form class="navbar-form form" role="form">#}
                                {#<div class="form-group">#}
                                  {#<div class="input-group">#}
                                        {#<span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>#}
                                        {#<!--EMAIL ADDRESS-->#}
                                        {#<input id="emailInput" placeholder="email address" class="form-control" type="email" oninvalid="setCustomValidity('Please enter a valid email address!')" onchange="try{setCustomValidity('')}catch(e){}" required="">#}
                                    {#</div>#}
                                {#</div>#}
                                {#<div class="form-group">#}
                                    {#<div class="input-group">#}
                                        {#<span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue"></i></span>#}
                                        {#<!--PASSWORD-->#}
                                        {#<input id="passwordInput" placeholder="password" class="form-control" type="password" oninvalid="setCustomValidity('Please enter a password!')" onchange="try{setCustomValidity('')}catch(e){}" required="">#}
                                    {#</div>#}
                                {#</div>#}
                                {#<!--  BASIC ERROR MESSAGE#}
                                {#<div class="form-group">#}
                                {#<label class="error-message color-red">*Email &amp; password don't match!</label>#}
                                {#</div>#}
                                {#-->#}
                                {#<div class="form-group">#}
                                    {#<!--BUTTON-->#}
                                    {#<button type="submit" class="btn btn-primary form-control">Login</button>#}
                                {#</div>#}
                                {#<div class="form-group">#}
                                    {#<!--RESET PASSWORD LINK-->#}
                                    {#<span class="pull-right"><a href="#">Forgot Password?</a></span>#}
                                {#</div>#}
                            {#</form>#}
                        {#</li>#}
                    {#</ul>#}
                {#</li>#}
            {#</ul>#}
        </li>
        <li><a class="btn btn-primary" href="{{ url('user/register') }}">Register</a></li>
    {% endif %}
</ul>
