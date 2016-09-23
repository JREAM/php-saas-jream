# TODO

# Streaming Problems with WebM
- Works fine with mp4
- Signed URLs with AES Encryption failing.
- Added the Streaming READ CloudFront user to everything
- Both webm and mp4 show error when loading RTMP URI, yet it works with mp4.
- Appears "Location not found", but I can use an unsigned url from flowplayer to make it work.
- @TRY: To make a video run from the stream that requires no signing? Forgot how I did this, is it even possible from the RTMP distro?
- @TRY: Make separate RTMP bucket and distro for streaming?

## Improvements
- User Table: account_type = enum(default, facebook, google)
- User Table: email = (associated with default/fb/google)
- A few methods would have to change (signup, get icons, get email, etc)

## Features
- Confirm email address: Gives Gold Star, 5% off course
- Complete course: Gives Gold Star

- Forums, can only post in certain areas if course was purchased
- Forums, can only post if email verified
- Protect against a bunch of crap messages

- Remove account, Set removal for 2 weeks, then it will perm delete with a flag pending (Cron)


## Bugs
- Fix Singup AJAX on live site under product page
- [FIXED] Broken link? Still gathering info from user.