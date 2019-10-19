<?php
class VideoGrid{

	private $con, $userLoggedInObj;
	private $largeMode = false;
	private $gridClass2 = "single-widget mb-20 videoGrid";

	public function __construct($con, $userLoggedInObj){
		$this->con = $con;
		$this->userLoggedInObj = $userLoggedInObj;
	}

	public function create($videos, $title, $showFilter){

		if($videos == null){

			$gridItems = $this->generateItems();

		}else{
			$gridItems = $this->generateItemsFromVideos($videos);
		}

		$header = "";

		if($title != null){
			$header = $this->createGridHeader($title, $showFilter);
		}

		return "$header
				<div class='$this->gridClass2'>
					$gridItems
				</div>";
	}

	public function generateItems(){
		$query = $this->con->prepare("SELECT * FROM videos ORDER BY RAND() LIMIT 8");
		$query->execute();

		$elementsHtml = "";

		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$video = new Video($this->con, $row, $this->userLoggedInObj);
			$item = new VideoGridItem($video, $this->largeMode);
			$elementsHtml .= $item->create();
		}

		return $elementsHtml;
	}

	public function generateItemsFromVideos($videos){
		$elementsHtml = "";

		foreach($videos as $video){
			$item = new VideoGridItem($video, $this->largeMode);
			$elementsHtml .= $item->create();
		}
		return $elementsHtml;
	}

	public function createGridHeader($title, $showFilter){
		$filter = "";

        if($showFilter){
        	$link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        	$urlArray = parse_url($link);
        	$query = $urlArray["query"];

        	parse_str($query, $params);

        	unset($params["orderBy"]);

        	$newQuery = http_build_query($params);

        	$newUrl = basename($_SERVER["PHP_SELF"]) . "?" . $newQuery;

        	$filter = "<div class='right'>
							<span>Order by:</span>
							<a href='$newUrl&orderBy=uploadDate'>Upload date</a>
							<a href='$newUrl&orderBy=views'>Most viewed</a>
        				</div>";
        }

        return "<div class='videoGridHeader'>
                        <div class='left'>
                            $title
                        </div>
                        $filter
                    </div>";
	}

	public function createLarge($videos, $title, $showFilter){
		$this->gridClass2 .= " large";
		$this->largeMode = true;
		return $this->create($videos, $title, $showFilter);
	}
}


?>