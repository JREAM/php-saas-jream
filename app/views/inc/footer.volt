<div id="footer">
    <div id="inner-footer" class="clearfix">
        <div class="social-icons text-right">
            <a href="https://plus.google.com/+jream" target="_blank"><i class="fa fa-google-plus fa-lg"></i></a>
            <a href="https://www.facebook.com/jream" target="_blank"><i class="fa fa-facebook fa-lg"></i></a>
            <a href="https://twitter.com/jreamdesign" target="_blank"><i class="fa fa-twitter fa-lg"></i></a>
            <a href="http://youtube.com/jreamdesign" target="_blank"><i class="fa fa-youtube fa-lg"></i></a>
        </div>
        <div class="pull-left col-md-5" id="mc_embed_signup">
            <small>
            JREAM Mailing List
            </small>
            <form class="form-inline validate" action="https://jream.us7.list-manage.com/subscribe/post?u=c15f3eb6b4d64a6cbdc77eb9b&amp;id=5a56a15329" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" target="_blank" novalidate>
                <div class="form-group">
                    <input type="email" value="" name="EMAIL" class="form-control input-sm" id="mce-EMAIL" placeholder="Email Address" required>
                </div>
                <div class="form-group">
                    <input type="submit" value="Subscribe" name="Subscribe" id="mc-embedded-subscribe" class="btn btn-primary">
                </div>
                <div style="position: absolute; left: -5000px;"><input type="text" name="b_c15f3eb6b4d64a6cbdc77eb9b_5a56a15329" value=""></div>
            </form>
        </div>
        <div class="pull-right col-md-5">
            <p class="attribution text-right" style="margin-top: 30px">
                <small>
                    <a href="{{ url('blog') }}">Blog</a>
                    <a href="{{ url('product') }}">Products</a>
                    <a href="{{ url('services') }}">Services</a>
                    
                    <a href="{{ url('lab') }}">Lab</a>
                    <a href="{{ url('contact') }}">Contact</a>
                    <a href="{{ url('updates') }}">Updates</a>
                    
                    <a href="{{ url('terms') }}">Terms and Privacy</a>
                    &copy;2005 -{{ date('Y') }} JREAM
                </small>
            </p>
        </div>
    </div>
</div>

{% if system.info_display %}
<div id="system-info">
    <span class='info-timestamp'>{{ system.info_date }}</span>
    <i class="fa fa-exclamation-triangle"></i> {{ system.info_message }}
</div>
{% endif %}

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-3106599-2', 'auto');
  ga('send', 'pageview');
</script>

<a href="#top" id="goto-top" class="hide" title="Scroll Back to Top">
    <i class="fa fa-angle-up"></i>
</a>
