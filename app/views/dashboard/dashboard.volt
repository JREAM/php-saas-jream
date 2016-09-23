{% extends "templates/full.volt" %}

{% block head %}
<script src="https://apis.google.com/js/platform.js"></script>
{% endblock %}

{% block title %}
    <h1>Dashboard</h1>
{% endblock %}

{% block breadcrumb %}
<ol class="breadcrumb pull-right">
    <a href="#your-courses">Your Courses</a> /
    <a href="#available-courses">Available Courses</a>
</ol>
<ol class="breadcrumb">
    <li class="active">Dashboard</li>
</ol>
{% endblock %}



{% block content %}
<div class="container container-fluid">
    <div class="row">


        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="#your-courses" id="your-courses"><i class="fa fa-video-camera opacity-50" aria-hidden="true"></i> Your Courses</a>
                <a href="#top" class="pull-right top"><i class="fa fa-angle-up"></i></a>
            </div>
            <div class="panel-body text-center">
                {% for userPurchase in userPurchases %}

                <?php
                $productId = $userPurchase->getProduct()->id;
                $percent = $productStatus[$productId];
                ?>

                <div class="img-thumbnail img-responsive dashboard-image-list">
                    <a href="{{ url('dashboard/course/index/') }}{{ userPurchase.getProduct().id }}">
                        <img class="fadeover " src="{{ userPurchase.getProduct().img_sm }}" alt="{{ userPurchase.getProduct().title }}" />
                    </a>
                    <div class="dashboard-product-title text-center">
                        <strong>{{ userPurchase.getProduct().title }}</strong>
                    </div>

                    <div class="progress" style="max-width: 320px; margin-top: 5px">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?=$percent?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$percent?>%">
                        </div>
                    </div>
                </div>
                {% endfor %}
                {% if userPurchases|length == 0 %}
                <div class="img-thumbnail img-responsive dashboard-image-list">
                    <img src="{{ url('img/no-courses.jpg') }}" alt="No Courses">
                </div>
                {% endif %}
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="#available-courses" id="available-courses"><i class="fa fa-shopping-cart opacity-50" aria-hidden="true"></i> Available Courses</a>
                <a href="#top" class="pull-right top"><i class="fa fa-angle-up"></i></a>
            </div>
            <div class="panel-body">

                {% if products|length != 0 %}
                    {% for index, product in products %}
                    <div class="relative img-thumbnail img-responsive dashboard-image-list {% if product.status == constant('\Product::STATUS_PLANNED') %}grayscale{% endif %}" {% if product.status !== 'development' and product.price != 0 %}data-toggle="popover" data-placement="top" data-content="Click to Purchase {{ product.title }}"{% endif %}>
                        <a href="{{ url('product/view/') }}{{ product.slug }}">
                            <img class="fadeover" src="{{ product.img_sm }}" alt="{{ product.title }}" />
                        </a>
                        <div class="dashboard-product-title text-center">
                            <strong><a href="{{ url('product/view/') }}{{ product.slug }}"><i class="fa fa-arrow-circle-right"></i> {{ product.title }}</a></strong>
                            <br />
                            {% if product.status == constant('\Product::STATUS_DEVELOPMENT') %}
                                Development
                            {% elseif product.status == constant('\Product::STATUS_PLANNED') %}
                                Planned
                            {% elseif product.price != 0 and product.status != constant('\Product::STATUS_DEVELOPMENT') %}
                                <sup>$</sup>{{ product.price }}
                            {% else %}
                                Free
                            {% endif %}
                        </div>
                    </div>
                    {% endfor %}
                {% endif %}

            </div>
        </div>
        </div>


</div>
<script>
/**
 * Remember the Hash
 */
$(function(){
    var hash = window.location.hash;
    hash && $('ul.nav a[href="' + hash + '"]').tab('show');

    $('.nav-tabs a').click(function (e) {
        $(this).tab('show');
        var scrollmem = $('body').scrollTop();
        window.location.hash = this.hash;
        $('html,body').scrollTop(scrollmem);
    });

});
</script>

<!-- Facebook Conversion Code for Acccounts - JREAM -->
<script>(function() {
  var _fbq = window._fbq || (window._fbq = []);
  if (!_fbq.loaded) {
    var fbds = document.createElement('script');
    fbds.async = true;
    fbds.src = '//connect.facebook.net/en_US/fbds.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(fbds, s);
    _fbq.loaded = true;
  }
})();
window._fbq = window._fbq || [];
window._fbq.push(['track', '6027505615509', {'value':'0.00','currency':'USD'}]);
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6027505615509&amp;cd[value]=0.00&amp;cd[currency]=USD&amp;noscript=1" /></noscript>
{% endblock %}