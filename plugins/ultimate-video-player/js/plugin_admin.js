(function ($) {
    $(document).ready(function () {

	var json_str = options.replace(/&quot;/g, '"');
	var selected;
	
	options = jQuery.parseJSON(json_str);
	//OPTIONS//
	addOption("name", "text", "Player name","");
	addOption("videoPlayerWidth", "text", "Total player width [px]",746);
	addOption("videoPlayerHeight", "text", "Total player height [px]",420);
	addOption("responsive", "checkbox", "Responsive",false);
	addOption("allowSkip", "checkbox", "Allow user to skip ad",false);
	addOption("videoPlayerShadow", "dropdown", "Select player shadow","Effect1", ["Effect1", "Effect2", "Effect3", "Effect4", "Effect5", "Effect6", "Off"]);

	/* addOption("playlist", "checkbox", "Show playlist",true); */
	addOption("playlist", "dropdown", "Playlist","Right playlist", ["Right playlist","Bottom playlist", "Off"]);
	addOption("skinPlaylist", "dropdown", "Select playlist skin","Default", ["Default", "Classic", "Minimal", "Transparent", "Silver"]);
	addOption("skinPlayer", "dropdown", "Select HTML5 player skin","Default", ["Default", "Classic", "Minimal", "Transparent", "Silver"]);
	addOption("youtubeSkin", "dropdown", "Select YouTube skin","dark", ["dark", "light"]);
	addOption("youtubeColor", "dropdown", "Select YouTube color","red", ["red", "white"]);
	addOption("vimeoColor", "text", "Vimeo player color","00adef");
	addOption("youtubeShowRelatedVideos", "dropdown", "Show related YouTube videos", "Yes", ["Yes","No"]);
	/* addOption("videoAdShow", "checkbox", "Video pre-roll ad",false);  */
	addOption("onFinish", "dropdown", "On video finish","playNextOnFinish", ["Play next video","Restart video", "Stop video"]);
    addOption("autoplay", "checkbox", "Autoplay",false);
    addOption("loadRandomVideoOnStart", "checkbox", "Random video on page load",false);
	addOption("playVideosRandomly", "checkbox", "Shuffle videos",false);
	addOption("posterImg", "selectImage", "Poster image","");
	addOption("rightClickMenu", "checkbox", "Right-click menu",true);
	addOption("autohideControls", "text", "Auto hide controls (sec)",4);
	addOption("fullscreen", "dropdown", "Fullscreen","Fullscreen native", ["Fullscreen native","Fullscreen browser"]);
	//addOption("playerDirection", "dropdown", "Player orientation","Left to right (default)", ["Left to right (default)","Right to left (eastern countries)"]);
    addOption("nowPlayingText", "dropdown", "Show now playing title","Yes", ["Yes","No"]);
	addOption("shareShow", "dropdown", "Use social share","Yes", ["Yes","No"]);
	addOption("facebookLink", "text", "Facebook","http://codecanyon.net/");
	addOption("twitterLink", "text", "Twitter","http://codecanyon.net/");
	addOption("googleplusLink", "text", "Google +","http://codecanyon.net/");
	/* addOption("myspaceLink", "text", "Myspace","http://codecanyon.net/");
	addOption("wordpressLink", "text", "Wordpress","http://codecanyon.net/");
	addOption("linkedinLink", "text", "Linkedin","http://codecanyon.net/");
	addOption("flickrLink", "text", "Flickr","http://codecanyon.net/");
	addOption("bloggerLink", "text", "Blogger","http://codecanyon.net/");
	addOption("deliciousLink", "text", "Delicious","http://codecanyon.net/");
	addOption("mailLink", "text", "Mail","http://codecanyon.net/"); */
	addOption("logoShow", "dropdown", "Logo","Yes", ["Yes","No"]);
	addOption("logoPath", "selectImage", "Select logo image","");
	addOption("logoPosition", "dropdown", "Logo position","bottom-right", ["bottom-right","bottom-left"]);
	addOption("logoClickable", "dropdown", "Logo clickable","Yes", ["Yes","No"]);
	addOption("logoGoToLink", "text", "Logo redirect to URL","http://codecanyon.net/");
	addOption("embedShow", "dropdown", "Use embed code","Yes", ["Yes","No"]);
	addOption("embedCodeSrc", "textarea", "Embed code iframe src","http://yourwebsite.com/player/deploy/index.html");
	addOption("embedCodeW", "text", "Embed code iframe width","746");
	addOption("embedCodeH", "text", "Embed code iframe height","420");
	
	addOptionRight("videoType", "dropdown", "Select video player type","- Select player type -", ["- Select player type -", "HTML5", "youtube", "vimeo"]);

	$('.postbox .hndle').click(function(e){
		$(this).parent().toggleClass("closed")
	});
	$('.postbox .handlediv').click(function(e){
		$(this).parent().toggleClass("closed")
	});
	function addOption(name,type,desc,defaultValue,values){

		var table = $("#player-options-table");
		var tableBody = table.find('tbody');
		var row = $('<tr valign="top"  class="field-row"></tr>').appendTo(tableBody);
		var th = $('<th scope="row">'+desc+'</th>').appendTo(row);
		var td = $('<td></td>').appendTo(row);

		switch(type){
			case "text":
				var input = $('<input type="text" name="'+name+'"/>').appendTo(td);
				if(typeof(options[name]) != 'undefined'){
					input.attr("value",options[name]);
				}else {
					input.attr('value',defaultValue);
				}
				break;
			case "textarea":
				// var a = stripslashes(options[name]);
				// var b = stripslashes(defaultValue);
			    var textarea = $('<textarea type="text" name="'+name+'" cols=45" rows="1"></textarea>').appendTo(td);
				if(typeof(options[name]) != 'undefined'){
					textarea.attr("value",options[name]);
					// textarea.attr("value",a);
				}else {
					textarea.attr('value',defaultValue);
					// textarea.attr('value',b);
				}
				break;
			case "checkbox":
				var inputHidden = $('<input type="hidden" name="'+name+'" value="false"/>').appendTo(td);
				var input = $('<input type="checkbox" name="'+name+'" value="true"/>').appendTo(td);
				if(typeof(options[name]) != 'undefined'){
					input.attr("checked",options[name]);
				}else {
					input.attr('checked',defaultValue);
				}
				break;
			case "selectImage":
				var input = $('<input type="text" name="'+name+'"/><a class="select-image-button button-secondary button80" href="#">Select image</a>').appendTo(td);
				if(typeof(options[name]) != 'undefined'){
					input.attr("value",options[name]);
				}else {
					input.attr('value',defaultValue);
				}
				break;
			case "dropdown":
				var select = $('<select name="'+name+'">').appendTo(td);
				for ( var i = 0; i < values.length; i++ )
				{
					var option = $('<option name="'+name+'" value="'+values[i]+'">'+values[i]+'</option>').appendTo(select);
					if(typeof(options[name]) != 'undefined')
					{
						if(options[name] == values[i])
						{
							option.attr('selected','true');
						}
					}
					else if(defaultValue == values[i])
					{
						option.attr('selected','true');
					}
				}
				break;
		}

	}
	function addOptionRight(name,type,desc,defaultValue,values){

		var table = $("#player-options-table-right");
		var tableBody = table.find('tbody');
		var row = $('<tr valign="top"  class="field-row"></tr>').appendTo(tableBody);
		var th = $('<th scope="row">'+desc+'</th>').appendTo(row);
		var td = $('<td></td>').appendTo(row);

		switch(type){
			case "dropdown":
				var select = $('<select id="type" name="'+name+'">').appendTo(td);
				for ( var i = 0; i < values.length; i++ )
				{
					var option = $('<option name="'+name+'" value="'+values[i]+'">'+values[i]+'</option>').appendTo(select);
					if(typeof(options[name]) != 'undefined')
					{
						if(options[name] == values[i])
						{
							option.attr('selected','true');
						}
					}
					else if(defaultValue == values[i])
					{
						option.attr('selected','true');
					}
				}
				break;
		}

	}
	$('#type').change(function() {
		selected = $('#type option:selected').val();
	});
	selected = $('#type option:selected').val();
	// options

	//for all videos in  options.videos create video
	for(var i= 0; i < options.videos.length; i++){
		var video = options.videos[i];
		var videosContainer = $("#videos-container");
		switch(options.videoType){
			case "HTML5":
                var videoItem = createVideoHtml_html5("videos["+i+"]", i, video.title, /*video.videoType*//*, video.youtubeID, video.vimeoID,*/ video.mp4, /*video.webm,*/ video.videoAdShow, video.videoAdGotoLink,video.mp4AD, video.webmAD, video.description, video.thumbImg, video.popupImg, video.popupAdShow, video.popupAdStartTime, video.popupAdEndTime, video.popupAdGoToLink, video.info, video.textAdShow,video.textAd,video.textAdStartTime,video.textAdEndTime,video.textAdGoToLink);
                videoItem.appendTo(videosContainer);
				break;
			case "youtube":
                var videoItem = createVideoHtml_youtube("videos["+i+"]", i, video.title /*video.videoType*/, video.youtubeID/*, video.vimeoID*/, video.mp4, /*video.webm,*/ video.videoAdShow, video.videoAdGotoLink,video.mp4AD, video.webmAD, video.description, video.thumbImg, video.popupImg, video.popupAdShow, video.popupAdStartTime, video.popupAdEndTime, video.popupAdGoToLink/*, video.info*/,video.textAdShow,video.textAd,video.textAdStartTime,video.textAdEndTime,video.textAdGoToLink);
                videoItem.appendTo(videosContainer);
				break;
			case "vimeo":
                var videoItem = createVideoHtml_vimeo("videos["+i+"]", i, video.title /*video.videoType*//*, video.youtubeID*/, video.vimeoID, video.mp4, /*video.webm,*/ video.videoAdShow, video.videoAdGotoLink,video.mp4AD, video.webmAD, video.description, video.thumbImg, video.popupImg, video.popupAdShow, video.popupAdStartTime, video.popupAdEndTime, video.popupAdGoToLink/*, video.info*/,video.textAdShow,video.textAd,video.textAdStartTime,video.textAdEndTime,video.textAdGoToLink);
                videoItem.appendTo(videosContainer);
				break;
		}

//		var videoItem = createVideoHtml("videos["+i+"]", i, video.title /*video.videoType*/, video.youtubeID, video.vimeoID, video.mp4, video.webm, video.videoAdShow, video.videoAdGotoLink,video.mp4AD, video.webmAD, video.description, video.thumbImg, video.info);
//		videoItem.appendTo(videosContainer);
	}

	$(".tabs").tabs();
	$(".ui-sortable").sortable();
	addListeners();

	if ($('.video').length > 0) {
		// it exists
		var countVideos=videosContainer.find(".video").length-1;}
	else{
		var countVideos=-1;}

	$('#add-new-video-button').click(function (e) {

		e.preventDefault();
		
		if(selected=="- Select player type -")
			countVideos = -1;
		else
			countVideos=countVideos+1;

        switch(selected){
            case "HTML5":
                var videoItem = createVideoHtml_html5("videos["+countVideos+"]", countVideos, "title", /*"videoType",*/ /*"youtubeID", "vimeoID",*/ "mp4", /*"webm",*/ "videoAdShow","videoAdGotoLink","mp4AD", "webmAD", "description", "thumbImg", "popupImg", "popupAdShow", "popupAdStartTime", "popupAdEndTime", "popupAdGoToLink", "info", "textAdShow","textAd","textAdStartTime","textAdEndTime","textAdGoToLink");
                var videosContainer = $("#videos-container");
                videoItem.appendTo(videosContainer);
                break;
            case "youtube":
                var videoItem = createVideoHtml_youtube("videos["+countVideos+"]", countVideos, "title", /*"videoType",*/ "youtubeID",/*, "vimeoID",*/ "mp4", /*"webm",*/ "videoAdShow","videoAdGotoLink","mp4AD", "webmAD", "description", "thumbImg", "popupImg", "popupAdShow", "popupAdStartTime", "popupAdEndTime", "popupAdGoToLink"/*, "info"*/, "textAdShow","textAd","textAdStartTime","textAdEndTime","textAdGoToLink");
                var videosContainer = $("#videos-container");
                videoItem.appendTo(videosContainer);
                break;
            case "vimeo":
                var videoItem = createVideoHtml_vimeo("videos["+countVideos+"]", countVideos, "title", /*"videoType",*/ /*"youtubeID",*/ "vimeoID", "mp4", /*"webm",*/ "videoAdShow","videoAdGotoLink","mp4AD", "webmAD", "description", "thumbImg", "popupImg", "popupAdShow", "popupAdStartTime", "popupAdEndTime", "popupAdGoToLink"/*, "info"*/, "textAdShow","textAd","textAdStartTime","textAdEndTime","textAdGoToLink");
                var videosContainer = $("#videos-container");
                videoItem.appendTo(videosContainer);
                break;
        }

		addListeners();

		return;
	});

	function addListeners(){
		$('.submitdelete').click(function () {
		/* console.log("delete"); */
			$(this).parent().parent().animate({
				'opacity': 0
			}, 100).slideUp(100, function () {
					$(this).remove();
				});
		});
		$('.select-image-button').click(function(e) {
			e.preventDefault();
			var imageURLInput = $(this).parent().find("input");
			var custom_uploader = wp.media({
				title: 'Select image',
				button: {
					text: 'Select'
				},
				multiple: false  // Set this to true to allow multiple files to be selected
			})
			.on('select', function() {
				var arr = custom_uploader.state().get('selection');
				var url = arr.models[0].attributes.url;
				imageURLInput.val(url);
			})
			.open();
		});
	}


    function createVideoHtml_html5(prefix,id,title,/*videoType,*//*youtubeID*//*vimeoID,*/mp4,/*webm,*/videoAdShow,videoAdGotoLink,mp4AD,webmAD,description,thumbImg,popupImg,popupAdShow,popupAdStartTime,popupAdEndTime,popupAdGoToLink,info,textAdShow,textAd,textAdStartTime,textAdEndTime,textAdGoToLink) {
		return $('<div id="'+id+'"class="video">'
					+'<text id="video-section-count"> HTML5 video '+id+'</text>'
						+ '<table class="form-table" id="player-videos-table">'
							+'<tbody>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Video title</th>'
									+'<td><input id="video-title" name="'+prefix+'[title]" type="text" placeholder="Enter video title" value="'+title+'" /></td>'
								+'</tr>'
//								+'<tr valign="top" class="field-row">'
//									+'<th scope="row">YouTube ID</th>'
//									+'<td><input id="youtube-id" name="'+prefix+'[youtubeID]" type="text" placeholder="Enter youtube ID" value="'+youtubeID+'" /></td>'
//								+'</tr>'
//								+'<tr valign="top" class="field-row">'
//									+'<th scope="row">Vimeo ID</th>'
//									+'<td><input id="youtube-id" name="'+prefix+'[vimeoID]" type="text" placeholder="Enter vimeo ID" value="'+vimeoID+'" /></td>'
//								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">MP4 video URL</th>'
									+'<td><input id="video-mp4" name="'+prefix+'[mp4]" type="text" placeholder="Enter .mp4 video URL" value="'+mp4+'" /></td>'
								+'</tr>'
								/* +'<tr valign="top" class="field-row">'
									+'<th scope="row">Webm video URL</th>'
									+'<td><input id="video-webm" name="'+prefix+'[webm]" type="text" placeholder="Enter .webm video URL" value="'+webm+'" /></td>'
								+'</tr>' */
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Video pre-roll show</th>'
									+'<td><input id="video-ad-show" name="'+prefix+'[videoAdShow]" type="text" placeholder="yes / no" value="'+videoAdShow+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Video pre-roll goto link</th>'
									+'<td><input id="video-ad-goto" name="'+prefix+'[videoAdGotoLink]" type="text" placeholder="go to link when ad clicked" value="'+videoAdGotoLink+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">MP4 pre-roll ad URL</th>'
									+'<td><input id="video-mp4-ad" name="'+prefix+'[mp4AD]" type="text" placeholder="Enter .mp4 pre-roll URL" value="'+mp4AD+'" /></td>'
								+'</tr>'
								/* +'<tr valign="top" class="field-row">'
									+'<th scope="row">Webm pre-roll ad URL</th>'
									+'<td><input id="video-webm-ad" name="'+prefix+'[webmAD]" type="text" placeholder="Enter .webm pre-roll URL" value="'+webmAD+'" /></td>'
								+'</tr>' */
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Description</th>'
									+'<td><textarea id="video-description" name="'+prefix+'[description]" type="text" cols="30" rows="2" placeholder="Enter video description">'+description+'</textarea></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Info</th>'
									+'<td><textarea id="video-info" name="'+prefix+'[info]" type="text" cols="30" rows="2" placeholder="Enter video info">'+info+'</textarea></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Thumbnail image</th>'
									+'<td><input id="image-path" name="'+prefix+'[thumbImg]" type="text" placeholder="Thumbnail URL" value="'+thumbImg+'" /><a class="select-image-button button-secondary button80" href="#">Select image</a></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad image</th>'
									+'<td><input id="popup-image-path" name="'+prefix+'[popupImg]" type="text" placeholder="pop-up Ad URL" value="'+popupImg+'" /><a class="select-image-button button-secondary button80" href="#">Select image</a></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad show</th>'
									+'<td><input id="popup-ad-show" name="'+prefix+'[popupAdShow]" type="text" placeholder="yes / no" value="'+popupAdShow+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad start time</th>'
									+'<td><input id="popup-ad-start-time" name="'+prefix+'[popupAdStartTime]" type="text" placeholder="pop-up ad start time" value="'+popupAdStartTime+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad end time</th>'
									+'<td><input id="popup-ad-end-time" name="'+prefix+'[popupAdEndTime]" type="text" placeholder="pop-up ad end time" value="'+popupAdEndTime+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad goto link</th>'
									+'<td><input id="popup-ad-goto-link" name="'+prefix+'[popupAdGoToLink]" type="text" placeholder="pop-up ad goto link" value="'+popupAdGoToLink+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Text ad show</th>'
									+'<td><input id="text-ad-show" name="'+prefix+'[textAdShow]" type="text" placeholder="yes / no" value="'+textAdShow+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Text ad</th>'
									+'<td><textarea id="video-textad" name="'+prefix+'[textAd]" type="text" cols="30" rows="2" placeholder="Enter text for ad">'+textAd+'</textarea></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Text ad start time</th>'
									+'<td><input id="text-ad-start-time" name="'+prefix+'[textAdStartTime]" type="text" placeholder="text ad start time" value="'+textAdStartTime+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Text ad end time</th>'
									+'<td><input id="text-ad-end-time" name="'+prefix+'[textAdEndTime]" type="text" placeholder="text ad end time" value="'+textAdEndTime+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Text ad goto link</th>'
									+'<td><input id="text-ad-goto-link" name="'+prefix+'[textAdGoToLink]" type="text" placeholder="text ad goto link" value="'+textAdGoToLink+'" /></td>'
								+'</tr>'
								+ '<div class="button-secondary submitbox deletediv"><a class="submitdelete deletion">Delete</a></div>'
							+'</tbody>'
						+'</table>'
					+ '<div class="sep"></div>'
				+ '</div>'
			);


	}    function createVideoHtml_youtube(prefix,id,title,/*videoType,*/youtubeID,/*vimeoID,*/mp4,/*webm,*/videoAdShow,videoAdGotoLink,mp4AD,webmAD,description,thumbImg,popupImg,popupAdShow,popupAdStartTime,popupAdEndTime,popupAdGoToLink,textAdShow,textAd,textAdStartTime,textAdEndTime,textAdGoToLink) {
		return $('<div id="'+id+'"class="video">'
					+'<text id="video-section-count"> YouTube video '+id+'</text>'
						+ '<table class="form-table" id="player-videos-table">'
							+'<tbody>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Video title</th>'
									+'<td><input id="video-title" name="'+prefix+'[title]" type="text" placeholder="Enter video title" value="'+title+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">YouTube ID</th>'
									+'<td><input id="youtube-id" name="'+prefix+'[youtubeID]" type="text" placeholder="Enter youtube ID" value="'+youtubeID+'" /></td>'
								+'</tr>'
//								+'<tr valign="top" class="field-row">'
//									+'<th scope="row">Vimeo ID</th>'
//									+'<td><input id="youtube-id" name="'+prefix+'[vimeoID]" type="text" placeholder="Enter vimeo ID" value="'+vimeoID+'" /></td>'
//								+'</tr>'
//								+'<tr valign="top" class="field-row">'
//									+'<th scope="row">Mp4 video URL</th>'
//									+'<td><input id="video-mp4" name="'+prefix+'[mp4]" type="text" placeholder="Enter .mp4 video URL" value="'+mp4+'" /></td>'
//								+'</tr>'
//								+'<tr valign="top" class="field-row">'
//									+'<th scope="row">Webm video URL</th>'
//									+'<td><input id="video-webm" name="'+prefix+'[webm]" type="text" placeholder="Enter .webm video URL" value="'+webm+'" /></td>'
//								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Video pre-roll show</th>'
									+'<td><input id="video-ad-show" name="'+prefix+'[videoAdShow]" type="text" placeholder="yes / no" value="'+videoAdShow+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Video pre-roll goto link</th>'
									+'<td><input id="video-ad-goto" name="'+prefix+'[videoAdGotoLink]" type="text" placeholder="go to link when ad clicked" value="'+videoAdGotoLink+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">MP4 pre-roll ad URL</th>'
									+'<td><input id="video-mp4-ad" name="'+prefix+'[mp4AD]" type="text" placeholder="Enter .mp4 pre-roll URL" value="'+mp4AD+'" /></td>'
								+'</tr>'
								/* +'<tr valign="top" class="field-row">'
									+'<th scope="row">Webm pre-roll ad URL</th>'
									+'<td><input id="video-webm-ad" name="'+prefix+'[webmAD]" type="text" placeholder="Enter .webm pre-roll URL" value="'+webmAD+'" /></td>'
								+'</tr>' */
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Description</th>'
									+'<td><textarea id="video-description" name="'+prefix+'[description]" type="text" cols="30" rows="2" placeholder="Enter video description">'+description+'</textarea></td>'
								+'</tr>'
//								+'<tr valign="top" class="field-row">'
//									+'<th scope="row">Info</th>'
//									+'<td><textarea id="video-info" name="'+prefix+'[info]" type="text" cols="30" rows="2" placeholder="Enter video info">'+info+'</textarea></td>'
//								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Thumbnail image</th>'
									+'<td><input id="image-path" name="'+prefix+'[thumbImg]" type="text" placeholder="Thumbnail URL" value="'+thumbImg+'" /><a class="select-image-button button-secondary button80" href="#">Select image</a></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad image</th>'
									+'<td><input id="popup-image-path" name="'+prefix+'[popupImg]" type="text" placeholder="pop-up Ad URL" value="'+popupImg+'" /><a class="select-image-button button-secondary button80" href="#">Select image</a></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad show</th>'
									+'<td><input id="popup-ad-show" name="'+prefix+'[popupAdShow]" type="text" placeholder="yes / no" value="'+popupAdShow+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad start time</th>'
									+'<td><input id="popup-ad-start-time" name="'+prefix+'[popupAdStartTime]" type="text" placeholder="pop-up ad start time" value="'+popupAdStartTime+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad end time</th>'
									+'<td><input id="popup-ad-end-time" name="'+prefix+'[popupAdEndTime]" type="text" placeholder="pop-up ad end time" value="'+popupAdEndTime+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad goto link</th>'
									+'<td><input id="popup-ad-goto-link" name="'+prefix+'[popupAdGoToLink]" type="text" placeholder="pop-up ad goto link" value="'+popupAdGoToLink+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Text ad show</th>'
									+'<td><input id="text-ad-show" name="'+prefix+'[textAdShow]" type="text" placeholder="yes / no" value="'+textAdShow+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Text ad</th>'
									+'<td><textarea id="video-textad" name="'+prefix+'[textAd]" type="text" cols="30" rows="2" placeholder="Enter text for ad">'+textAd+'</textarea></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Text ad start time</th>'
									+'<td><input id="text-ad-start-time" name="'+prefix+'[textAdStartTime]" type="text" placeholder="text ad start time" value="'+textAdStartTime+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Text ad end time</th>'
									+'<td><input id="text-ad-end-time" name="'+prefix+'[textAdEndTime]" type="text" placeholder="text ad end time" value="'+textAdEndTime+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Text ad goto link</th>'
									+'<td><input id="text-ad-goto-link" name="'+prefix+'[textAdGoToLink]" type="text" placeholder="text ad goto link" value="'+textAdGoToLink+'" /></td>'
								+'</tr>'
								+ '<div class="button-secondary submitbox deletediv"><a class="submitdelete deletion">Delete</a></div>'
							+'</tbody>'
						+'</table>'
					+ '<div class="sep"></div>'
				+ '</div>'
			);


	}
    function createVideoHtml_vimeo(prefix,id,title/*videoType,*//*youtubeID*/,vimeoID,mp4,/*webm,*/videoAdShow,videoAdGotoLink,mp4AD,webmAD,description,thumbImg,popupImg,popupAdShow,popupAdStartTime,popupAdEndTime,popupAdGoToLink,/*info,*/textAdShow,textAd,textAdStartTime,textAdEndTime,textAdGoToLink) {
		return $('<div id="'+id+'"class="video">'
					+'<text id="video-section-count"> Vimeo video '+id+'</text>'
						+ '<table class="form-table" id="player-videos-table">'
							+'<tbody>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Video title</th>'
									+'<td><input id="video-title" name="'+prefix+'[title]" type="text" placeholder="Enter video title" value="'+title+'" /></td>'
								+'</tr>'
//								+'<tr valign="top" class="field-row">'
//									+'<th scope="row">YouTube ID</th>'
//									+'<td><input id="youtube-id" name="'+prefix+'[youtubeID]" type="text" placeholder="Enter youtube ID" value="'+youtubeID+'" /></td>'
//								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Vimeo ID</th>'
									+'<td><input id="youtube-id" name="'+prefix+'[vimeoID]" type="text" placeholder="Enter vimeo ID" value="'+vimeoID+'" /></td>'
								+'</tr>'
//								+'<tr valign="top" class="field-row">'
//									+'<th scope="row">Mp4 video URL</th>'
//									+'<td><input id="video-mp4" name="'+prefix+'[mp4]" type="text" placeholder="Enter .mp4 video URL" value="'+mp4+'" /></td>'
//								+'</tr>'
//								+'<tr valign="top" class="field-row">'
//									+'<th scope="row">Webm video URL</th>'
//									+'<td><input id="video-webm" name="'+prefix+'[webm]" type="text" placeholder="Enter .webm video URL" value="'+webm+'" /></td>'
//								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Video pre-roll show</th>'
									+'<td><input id="video-ad-show" name="'+prefix+'[videoAdShow]" type="text" placeholder="yes / no" value="'+videoAdShow+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Video pre-roll goto link</th>'
									+'<td><input id="video-ad-goto" name="'+prefix+'[videoAdGotoLink]" type="text" placeholder="go to link when ad clicked" value="'+videoAdGotoLink+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">MP4 pre-roll ad URL</th>'
									+'<td><input id="video-mp4-ad" name="'+prefix+'[mp4AD]" type="text" placeholder="Enter .mp4 pre-roll URL" value="'+mp4AD+'" /></td>'
								+'</tr>'
								/* +'<tr valign="top" class="field-row">'
									+'<th scope="row">Webm pre-roll ad URL</th>'
									+'<td><input id="video-webm-ad" name="'+prefix+'[webmAD]" type="text" placeholder="Enter .webm pre-roll URL" value="'+webmAD+'" /></td>'
								+'</tr>' */
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Description</th>'
									+'<td><textarea id="video-description" name="'+prefix+'[description]" type="text" cols="30" rows="2" placeholder="Enter video description">'+description+'</textarea></td>'
								+'</tr>'
//								+'<tr valign="top" class="field-row">'
//									+'<th scope="row">Info</th>'
//									+'<td><textarea id="video-info" name="'+prefix+'[info]" type="text" cols="30" rows="2" placeholder="Enter video info">'+info+'</textarea></td>'
//								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Thumbnail image</th>'
									+'<td><input id="image-path" name="'+prefix+'[thumbImg]" type="text" placeholder="Thumbnail URL" value="'+thumbImg+'" /><a class="select-image-button button-secondary button80" href="#">Select image</a></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad image</th>'
									+'<td><input id="popup-image-path" name="'+prefix+'[popupImg]" type="text" placeholder="pop-up Ad URL" value="'+popupImg+'" /><a class="select-image-button button-secondary button80" href="#">Select image</a></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad show</th>'
									+'<td><input id="popup-ad-show" name="'+prefix+'[popupAdShow]" type="text" placeholder="yes / no" value="'+popupAdShow+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad start time</th>'
									+'<td><input id="popup-ad-start-time" name="'+prefix+'[popupAdStartTime]" type="text" placeholder="pop-up ad start time" value="'+popupAdStartTime+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad end time</th>'
									+'<td><input id="popup-ad-end-time" name="'+prefix+'[popupAdEndTime]" type="text" placeholder="pop-up ad end time" value="'+popupAdEndTime+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Pop-up ad goto link</th>'
									+'<td><input id="popup-ad-goto-link" name="'+prefix+'[popupAdGoToLink]" type="text" placeholder="pop-up ad goto link" value="'+popupAdGoToLink+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Text ad show</th>'
									+'<td><input id="text-ad-show" name="'+prefix+'[textAdShow]" type="text" placeholder="yes / no" value="'+textAdShow+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Text ad</th>'
									+'<td><textarea id="video-textad" name="'+prefix+'[textAd]" type="text" cols="30" rows="2" placeholder="Enter text for ad">'+textAd+'</textarea></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Text ad start time</th>'
									+'<td><input id="text-ad-start-time" name="'+prefix+'[textAdStartTime]" type="text" placeholder="text ad start time" value="'+textAdStartTime+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Text ad end time</th>'
									+'<td><input id="text-ad-end-time" name="'+prefix+'[textAdEndTime]" type="text" placeholder="text ad end time" value="'+textAdEndTime+'" /></td>'
								+'</tr>'
								+'<tr valign="top" class="field-row">'
									+'<th scope="row">Text ad goto link</th>'
									+'<td><input id="text-ad-goto-link" name="'+prefix+'[textAdGoToLink]" type="text" placeholder="text ad goto link" value="'+textAdGoToLink+'" /></td>'
								+'</tr>'
								+ '<div class="button-secondary submitbox deletediv"><a class="submitdelete deletion">Delete</a></div>'
							+'</tbody>'
						+'</table>'
					+ '<div class="sep"></div>'
				+ '</div>'
			);


	}
	
	/* console.log("selected",$("#player-videos-table").find("#select-video-type").val("youtube")) */
	/* $("#player-videos-table").find("#select-video-type").val("HTML5") */
	/*$("#player-videos-table").find("#select-video-type").change(function(){ 
			console.log("changed", $("#select-video-type").val())
			if($("#select-video-type").val()=="HTML5"){
				console.log("set html5 as val")
				$("#player-videos-table").find("#select-video-type").val("HTML5")
			}
			else if($("#select-video-type").val()=="youtube"){
				console.log("set youtube as val")
				$("#player-videos-table").find("#select-video-type").val("youtube")
			}
		});*/
		

		
	});
})(jQuery);

function stripslashes (str) {
  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: Ates Goral (http://magnetiq.com)
  // +      fixed by: Mick@el
  // +   improved by: marrtins
  // +   bugfixed by: Onno Marsman
  // +   improved by: rezna
  // +   input by: Rick Waldron
  // +   reimplemented by: Brett Zamir (http://brett-zamir.me)
  // +   input by: Brant Messenger (http://www.brantmessenger.com/)
  // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
  // *     example 1: stripslashes('Kevin\'s code');
  // *     returns 1: "Kevin's code"
  // *     example 2: stripslashes('Kevin\\\'s code');
  // *     returns 2: "Kevin\'s code"
  return (str + '').replace(/\\(.?)/g, function (s, n1) {
	switch (n1) {
	case '\\':
	  return '\\';
	case '0':
	  return '\u0000';
	case '':
	  return '';
	default:
	  return n1;
	}
  });
}