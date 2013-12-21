MyYouTube
==========
An exciting project to create your own YouTube Clone where users can upload / delete their videos, view / rate other's videos and also have access to a single LiveStreaming using RTMP Protocol.


Technologies Used
=====
* Amazon S3 (Storing User Videos)
* Amazon EC2 (Running the Web Server)
* Amazon CloudFront (Content Distribution)
* JWPlayer (Video Player)
* HTML5 (Inbuilt Video Playing)
* Wowza Media Server (using Amazon CloudFormation) - for live streaming of video


Tasks completed
=====
* Application should be capable of operations of Upload, List View, Rating and Deletion (Deletion allowed only for the owner of the video)
* Videos are uploaded to an Amazon S3 Bucket. File type compatibility is checked else error is thrown.
* Option to delete a video
* Creation of the bucket using Amazon S3 API dyanmically. initial.php
* Streaming the video using CloudFront as CDN. (Both Web and RTMP Distribution)
* Creation of the cloudfront distribution using Amazon API dynamically. initial.php
* Streaming done using both JWPlayer (Flash and uses RTMP Distribution) and HTML5 (uses Web Distribution)
* Rating of Videos. One user can rate a video with only one value which can be modified by the user.
* Use of Amazon RDS to store the Rating details together with the videos details.
* Amazon EC2 server to host this website
* Phone Based Video Upload to S3 (through the HTML5 interface, since most smartphones support direct HTML5 uploads)
* Handled Live Streaming. (livestream.php) (Used an Amazon CloudFormation running Wowza Media Server to encode and compress the live video and distribute it through the Amazon CloudFront and then used JWPlayer to live stream the broadcast using the RTMP Playback URL provided in the Wowza Server)

Run the Project
====
Update the following paramters
* Google Authentication Paramters
* Amazon S3 Access Key and Client Secret Keys
* Amazon RDS Database URL, Username and Password

You are good to go !!!
