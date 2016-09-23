<div class="flowplayer"
    data-engine="flash"
    data-rtmp="{{ api.aws.cloudfront.rtmpUrl }}cfx/st"
    data-width="50px"
    advance="false"
    data-analytics="UA-3106599-2"
    data-key="$795097514805828">

    <video>
        <source type="video/flash" src="mp4:{{ rtmpSignedUrl['mp4'] }}">
    </video>

</div>

<script>
flowplayer.conf = {
   ratio: 9/16,
   embed: false,
   adaptiveRatio: true
};
flowplayer(function (api) {
   api.bind("load", function (e, api, video) {
      $("#videosrc").text(video.src);
    });
});
</script>