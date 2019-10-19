<?php 
require_once("includes/header.php");
require_once("includes/classes/VideoDetailsFormProvider.php");

if(!$usernameLoggedIn){
    header ("Location: signIn");
}
?>

	<section class="contact-area mb-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <!-- Section Heading -->
                    <div class="section-heading style-2">
                    	<br>
                        <h4>Upload Video</h4>
                        <div class="line"></div>
                    </div>
                    <!-- Contact Form Area -->
                    <div class="contact-form-area mt-50">
                        <?php
                            $formProvider = new VideoDetailsFormProvider($con);
                            echo $formProvider->createUploadForm();
                        ?>
                    </div>

                    <div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModal" aria-hidden="true" data-backdrop="static" data-keyboard='false'>
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <img src="img/pleasewait2.gif">
                        </div>
                      </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <script src="https://widget.cloudinary.com/v2.0/global/all.js" type="text/javascript"></script>
    <script type="text/javascript">  
    document.getElementById("upload_widget_opener").addEventListener("click", function() {
        cloudinary.openUploadWidget({cloud_name: '**Insert your cloudinary cloud name**',upload_preset: '**Insert cloudinary unsigned name', sources: [
        "local",
        "url"
    ], styles: {
            palette: {
                window: "#222627",
                sourceBg: "#222627",
                windowBorder: "#DB4437",
                tabIcon: "#FFFFFF",
                inactiveTabIcon: "#8E9FBF",
                menuIcons: "#DB4437",
                link: "#DB4437",
                action: "#DB4437",
                inProgress: "#DB4437",
                complete: "#33ff00",
                error: "#EA2727",
                textDark: "#000000",
                textLight: "#FFFFFF"
            }, multiple:false,resource_type:'video',
            fonts: {
                default: null,
                "'Poppins', sans-serif": {
                    url: "https://fonts.googleapis.com/css?family=Poppins",
                    active: true
                }
            }
        }, thumbnailTransformation: { width: 300, height: 210}}, 
        function(error, result) { 

            if (result.event === "success") {
                console.log(result);
                console.log(result.info.secure_url);
                console.log(result.info.thumbnail_url);
                console.log(result.info.public_id);
                console.log(result.info.duration);
            }
            //console.log(error, result);
            //console.log(result[0].path);
            //console.log(result);

            var video_url = result.info.secure_url;
            var video_thumbnail = result.info.thumbnail_url;
            var cloud_id = result.info.public_id;
            var duration = result.info.duration;

            //console.log(result[0].thumbnail_url);
            //console.log(result[0].url);
            //console.log(result[0].public_id);

            //var video_url = result[0].url;
            //var video_thumbnail = result[0].thumbnail_url;
            //var cloud_id = result[0].public_id;

            if(error==null){
                if(result.event === "success"){
                $("#upload_widget_opener").hide();
                $("#video_url").val(video_url);
                $("#video_thumbnail").val(video_thumbnail);
                $("#cloud_id").val(cloud_id);
                $("#duration").val(duration);
            }
        }else{

        }
            

            });
    }, false);
    </script>



<?php require_once("includes/footer.php"); ?>
