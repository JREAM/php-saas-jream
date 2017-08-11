/**
 * Countdown for Promotion
 * @type {Number}
 * @depends moment.js
 */
let countdown = function(expires_timestamp, now_timestamp) {

  let diff_time = eventTime - now_timestamp;
  let duration = moment.duration(diff_time*1000, 'milliseconds');
  let interval = 1000;

  setInterval(function(){
    duration = moment.duration(duration - interval, 'milliseconds');
      $('.countdown').text(duration.hours() + ":" + duration.minutes() + ":" + duration.seconds())
  }, interval);
};


module.exports = countdown;
