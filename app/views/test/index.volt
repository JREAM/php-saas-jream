<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    {{ get_title() }}
    <link rel="icon" type="image/png" href="{{ url('img/favicon.png') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Ubuntu:300" type="text/css" />
    <link rel="stylesheet" href="{{ url('third-party/css/bootstrap.min.css') }}" type="text/css" />
    <script src="{{ url('third-party/js/jquery.min.js') }}"></script>
    <script src="{{ url('third-party/js/bootstrap.js') }}"></script>
</head>
<body>

<div class="container container-fluid">


<div class="bs-example">
    <ul class="nav nav-pills">
      <li class="active"><a href="#">Regular link</a></li>
      <li class="dropdown">
        <a id="drop4" role="button" data-toggle="dropdown" href="#">Dropdown <b class="caret"></b></a>
        <ul id="menu1" class="dropdown-menu" role="menu" aria-labelledby="drop4">
          <li role="presentation"><a role="menuitem" tabindex="-1" href="http://twitter.com/fat">Action</a></li>
          <li role="presentation"><a role="menuitem" tabindex="-1" href="http://twitter.com/fat">Another action</a></li>
          <li role="presentation"><a role="menuitem" tabindex="-1" href="http://twitter.com/fat">Something else here</a></li>
          <li role="presentation" class="divider"></li>
          <li role="presentation"><a role="menuitem" tabindex="-1" href="http://twitter.com/fat">Separated link</a></li>
        </ul>
      </li>
      <li class="dropdown">
        <a id="drop5" role="button" data-toggle="dropdown" href="#">Dropdown 2 <b class="caret"></b></a>
        <ul id="menu2" class="dropdown-menu" role="menu" aria-labelledby="drop5">
          <li role="presentation"><a role="menuitem" tabindex="-1" href="http://twitter.com/fat">Action</a></li>
          <li role="presentation"><a role="menuitem" tabindex="-1" href="http://twitter.com/fat">Another action</a></li>
          <li role="presentation"><a role="menuitem" tabindex="-1" href="http://twitter.com/fat">Something else here</a></li>
          <li role="presentation" class="divider"></li>
          <li role="presentation"><a role="menuitem" tabindex="-1" href="http://twitter.com/fat">Separated link</a></li>
        </ul>
      </li>
      <li class="dropdown">
        <a id="drop6" role="button" data-toggle="dropdown" href="#">Dropdown 3 <b class="caret"></b></a>
        <ul id="menu3" class="dropdown-menu" role="menu" aria-labelledby="drop6">
          <li role="presentation"><a role="menuitem" tabindex="-1" href="http://twitter.com/fat">Action</a></li>
          <li role="presentation"><a role="menuitem" tabindex="-1" href="http://twitter.com/fat">Another action</a></li>
          <li role="presentation"><a role="menuitem" tabindex="-1" href="http://twitter.com/fat">Something else here</a></li>
          <li role="presentation" class="divider"></li>
          <li role="presentation"><a role="menuitem" tabindex="-1" href="http://twitter.com/fat">Separated link</a></li>
        </ul>
      </li>
    </ul> <!-- /pills -->
  </div>


</div>

</body>
</html>
