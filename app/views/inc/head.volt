    <meta charset="utf-8">
    <meta name="google-site-verification" content="dwKdmkj79fk1i7bjLXbYlCLiFLF-iOa7xu0g-s0yypw" />
    {{ get_title() }}
    <link rel="icon" href="{{ url('img/favicon.png') }}" type="image/png">
    <link href='https://fonts.googleapis.com/css?family=Titillium+Web' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{{ url('third-party/bootstrap-social/bootstrap-social.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ url('third-party/devicons/css/devicons.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ url ('third-party/flowplayer/skin/functional.css') }}" type="text/css">
    <!-- Built Dependencies -->
    <script src="{{ url('third-party/js/dependencies.min.js') }} "></script>

    <link rel="stylesheet" href="<?php echo $this->url->get('third-party/flowplayer/skin/skin.css'); ?>" type="text/css">
    <script src="{{ url('third-party/flowplayer/flowplayer.min.js') }}"></script>

    <!-- Re-Captcha -->
    <script src='https://www.google.com/recaptcha/api.js'></script>

    <!-- Built App -->
    <link rel="stylesheet" href="{{ url('third-party/css/dependencies.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ url('css/app.min.css') }}" type="text/css">
    <script src="{{ url('js/app.min.js') }}"></script>

    {% if router.getNamespaceName()|lower == 'admin' %}
        <script src="{{ url('third-party/select2/select2.min.js') }} "></script>
        <link rel="stylesheet" href="{{ url('third-party/select2/select2.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ url('third-party/select2/select2-bootstrap.css') }}" type="text/css">
        <script>
        $(function() {
            if ($.isFunction($.fn.select2)) {
                $(".select2").select2();
            }
        });
        </script>
    {% endif %}

