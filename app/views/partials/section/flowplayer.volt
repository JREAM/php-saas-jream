<link rel="stylesheet" href="{{ url('vendor/flowplayer/skin/functional.css') }}" type="text/css">

<div class="flowplayer functional"
     data-swf="{{ url('vendor/flowplayer/flowplayerhls.swf') }}"
     data-key="$795097514805828"
     data-rtmp="{{ api.aws.cloudfront.rtmpUrl }}cfx/st"
     data-analytics="UA-3106599-2"
     data-ratio="0.4167"
     data-aspect-ratio="16:9"
     data-embed="false"
     style="height: 720px !important; background-size: cover !important;">

    <video>
        <source type="video/flash" src="mp4:{{ rtmpSignedUrl['mp4'] }}">
        <source type="video/mp4" src="">
    </video>

</div>

<script src="{{ url('vendor/flowplayer/flowplayer.min.js') }}"></script>
<script src="{{ url('vendor/flowplayer/flowplayer.hlsjs.min.js') }}"></script>

<script>
$(function() {

    flowplayer("flowplayer", "{{ url('vendor/flowplayer/flowplayer.swf') }}", {
        // required for hardware accelaration to take effect
        wmode: "direct",
        adaptiveRatio: true,
        controls: {
            // enable tooltips for the buttons
            tooltips: { buttons: true }
        },
        logo: {
            url: 'https://d2qmoq5vnrtrov.cloudfront.net/img/logo/logo-full-xs.svg',
            fullscreenOnly: false,
            displayTime: 10
        },
        clip: {
            url: "mp4:{{ rtmpSignedUrl['mp4'] }}",
            autoPlay: false,
            autoBuffering: true,
            provider: 'rtmp',
             scaling: 'fit',
            // enable hardware acceleration
            accelerated: true,
            urlResolver: 'cloudfront'
        },
        plugins: {
            rtmp: {
                url: "{{ url('vendor/flowplayer/plugins/flowplayer.rtmp.swf') }}",
                netConnectionUrl: '{{ api.aws.cloudfront.rtmpUrl }}cfx/st'
            },
    //        cloudfront: {
    //            url: "flowplayer.cloudfrontsignedurl-1.0.swf"
    //        },
            slowmotion: {
                url: "{{ url('vendor/flowplayer/plugins/flowplayer.slowmotion.swf') }}",
                serverType: "fms"
            },
            speedIndicator: {
                url: "{{ url('vendor/flowplayer/plugins/flowplayer.content.swf') }}",
                bottom: 50,
                right: 15,
                width: 135,
                height: 30,
                border: 'none',
                style: {
                    body: {
                        fontSize: 14,
                        fontFamily: 'Trebuchet',
                        textAlign: 'center',
                        color: '#f2f2f2'
                    }
                },

                backgroundColor: 'rgba(20, 20, 20, 0.65)',

                // Don't want speed-indicator plugin to be displayed by default,
                // only when a speed change occurs.
                display: 'none'
            },
        }
    });

    flowplayer(function (api) {
      api.on("load", function (e, api) {
        alert(api.engine.engineName + " engine in use");
      });
       api.bind("load", function (e, api, video) {
          $("#videosrc").text(video.src);
        });
    });

});
</script>
