<?php 
require_once("includes/header.php");
require_once("includes/classes/VideoPlayer.php");
require_once("includes/classes/VideoInfoSection.php");
require_once("includes/classes/CommentSection.php");
require_once("includes/classes/Comment.php");

if(!isset($_GET["id"])){
	echo "No url passed into page.";
	exit();
}

$video = new Video($con, $_GET["id"], $userLoggedInObj);
$video->incrementViews();
?>
<script src="js/videoPlayerActions.js"></script>
<script src="js/commonActions.js"></script>
<script src="js/userActions.js"></script>
<script src="js/commentActions.js"></script>

<section class="post-details-area mb-80">
	<div class="container">
		<div class="row justify-content-center" style="margin-top: 20px;">
			<div class="col-12 col-lg-8">
				<div class="post-details-content d-flex">
					<div class="blog-content">
						<div class="post-content mt-1">
							<div class="single-video-area">

								<?php $videoPlayer = new VideoPlayer($video); echo $videoPlayer->create(true); ?>

							</div>
							<p class="post-title mb-2 noselect"><?php echo $video->getTitle(); ?></p>

							<blockquote class="vizew-blockquote">
								<p class="noselect"><?php echo $video->getDescription(); ?></p>

								<div class="post-tags">
									<ul>
										<li><a><?php echo $video->getCategory(); ?></a></li>
									</ul>
								</div>
							</blockquote>

							<div class="d-flex justify-content-between mb-15">
								
								<?php $videoPlayer = new VideoInfoSection($con, $video, $userLoggedInObj); echo $videoPlayer->create(); ?>
									
							</div>

							<div class="vizew-post-author d-flex align-items-center py-2">

								<?php $videoPlayer = new VideoInfoSection($con, $video, $userLoggedInObj); echo $videoPlayer->createSecondary(); ?>

								<?php $videoPlayer = new VideoInfoSection($con, $video, $userLoggedInObj); echo $videoPlayer->createThird(); ?>

							</div>
						</div>

						<div class="comment_area clearfix mb-20">
							<div class="section-heading style-2">
								<h4>Comments</h4>
								<div class="line"></div>
							</div>

							<?php $commentSection = new CommentSection($con, $video, $userLoggedInObj); echo $commentSection->create(); ?>

						</div>

					</div>
				</div>
			</div>

			<div class="col-12 col-md-6 col-lg-4">
				<div class="sidebar-area">

						<?php $videoGrid = new VideoGrid($con, $userLoggedInObj); echo $videoGrid->create(null, null, false); ?>

                    <div class="single-widget add-widget mb-20">
                        <a href="https://github.com/nabilfarhann"><img src="img/ads.jpg" alt=""></a>
                    </div>

                        
                    <!-- <div class="single-widget youtube-channel-widget mb-20">
                        <div class="section-heading style-3 mb-20">
                            <h4>Hot Channels</h4>
                            <div class="line"></div>
                        </div>

                        <div class="single-youtube-channel d-flex align-items-center">
                            <div class="youtube-channel-thumbnail">
                                <img src="img/bg-img/25.jpg" alt="">
                            </div>
                            <div class="youtube-channel-content">
                                <a href="single-post.html" class="channel-title">Music Channel</a>
                                <a href="#" class="btn subscribe-btn"><i class="fa fa-play-circle-o" aria-hidden="true"></i> Subscribe</a>
                            </div>
                        </div>

                        <div class="single-youtube-channel d-flex align-items-center">
                            <div class="youtube-channel-thumbnail">
                                <img src="img/bg-img/26.jpg" alt="">
                            </div>
                            <div class="youtube-channel-content">
                                <a href="single-post.html" class="channel-title">Trending Channel</a>
                                <a href="#" class="btn subscribe-btn"><i class="fa fa-play-circle-o" aria-hidden="true"></i> Subscribe</a>
                            </div>
                        </div>

                        <div class="single-youtube-channel d-flex align-items-center">
                            <div class="youtube-channel-thumbnail">
                                <img src="img/bg-img/27.jpg" alt="">
                            </div>
                            <div class="youtube-channel-content">
                                <a href="single-post.html" class="channel-title">Travel Channel</a>
                                <a href="#" class="btn subscribe-btn"><i class="fa fa-play-circle-o" aria-hidden="true"></i> Subscribe</a>
                            </div>
                        </div>

                        <div class="single-youtube-channel d-flex align-items-center">
                            <div class="youtube-channel-thumbnail">
                                <img src="img/bg-img/28.jpg" alt="">
                            </div>
                            <div class="youtube-channel-content">
                                <a href="single-post.html" class="channel-title">Sport Channel</a>
                                <a href="#" class="btn subscribe-btn"><i class="fa fa-play-circle-o" aria-hidden="true"></i> Subscribe</a>
                            </div>
                        </div>

                        <div class="single-youtube-channel d-flex align-items-center">
                            <div class="youtube-channel-thumbnail">
                                <img src="img/bg-img/29.jpg" alt="">
                            </div>
                            <div class="youtube-channel-content">
                                <a href="single-post.html" class="channel-title">TV Show Channel</a>
                                <a href="#" class="btn subscribe-btn"><i class="fa fa-play-circle-o" aria-hidden="true"></i> Subscribe</a>
                            </div>
                        </div>
                    </div> -->

                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once("includes/footer.php"); ?>