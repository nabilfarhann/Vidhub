function postComment(button, postedBy, videoId, replyTo, containerClass){
	var textarea = $(button).siblings("textarea");
	var commentText = textarea.val();
	textarea.val("");

	if(commentText){

		$.post("ajax/postComment.php", { commentText: commentText, postedBy: postedBy, videoId: videoId, responseTo: replyTo })
		.done(function(comment){
			
			if(!replyTo){

				$("." + containerClass).prepend(comment);

			}else{
				$(button).parent().siblings("." + containerClass).append(comment);
			}

		});

	}else{
		alert("You cant post an empty comment");
	}
}

function toggleReply(button){
	var parent = $(button).closest(".itemContainer");
	var commentForm = parent.find(".commentForm").first();

	commentForm.toggleClass("hidden");
}

function likeComment(commentId, button, videoId){
	$.post("ajax/likeComment.php", {commentId: commentId, videoId: videoId})
	.done(function(numToChange){
		
		var likeButton = $(button);
		var dislikeButton = $(button).siblings(".dislikeButton");

		likeButton.addClass("active");
		dislikeButton.removeClass("active");

		var likesCount = $(button).siblings(".likesCount");
		updateLikesValue(likesCount, numToChange);

		if(numToChange < 0){
			likeButton.removeClass("active");
			likeButton.find("img:first").attr("src", "img/icons/thumb-up.png");
	}else{
			likeButton.find("img:first").attr("src", "img/icons/thumb-up-active.png");
		}

		dislikeButton.find("img:first").attr("src", "img/icons/thumb-down.png");
	});
}

function dislikeComment(commentId, button, videoId){
	$.post("ajax/dislikeComment.php", {commentId: commentId, videoId: videoId})
	.done(function(numToChange){
		
		var dislikeButton = $(button);
		var likeButton = $(button).siblings(".likeButton");

		dislikeButton.addClass("active");
		likeButton.removeClass("active");

		var likesCount = $(button).siblings(".likesCount");
		updateLikesValue(likesCount, numToChange);

		if(numToChange > 0){
			dislikeButton.removeClass("active");
			dislikeButton.find("img:first").attr("src", "img/icons/thumb-down.png");
	}else{
			dislikeButton.find("img:first").attr("src", "img/icons/thumb-down-active.png");
		}

		likeButton.find("img:first").attr("src", "img/icons/thumb-up.png");
	});
}

function updateLikesValue(element, num){
	var likesCountVal = element.text() || 0;
	element.text(parseInt(likesCountVal) + parseInt(num));
}

function getReplies(commentId, button, videoId){
	$.post("ajax/getCommentReplies.php", { commentId: commentId, videoId: videoId })
	.done(function(comments){
		var replies = $("<div>").addClass("repliesSection");
		replies.append(comments);

		$(button).replaceWith(replies);
	});
}