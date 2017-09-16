<div id="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4">

                <div class="social-icons">
                    <a href="https://plus.google.com/+jream" target="_blank"><i class="fa fa-google-plus fa-lg"></i></a>
                    <a href="https://www.facebook.com/jream" target="_blank"><i class="fa fa-facebook fa-lg"></i></a>
                    <a href="https://twitter.com/jreamdev" target="_blank"><i class="fa fa-twitter fa-lg"></i></a>
                    <a href="http://youtube.com/jream" target="_blank"><i class="fa fa-youtube fa-lg"></i></a>
                </div>

                <div id="mc_embed_signup">
                    <small>Newsletter</small>
                    <form id="formFooterNewsletterSubscribe" class="form-inline validate" action="{{ url('auth/newsletter/subscribe') }}" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" target="_blank" novalidate>
                        <div class="form-group">
                            <input type="email" value="" name="email" class="form-control input-sm" id="mce-EMAIL" placeholder="Email Address" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Subscribe" name="Subscribe" class="btn btn-primary">
                        </div>
                    </form>
                </div>

            </div>
            <div class="col-md-4">
                <ul class="footer-links text-right list-unstyled">
                    <li><a href="{{ url('blog') }}">Blog</a></li>
                    <li><a href="{{ url('product') }}">Products</a></li>
                    <li><a href="{{ url('promotion') }}">Promotions</a></li>
                    <li><a href="{{ url('lab') }}">Lab</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <ul class="footer-links text-right list-unstyled">
                    <li><a href="{{ url('contact') }}">Contact</a></li>
                    <li><a href="{{ url('updates') }}">Updates</a></li>
                    <li><i class="fa fa-external-link" aria-hidden="true"></i> <a href="//jream.studio" target="blank">Development Studio</a></li>
                    <li><a href="{{ url('terms') }}">Terms and Privacy</a></li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 pull-right text-right">
                &copy;2005 - {{ date('Y') }} JREAM
            </div>
        </div>
    </div>
</div>

<!-- Dependencies -->
<script src="{{ url('vendor/bootstrap/bootstrap.min.js') }} "></script>
{#<script src="{{ url('vendor/validator.min.js') }} "></script>#}
{#<script src="{{ url('vendor/moment.min.js') }} "></script>#}
<script src="{{ url('vendor/jquery.expander.min.js') }} "></script>

<!-- CDN -->
<script src='//www.google.com/recaptcha/api.js'></script>

<!-- App -->
<script src="{{ url('js/app.js') }}{{ cacheBust }}"></script>

{% if constant('\APPLICATION_ENV') == constant('\APP_DEVELOPMENT') %}
    <script src="//localhost:35729/livereload.js"></script>
{% else %}
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-3106599-2', 'auto');
      ga('send', 'pageview');
    </script>
{% endif %}

<a href="#top" id="goto-top" class="hide" title="Scroll Back to Top">
    <i class="fa fa-angle-up"></i>
</a>
