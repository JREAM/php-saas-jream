
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta content="telephone=no" name="format-detection">

  <!-- @TODO: REMOVE WHEN PRODUCTION READY -->
  <meta name="robots" content="noindex, nofollow">

  <title>Hire JREAM</title>

  <link href="{{ url('subsections/hire/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ url('subsections/hire/css/override.css') }}" rel="stylesheet">
  <!--[if lt IE 9]>
    <script src="{{ url('subsections/hire/js/ie-fix/html5shiv.min.js') }}'"></script>
    <script src="{{ url('subsections/hire/js/ie-fix/respond.min.js') }}'"></script>
  <![endif]-->
</head>
<body data-spy="scroll" data-target="#navbar">
<div class="load"></div>

{% include "hire/sections/slider.volt" %}

{% include "hire/partials/nav.volt" %}

<!-- start:Main Sections -->
{% include "hire/sections/greeting.volt" %}
{% include "hire/sections/services.volt" %}
{% include "hire/sections/benefits.volt" %}
{% include "hire/sections/statistics.volt" %}
{% include "hire/sections/team.volt" %}
{% include "hire/sections/proposal.volt" %}
{% include "hire/sections/prices.volt" %}
{% include "hire/sections/stages.volt" %}
{% include "hire/sections/partners.volt" %}
{% include "hire/sections/contacts.volt" %}
<!-- stop:Main Sections -->

{% include "hire/partials/footer.volt" %}



<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="{{ url('subsections/hire/js/scrollspy.js') }}"></script>
<script src="{{ url('subsections/hire/js/bootstrap.min.js') }}"></script>
<script src="{{ url('subsections/hire/js/custom.js') }}"></script>
</body>
</html>
