<?php

class VideoPlayer{

	private $video;

	public function __construct($video){
		$this->video = $video;
	}

	public function create($autoplay){

		if($autoplay){
			$autoplay = "autoplay";
		} else{
			$autoplay = "";
		}

		$filePath = $this->video->getFilePath();

		//return "<iframe src='$filePath' allow='accelerometer; $autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";

		//or

		return "<video class='videoPlayer' oncontextmenu='return false;' controls $autoplay id='vid'>
					<source src='$filePath'>
					Your browser does not support this video
				</video>";
	}
}

?>