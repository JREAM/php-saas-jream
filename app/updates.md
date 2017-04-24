_This is an overview of JREAM development. This is not a comprehensive list of every update._

## 4-23-17
- Launched Blog, it's now in the main navigation.
- Blog Design is not complete, writing content to help people first.
- PS: I don't use WordPress :)

## 4-22-17
- Working on researching various frameworks for new changes
- Preparing a new UI Design
- Setting up a Blog, Design will come after content [https://blog.jream.com](https://blog.jream.com)
- Working on HTTP2 for Everything
- Looking for Large Hosting Platform for Several Projects

## 4-2-17
- Backend: Medium amount of code refactoring
- Upgrade: Moved to Phalcon 3
- Upgrade: Moved to PHP7, new geo-location.
- Bugs: Fixed isSecureRequest() to isSecure() having errors from v3 Phalcon.


## 7-17-16
- UI: Adding More Icons
- UI: Fixing UI Display to look a little better

## 5-14-16
- Backend: Cleaning up Code an extra Data.
- Backend: Fixed Email Sending, Moved to AWS SES.
- Backend: Had to fix `source` table causing conflict with Phalcon.

## 2-1-16
- Frontend: Working on improving small things with the UI
- Code: Mainly back-end organization and code cleanup.

## 1-12-16
- Servers: Rebuilt and Running new Production Servers, this took a long time.
- Servers: Deployed two new Development Servers.
- Servers: Error Logging Improved for bug detection.
- Payment: Fixed Stripe, using Native Stripe -- There is a bug in Omni-Pay stripe latest.
- Payment: Fixed, Paypal working due to un-logged Product error (Server logging improved on new OS).
- UI: Working minor UI changes
- Courses: Working on a new course possibly titled Ubuntu-ify, 10 videos complete so far.

## 1-4-16
- Servers: Dual servers running to prevent outages, host was DDoS'd for 10 days.
- Packages: Updating packages broke things, Video Streaming is fixed, reverted to previous AWS API.
- Payment: Currently Down.

## 12-31-15
- Added: Notification footer incase of any known issues.
- Updated: Fixing Facebook SDK Problems. This took a while!
- Updated Privacy: The Facebook API no longer allows `username` so rather than use your full-name display it will be `first_name`+`last_initial`.
- Updated: Facebook SDK Update

- Fixed: Broken Template Include
- Fixed: Broken Product Page with new Facebook SDK

## 11-12-15
- Updated: FlowPlayer Latest
- In Progress: Devtools area (http://jream.com/devtools/utf8chars)

## 5-27-15
- Course: Working on Django course, about 5 hours of content or more is complete. It's a big one!
- Update: FlowPlayer is now version 6.
- GUI: The skin for Flowplayer was changed from minimalist to something a tiny bit more interesting. Moved off the S3 onto server to fix button display issues.
- Server: Minor Server Cleanup :)

## 4-28-05
- Preparing: Live Sessions Beta Launch Soon
- Added: Consulting Service
- Internal: Re-organized include structure

## 4-11-05
- Downtime: 20 minutes downtime at 2:30pm EST
- Fixed: Was missing Phalcon Incubator Depdencies which were only in composers dev-requirements, rather than requirements. Redis wasn't caching with a missing Class.

## 03-25-15
- GUI: Updates has been removed from Dashboard and added to the footer
- Feature: Promotional Code Offers are in development

## 03-15-15
- GUI: Many minor Display Changes, SASS Minification.
- Feature: YouTube Video section added for free videos.

## 02-09-15
- Login: Implementing Google+, currently in debug mode.
- Email: Implementing a simple system internally to send updates.
- Course: Still working on Django course, it's a lot of work :)

## 01-19-15
- Flat Logo used, Collapsed icon only.
- GUI Changes.
- Course: Working on Django Course and Preparing a Live Course.

## 01-07-15
- Happy new years, working on a new Django Course.
- Downtime: 35 minutes downtime at 2:30AM EST due to the new Facebook API not ready for JREAM.

## 12-05-15
- Downtime: 10 minute downtime, had to migrate servers to another region.

## 11-25-14
- Re-issued SSL Certificate
- Removed any POODLE threat
- Forward Secrecy Supported
- Qualys SSL Labs Report:
    - Overall Rating: **A**
    - Certificate: 100
    - Protocol Support: 95
    - Key Exchange: 80
    - Cipher Strength: 90

## 11-09-14
- Feature: Flash Fallback Enabled if MP4 is not available
- Third-Party Bug: Fullscreen Issues with player and MP4 @ https://flowplayer.org/docs/known-issues.html
- Mobile: Still working with WebM Format, there is not an RTP for V-8 codecs on AWS. Looking into HTTPS Streaming.

## 11-02-14
- Mobile: Working towards Mobile Streaming in WebM format, content took 2 days to convert. Still have difficulty with RTMP and WebM Signed URLs.

## 10-29-14
- Feature: Working on a Quiz section.
- Lacking: Not much time to work on everything from Copywriting, UI, Server, System, Courses.

## 10-5-14
- Copywriting: Discover Area Simplified with bullet lists.
- UI: Adjusted Responsive UI for front-end.
- UI: Home, Disover, Login, and Register are now more modern.
- System: Trying out Varnish for Caching.
- Bugs: Fixed a broken link (Thanks for letting me know)

## 10-2-14
- UI: The UI is going through changes slowly. It is being tested before deployment, if however you encounter a bug please email `hello[at]jream.com`

## 9-26-14
- Courses: Published Redis Course
- Bug: Fixed Sandbox mode in PayPal on Live Environment (missed a variable)
- OS: Updated OS Distribution
- Security: Updated Bash Vulnerability "Shellshock"
- App: Making the App Multi-Module for expansion
- App: Looking for a better front-end solution

## 8-26-14
- Courses: Primarily Developing on the Redis Course.
- Internal: Refactoring parts of the JREAM Core to reduce duplicated code.
- Internal: Cleaning the Database of unused sections.
- Cancelled: Affiliation will not be available due to Tax Reasons and JREAM will never store personal information.

## 7-29-14
- UI: Application now utilizes Gulp and Bower.
- UI: Site design in the works.
- Copywriting: Building a Discover Area.

## 6-29-14
- Course: HTML & CSS Added
- UI: Site went through overhaul of UI Elements and color changes.

## 5-13-14
- Internal: All Login/Register/Password have been moved to a user controller
- Internal: Sessions had to be reset to apply changes, you may have been logged out once.

## 5-7-14
- UI: Font size Increased
- UI: Working on white space improvements

## 4-28-14
- Facebook Login: Your alias/username will automatically update if it's different when you login.

## 4-27-14

- UI: There have been many minor responsive improvements.
- Security: Changing your password now requires you to confirm your previous password
incase you left your computer on and someone accessed it.
- Failsafe: A backup server was setup if and when JREAM gives a non-200 HTTP Response.

- Development (Forum): A forum is being built to replace the Questions Area.
- Development (Affiliate): In the works arranging an affiliate system


## 4-15-14
- Heartbleed Fix: There was 8 hours downtime to prevent any potential heartbleed problems when it became news.
    - OpenSSL Updated.
    - System Upgraded.
    - Server credentials changed.
    - SSL Certficates were re-issued.
    - No personal information is stored at JREAM, it's on 3rd-party enterprise servers such as Stripe. There is nothing to to leak, but security matters.

