<link rel="stylesheet" href="{{ url('vendor/flowplayer/skin/functional.css') }}" type="text/css">

{#<div id="fp-hlsjs"></div>#}

<!-- Flash RMTP -->
<div class="flowplayer functional"
     data-swf="{{ url('vendor/flowplayer/flowplayerhls.swf') }}"
     data-key="$795097514805828"
     data-rtmp="{{ api.aws.cloudfront.rtmpUrl }}cfx/st"
     data-analytics="UA-3106599-2"
     data-ratio="0.4167"
     data-aspect-ratio="16:9"
     data-embed="false"
     style="height: 720px !important; background-size: cover !important;">


    <!-- The order of preference -->
    <video>
        <source type="video/flash" src="mp4:{{ rtmpSignedUrl['mp4'] }}">
        {#<source type="video/mp4" src="">#}
    </video>

</div>

<!-- @TODO HLS Elastic Transcoder non-RMTP is quite tricky without flash, still looking into it -->

<!-- Init Flowplayer Scripts, Attempt HTML5 HLS first -->
<script src="{{ url('vendor/flowplayer/flowplayer.min.js') }}"></script>
<script src="{{ url('vendor/flowplayer/flowplayer.hlsjs.min.js') }}"></script>

<!-- Fallback to Flash for RMTP if clients browser is not compatible -->
<script>
$(function() {
//
//    flowplayer("#fp-hlsjs", {
//        splash: true,
//        loop: false,
//        ratio: 9/16,
//
//        playlist: [{
//            title: "3 audio tracks",
//            hlsjs: {
//                // codec not specified in master playlist
//                defaultAudioCodec: "mp4a.40.2"
//            },
//            sources: [
//                { type: "application/x-mpegurl",
//                  src: "//wowzaec2demo.streamlock.net/vod-multitrack/_definst_/smil:ElephantsDream/ElephantsDream.smil/playlist.m3u8" }
//            ]
//        }, {
//            title: "4 subtitle tracks, 2 audio tracks",
//            hlsjs: {
//                // enable subtitle display
//                subtitles: true,
//                // enable audio ABR
//                audioABR: true
//            },
//            sources: [
//                { type: "application/x-mpegurl",
//                  src: "//bitdash-a.akamaihd.net/content/sintel/hls/playlist.m3u8" }
//            ]
//        }],
//        embed: false
//    });

});
</script>
