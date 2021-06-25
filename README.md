# Vidhub
Video sharing website (YouTube clone)

![Vidhub](https://github.com/nabilfarhann/Vidhub/blob/master/img/Vidhub.png?raw=true)

:star2::star2: **Don't forget to give this repo a star!** :star2::star2:

## Description
Vidhub is a video sharing website done using PHP and MySQL. This system also integrate with Cloudinary, Cloudinary provides a cloud-based image and video management solution. All videos that upload to Vidhub will be stored to Cloudinary.

***Instruction on usage:***
````
1. Clone or download this repositories
2. Unzip and open with TextEditor (VS Code)
3. Create MySQL database and name it to 'vidhub'
4. Change host, username and password of MySQL database in 'includes\config.php'
5. Apply and Upload vidhub.sql to 'vidhub' database
6. Create Cloudinary account and enable unsigned uploading
7. Insert unsigned uploading name and cloud name to Cloudinary widget in 'upload.php'
8. Run!
````

***You can download latest version of FFMPEG Static file for conversion of video and to generates Thumbnail***
````
( FFMPEG available for Linux, Windows and Mac )
Choose FFMPEG Static files based on your operating system, Download and replace it in ffmpeg folder
````

***If you wish to process video or upload it to localhost folder***
````
1. Go to 'includes/classes/' and rename 'VideoProcessor-localhost' to 'VideoProcessor' (Ovewrite)
2. You need to create 3 folder,
  - uploads (top)
    - videos (inside uploads folder)
      - thumbnails (inside videos folder)
````

If you have anything to ask or would like to share your opinion, can email me:
> **__nabilfarhan.dev@gmail.com__**

## List of Features
- [x] Upload / Delete / Edit Video
- [x] Comment / Like / Dislike Video
- [x] Profile Page / Subscribe
- [x] Comment and Delete Comment
- [x] Search Video
- [x] Total views / Upload Date
- [x] Trending / Subscription Pages
