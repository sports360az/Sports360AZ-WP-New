var PLAYER= PLAYER || {};


PLAYER.Playlist = function ($, video, options, mainContainer, element, preloader, myVideo, canPlay, click_ev, pw, hasTouch) {
    console.log("PLAYLIST.js")
    //constructor
    var self = this;

    this.VIDEO = video;
    this.element = element;
    this.canPlay = canPlay;
    this.CLICK_EV = click_ev;
    this.hasTouch = hasTouch;
    this.preloader = preloader;
    this.options = options;
    this.videoid = "VIDEOID";
    this.adStartTime = "ADSTARTTIME";
//    this.videoAdPlaying = false;
    this.videoAdPlayed = false;
    this.rand = Math.floor((Math.random() * (options.videos).length) + 0);
    var ytSkin = options.youtubeSkin;
    var ytColor = options.youtubeColor;
	var ytShowRelatedVideos;
	switch(options.youtubeShowRelatedVideos){
		case "Yes":
			ytShowRelatedVideos = 1;
		break;
		case "No":
			ytShowRelatedVideos = 0;
		break;
	}
    ytSkin.toString();
    ytColor.toString();
//    var players = new Array();

    this.playlist = $("<div />");
    this.playlistContent= $("<dl />");

    switch(options.playlist){
        case "Right playlist":
            this.playlist.attr('id', 'ult_vp_playlist');
            this.playlistContent.attr('id', 'ult_vp_playlistContent');
            break;
        case "Bottom playlist":
            this.playlist.attr('id', 'ult_vp_playlist_bottom');
            this.playlistContent.attr('id', 'ult_vp_playlistContent_bottom');
            break;
    }
    self.videos_array=new Array();
    self.item_array=new Array();

    this.ytWrapper = $('<div></div>');
    this.ytWrapper.attr('id', 'ult_vp_ytWrapper');
    if( self.element){
        self.element.append(self.ytWrapper);
    }

//    var numPlayers=0;
//    $(".videoplayer ").each(function() {
//        numPlayers=numPlayers+1;
//    });
//    console.log("players:",numPlayers)

    this.ytPlayer = $('<div></div>');
    this.ytPlayer.attr('id', 'ult_vp_ytPlayer');
    this.ytWrapper.append(this.ytPlayer);

//    self.ytWrapper.hide();

    this.vimeoWrapper = $('<div></div>');
    this.vimeoWrapper.attr('id', 'ult_vp_vimeoWrapper');
    if( self.element){
        self.element.append(self.vimeoWrapper);
    }

    $('#ult_vp_vimeoWrapper').html('<iframe id="vimeo_video" src="" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');


    var offsetL=0;
    var offsetT=0;

    document.addEventListener("eventYoutubeReady", onPlayerReady, false);
    /* window.addEventListener("eventYoutubeReady", onPlayerReady, false); */

    function onPlayerReady(eventYoutubeReady) {
        console.log("youtube ready")

        if(self.youtubePlayer!= undefined){
            var myTimer = setInterval(function(){
                if(self.youtubePlayer.getPlayerState()==1){
					if(self.videos_array[self.videoid].popupAdShow=="yes")
						self.VIDEO.enablePopup();
					if(self.videos_array[self.videoid].textAdShow=="yes")
						self.VIDEO.enableTextPopup();
                }
            },1000);
        }
//        if(options.videos[0].videoType=="youtube")
        if(options.videoType=="youtube")
        {
            self.VIDEO.removeHTML5elements();
            if(options.loadRandomVideoOnStart)
                self.youtubePlayer.cueVideoById(self.videos_array[self.rand].youtubeID);
            else
                self.youtubePlayer.cueVideoById(self.videos_array[0].youtubeID);
//            eventYoutubeReady.target.cuePlaylist({list: "RDy8KA4wp19XE"});//https://www.youtube.com/watch?v=y8KA4wp19XE&list=RDy8KA4wp19XE#t=0
/*********
            //===YOUTUBE PLAYLIST====//
            var playListURL = 'http://gdata.youtube.com/feeds/api/playlists/RDy8KA4wp19XE?v=2&alt=json&callback=?';
             //https://www.youtube.com/watch?v=y8KA4wp19XE&list=RDy8KA4wp19XE#t=0
             var videoURL= 'http://www.youtube.com/watch?v=';
             $.getJSON(playListURL, function(data) {
                var list_data="";
                $.each(data.feed.entry, function(i, item) {
                    var feedTitle = item.title.$t;
                    console.log(feedTitle)
                    var feedURL = item.link[1].href;
                    var fragments = feedURL.split("/");
                    var videoID = fragments[fragments.length - 2];
                    var url = videoURL + videoID;
                    var thumb = "http://img.youtube.com/vi/"+ videoID +"/default.jpg";
                    list_data += '<li><a href="'+ url +'" title="'+ feedTitle +'"><img alt="'+ feedTitle+'" src="'+ thumb +'"</a></li>';
                });
                $(list_data).appendTo("#ult_vp_playlistContent");
            });

            //===YOUTUBE CHANNEL====//
//            $.getJSON('http://gdata.youtube.com/feeds/api/users/benpearlmagic/uploads?alt=json', function(data) {
            //https://www.youtube.com/channel/USERNAME
            var channelURL = 'http://gdata.youtube.com/feeds/api/users/UCHqaLr9a9M7g9QN6xem9HcQ/uploads?alt=json&orderby=published';
            $.getJSON(channelURL, function(data) {
//                console.log(data);
                for(var i in data.feed.entry) {
                    console.log("Title : "+data.feed.entry[i].title.$t); // title
                    console.log("Description : "+data.feed.entry[i].content.$t); // description
                }
            });
*****/

            if(!this.hasTouch){
                if(options.autoplay)
                    (self.youtubePlayer).playVideo();
            }
            self.VIDEO.resizeAll();

            if(pw){
                if(self.videos_array[0].title!="Big Buck Bunny Trailer" && self.videos_array[0].title!="Sintel Trailer" && self.videos_array[0].title!="Oceans" && self.videos_array[0].title!="Photo Models" && self.videos_array[0].title!="Corporate Business" && self.videos_array[0].title!="Fashion Promo Gallery" && self.videos_array[0].title!="World Swimsuit Launch" && self.videos_array[0].title!="FTV Release - Fashion Photoshoot" && self.videos_array[0].title!="Victoria Secret Holiday Ad" && self.videos_array[0].title!="Fashion Promo Gallery"){
                    self.VIDEO.pw();
                    if(self.youtubePlayer!= undefined){
                        self.youtubePlayer.stopVideo();
                        self.youtubePlayer.clearVideo();
                        self.youtubePlayer.setSize(1, 1);
                    }
                }
            }
        }
    }
    function onPlayerStateChange(event) {
        var youtube_time = Math.floor(self.youtubePlayer.getCurrentTime());
        if(event.data === 0) {
            //ended
                self.randEnd = Math.floor((Math.random() * (options.videos).length) + 0);

                self.videoAdPlayed=false;
                self.videoid = parseInt(self.videoid)+1;//increase video id
                if (self.videos_array.length == self.videoid){
                    self.videoid = 0;
                }
                //play next on finish
//                if(options.playNextOnFinish)
                if(options.onFinish=="Play next video")
                {
                    switch(self.options.playlist){
                        case "Right playlist":
                            mainContainer.find(".ult_vp_itemSelected").removeClass("ult_vp_itemSelected").addClass("ult_vp_itemUnselected");//remove selected
                            if(options.playVideosRandomly)
                                $(self.item_array[self.randEnd]).removeClass("ult_vp_itemUnselected").addClass("ult_vp_itemSelected");//selected
                            else
                                $(self.item_array[self.videoid]).removeClass("ult_vp_itemUnselected").addClass("ult_vp_itemSelected");//selected
                            break;
                        case "Bottom playlist":
                            mainContainer.find(".ult_vp_itemSelected_bottom").removeClass("ult_vp_itemSelected_bottom").addClass("ult_vp_itemUnselected_bottom");//remove selected
                            if(options.playVideosRandomly)
                                $(self.item_array[self.randEnd]).removeClass("ult_vp_itemUnselected_bottom").addClass("ult_vp_itemSelected_bottom");//selected
                            else
                                $(self.item_array[self.videoid]).removeClass("ult_vp_itemUnselected_bottom").addClass("ult_vp_itemSelected_bottom");//selected
                            break;
                    }

//                    if(options.videos[self.videoid].videoType=="youtube")
                    if(options.videoType=="youtube")
                    {
                        self.VIDEO.closeAD();
                        self.videoAdPlayed=false;
                        self.ytWrapper.css({zIndex:501});
                        self.VIDEO.removeHTML5elements();
                        if(self.youtubePlayer!= undefined){
                            if(options.playVideosRandomly)
                                self.youtubePlayer.cueVideoById(self.videos_array[self.randEnd].youtubeID);
                            else
                                self.youtubePlayer.cueVideoById(self.videos_array[self.videoid].youtubeID);
                            self.youtubePlayer.setSize(element.width(), element.height());
                            if(!this.hasTouch){
                                (self.youtubePlayer).playVideo();
                            }
                        }

                    }
//                    else if(options.videos[self.videoid].videoType=="vimeo")
                    else if(options.videoType=="vimeo")
                    {
                        self.preloader.stop().animate({opacity:0},700,function(){$(this).hide()});
                        self.vimeoWrapper.css({zIndex:501});
                        if(self.CLICK_EV=="click")
                            if(options.playVideosRandomly)
                                document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[self.randEnd].vimeoID+"?autoplay=1?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
                            else
                                document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[self.videoid].vimeoID+"?autoplay=1?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
                        if(self.CLICK_EV=="touchend")
                            if(options.playVideosRandomly)
                                    document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[self.randEnd].vimeoID+"?autoplay=1?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
                            else
                                document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[self.videoid].vimeoID+"?autoplay=1?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;

                        self.VIDEO.removeHTML5elements();
                        self.ytWrapper.css({zIndex:0});
                        if(self.youtubePlayer!= undefined){
                            self.youtubePlayer.stopVideo();
                            self.youtubePlayer.clearVideo();
//                            self.youtubePlayer.setSize(1, 1);
                        }
                        addVimeoListeners();
                    }
//                    else if(options.videos[self.videoid].videoType=="HTML5")
                    else if(options.videoType=="HTML5")
                    {
                        self.ytWrapper.css({zIndex:0});
                        self.VIDEO.showHTML5elements();
                        if(self.youtubePlayer!= undefined){
                            self.youtubePlayer.stopVideo();
                            self.youtubePlayer.clearVideo();
//                            self.youtubePlayer.setSize(1, 1);
                        }


                        /*if(myVideo.canPlayType && myVideo.canPlayType('video/webm').replace(/no/, ''))
                        {
                            this.canPlay = true;
                            //                         alert(".webm can play" + this.canPlay);
                            self.video_path = self.videos_array[self.videoid].video_path_webm;
                            self.video_pathAD = self.videos_array[self.videoid].video_path_webmAD;
                        }
                        else */if(myVideo.canPlayType && myVideo.canPlayType('video/mp4').replace(/no/, ''))
                        {
                            this.canPlay = true;
                            //                            alert(".mp4 can play" + this.canPlay);
                            if(options.playVideosRandomly){
                                self.video_path = self.videos_array[self.randEnd].video_path_mp4;
                                self.video_pathAD = self.videos_array[self.randEnd].video_path_mp4AD;
                            }
                            else{
                                self.video_path = self.videos_array[self.videoid].video_path_mp4;
                                self.video_pathAD = self.videos_array[self.videoid].video_path_mp4AD;
                            }
                        }

                        self.VIDEO.resizeAll();
                        if(options.playVideosRandomly)
                            self.VIDEO.load(video_path, self.randEnd);
                        else
                            self.VIDEO.load(video_path, self.videoid);
                        self.VIDEO.play();

                        if(options.playVideosRandomly){
                            $(self.element).find(".ult_vp_infoTitle").html(self.videos_array[self.randEnd].title);
                            $(self.element).find(".ult_vp_infoText").html(self.videos_array[self.randEnd].info_text);
                            $(self.element).find(".ult_vp_nowPlayingText").html(self.videos_array[self.randEnd].title);
                        }
                        else{
                            $(self.element).find(".ult_vp_infoTitle").html(self.videos_array[self.videoid].title);
                            $(self.element).find(".ult_vp_infoText").html(self.videos_array[self.videoid].info_text);
                            $(self.element).find(".ult_vp_nowPlayingText").html(self.videos_array[self.videoid].title);
                        }
                    }
                }
//                else if(!options.playNextOnFinish)
                else if(options.onFinish=="Restart video")
                {
                    if(self.youtubePlayer!= undefined){
                        self.youtubePlayer.seekTo(0);
                        self.youtubePlayer.playVideo();
                    }

                }
                else if(options.onFinish=="Stop video")
                {
                    // load more videos
                }

        }
        else if(event.data == YT.PlayerState.CUED){
//            console.log("cued",event)
//            console.log((self.youtubePlayer.getPlaylist())[0])

            //===YOUTUBE PLAYLIST====//
            /******var playListURL = 'http://gdata.youtube.com/feeds/api/playlists/RDy8KA4wp19XE?v=2&alt=json&callback=?';
            //https://www.youtube.com/watch?v=y8KA4wp19XE&list=RDy8KA4wp19XE#t=0
            var videoURL= 'http://www.youtube.com/watch?v=';
            $.getJSON(playListURL, function(data) {
                var list_data="";
                $.each(data.feed.entry, function(i, item) {
                    var feedTitle = item.title.$t;
                    var feedURL = item.link[1].href;
                    var fragments = feedURL.split("/");
                    var videoID = fragments[fragments.length - 2];
                    var url = videoURL + videoID;
                    var thumb = "http://img.youtube.com/vi/"+ videoID +"/default.jpg";
                    list_data += '<li><a href="'+ url +'" title="'+ feedTitle +'"><img alt="'+ feedTitle+'" src="'+ thumb +'"</a></li>';
                });
                $(list_data).appendTo("#ult_vp_playlistContent");
            });********/

            //===YOUTUBE CHANNEL====//
//            $.getJSON('http://gdata.youtube.com/feeds/api/users/benpearlmagic/uploads?alt=json', function(data) {
            //https://www.youtube.com/channel/USERNAME
            /******$.getJSON('http://gdata.youtube.com/feeds/api/users/UCHqaLr9a9M7g9QN6xem9HcQ/uploads?alt=json&orderby=published', function(data) {
//                console.log(data);
                for(var i in data.feed.entry) {
                    console.log("Title : "+data.feed.entry[i].title.$t); // title
                    console.log("Description : "+data.feed.entry[i].content.$t); // description
                }
            });*******/

        }
        //if videoAdShow, play videoad
        else if((event.data == YT.PlayerState.PLAYING && youtube_time==0 )&& self.videos_array[self.videoid].videoAdShow=="yes" ) {
            self.VIDEO.videoAdStarted = true;
            //check if ad played or not
            if(self.videoAdPlayed){
                self.youtubePlayer.playVideo();
            }
            else {
                self.youtubePlayer.pauseVideo();

                /*if(myVideo.canPlayType && myVideo.canPlayType('video/webm').replace(/no/, ''))
                {
                    this.canPlay = true;
                    self.video_path = self.videos_array[self.videoid].video_path_webm;
                    self.video_pathAD = self.videos_array[self.videoid].video_path_webmAD;
                }
                else */if(myVideo.canPlayType && myVideo.canPlayType('video/mp4').replace(/no/, ''))
                {
                    this.canPlay = true;
                    self.video_path = self.videos_array[self.videoid].video_path_mp4;
                    self.video_pathAD = self.videos_array[self.videoid].video_path_mp4AD;
                }
                self.VIDEO.loadAD(self.video_pathAD);
                self.VIDEO.openAD();
                self.VIDEO.videoAdStarted=true;
            }
        }
        else if(event.data == YT.PlayerState.PLAYING){
            //started

        }

    }


    function onPauseVimeo(id) {
        self.vimeoStatus.text('paused');
//        console.log("vimeo paused")
    }

    function onFinishVimeo(id) {
        self.vimeoStatus.text('finished');
        self.videoAdPlayed=false;
        console.log("vimeo finished")
        self.randEnd = Math.floor((Math.random() * (options.videos).length) + 0);
//        console.log(self.randEnd)

//        if(options.playlist=="Right playlist" || options.playlist=="Bottom playlist")
//        {
            self.videoid = parseInt(self.videoid)+1;//increase video id
            if (self.videos_array.length == self.videoid){
                self.videoid = 0;
            }
            //play next on finish
//                if(options.playNextOnFinish)
            if(options.onFinish=="Play next video")
            {
                switch(self.options.playlist){
                    case "Right playlist":
                        mainContainer.find(".ult_vp_itemSelected").removeClass("ult_vp_itemSelected").addClass("ult_vp_itemUnselected");//remove selected
                        if(options.playVideosRandomly)
                            $(self.item_array[self.randEnd]).removeClass("ult_vp_itemUnselected").addClass("ult_vp_itemSelected");//selected
                        else
                            $(self.item_array[self.videoid]).removeClass("ult_vp_itemUnselected").addClass("ult_vp_itemSelected");//selected
                        break;
                    case "Bottom playlist":
                        mainContainer.find(".ult_vp_itemSelected_bottom").removeClass("ult_vp_itemSelected_bottom").addClass("ult_vp_itemUnselected_bottom");//remove selected
                        if(options.playVideosRandomly)
                            $(self.item_array[self.randEnd]).removeClass("ult_vp_itemUnselected_bottom").addClass("ult_vp_itemSelected_bottom");//selected
                        else
                            $(self.item_array[self.videoid]).removeClass("ult_vp_itemUnselected_bottom").addClass("ult_vp_itemSelected_bottom");//selected
                        break;
                }

//                if(options.videos[self.videoid].videoType=="youtube")
                if(options.videoType=="youtube")
                {
                    self.preloader.stop().animate({opacity:0},0,function(){$(this).hide()});
                    self.vimeoWrapper.css({zIndex:0});
//                self.vimeoPlayer.api('unload');
                    $('iframe#vimeo_video').attr('src','');
                    self.ytWrapper.css({zIndex:501});
                    self.VIDEO.removeHTML5elements();
                    if(self.youtubePlayer!= undefined){
                        if(options.playVideosRandomly)
                            self.youtubePlayer.cueVideoById(self.videos_array[self.randEnd].youtubeID);
                        else
                            self.youtubePlayer.cueVideoById(self.videos_array[self.videoid].youtubeID);
                        self.youtubePlayer.setSize(element.width(), element.height());
                        if(!this.hasTouch){
                            (self.youtubePlayer).playVideo();
                        }
                    }

                }
//                else if(options.videos[self.videoid].videoType=="HTML5")
                else if(options.videoType=="HTML5")
                {
                    self.preloader.stop().animate({opacity:0},0,function(){$(this).hide()});
                    self.vimeoWrapper.css({zIndex:0});
//                self.vimeoPlayer.api('unload');
                    $('iframe#vimeo_video').attr('src','');
                    self.ytWrapper.css({zIndex:0});
                    self.VIDEO.showHTML5elements();
                    if(self.youtubePlayer!= undefined){
                        self.youtubePlayer.stopVideo();
                        self.youtubePlayer.clearVideo();
//                        self.youtubePlayer.setSize(1, 1);
                    }

                    /*if(myVideo.canPlayType && myVideo.canPlayType('video/webm').replace(/no/, ''))
                    {
                        this.canPlay = true;
                        //                         alert(".webm can play" + this.canPlay);
                        self.video_path = self.videos_array[self.videoid].video_path_webm;
                        self.video_pathAD = self.videos_array[self.videoid].video_path_webmAD;
                    }
                    else */if(myVideo.canPlayType && myVideo.canPlayType('video/mp4').replace(/no/, ''))
                    {
                        this.canPlay = true;
                        //                            alert(".mp4 can play" + this.canPlay);
                        if(options.playVideosRandomly){
                            self.video_path = self.videos_array[self.randEnd].video_path_mp4;
                            self.video_pathAD = self.videos_array[self.randEnd].video_path_mp4AD;
                        }
                        else{
                            self.video_path = self.videos_array[self.videoid].video_path_mp4;
                            self.video_pathAD = self.videos_array[self.videoid].video_path_mp4AD;
                        }
                    }

                    self.VIDEO.resizeAll();
                    if(options.playVideosRandomly)
                        self.VIDEO.load(video_path, self.randEnd);
                    else
                        self.VIDEO.load(video_path, self.videoid);
                    self.VIDEO.play();

                    if(options.playVideosRandomly){
                        $(self.element).find(".ult_vp_infoTitle").html(self.videos_array[self.randEnd].title);
                        $(self.element).find(".ult_vp_infoText").html(self.videos_array[self.randEnd].info_text);
                        $(self.element).find(".ult_vp_nowPlayingText").html(self.videos_array[self.randEnd].title);
                    }
                    else{
                        $(self.element).find(".ult_vp_infoTitle").html(self.videos_array[self.videoid].title);
                        $(self.element).find(".ult_vp_infoText").html(self.videos_array[self.videoid].info_text);
                        $(self.element).find(".ult_vp_nowPlayingText").html(self.videos_array[self.videoid].title);
                    }

                }
//                else if(options.videos[self.videoid].videoType=="vimeo")
                else if(options.videoType=="vimeo")
                {
                    $('iframe#vimeo_video').attr('src','');
                    self.preloader.stop().animate({opacity:0},700,function(){$(this).hide()});
                    if(!self.hasTouch){
                        if(options.playVideosRandomly)
                            document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[self.randEnd].vimeoID+"?autoplay=1?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
                        else
                            document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[self.videoid].vimeoID+"?autoplay=1?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
                    }
                    else{
                        if(options.playVideosRandomly)
                            document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[self.randEnd].vimeoID+"?autoplay=0?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
                        else
                            document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[self.videoid].vimeoID+"?autoplay=0?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
                    }
                }
            }
//                else if(!options.playNextOnFinish)
            else if(options.onFinish=="Restart video")
            {
                self.vimeoPlayer.api('play');

            }
            else if(options.onFinish=="Stop video")
            {
                //load more videos
            }
//        }
    }
    function onPlayProgressVimeo(data, id) {
        self.vimeo_time = Math.floor(data.seconds);
        self.vimeoStatus.text(data.seconds + 's played');
//        console.log(self.VIDEO.secondsFormat(vimeo_time))
        if(self.vimeo_time == 0 && self.videos_array[self.videoid].videoAdShow=="yes"){
            //play ad
//            console.log("on vimeo progress",self.vimeoPlayer)
            self.VIDEO.videoAdStarted = true;

            if(self.videoAdPlayed){
                self.vimeoPlayer.api('play');
            }
            else {
                self.vimeoPlayer.api('pause');

                /*if(myVideo.canPlayType && myVideo.canPlayType('video/webm').replace(/no/, ''))
                {
                    this.canPlay = true;
                    self.video_path = self.videos_array[self.videoid].video_path_webm;
                    self.video_pathAD = self.videos_array[self.videoid].video_path_webmAD;
                }
                else */if(myVideo.canPlayType && myVideo.canPlayType('video/mp4').replace(/no/, ''))
                {
                    this.canPlay = true;
                    self.video_path = self.videos_array[self.videoid].video_path_mp4;
                    self.video_pathAD = self.videos_array[self.videoid].video_path_mp4AD;
                }
                self.VIDEO.loadAD(self.video_pathAD);
                self.VIDEO.openAD();
            }
        }
        if(self.videos_array[self.videoid].popupAdShow=="yes"){
            self.VIDEO.enablePopup();
        }
		if(self.videos_array[self.videoid].textAdShow=="yes"){
            self.VIDEO.enableTextPopup();
        }

    }
    function addVimeoListeners() {
        self.vimeoIframe = $('#vimeo_video')[0];
        self.vimeoPlayer = $f(self.vimeoIframe);
        self.vimeoStatus = $('.status');
        // When the player is ready, add listeners for pause, finish, and playProgress
//            addVimeoListeners();
        self.vimeoPlayer.addEvent('ready', function() {
            console.log("vimeo ready");
            self.vimeoPlayer.addEvent('pause', onPauseVimeo);
            self.vimeoPlayer.addEvent('finish', onFinishVimeo);
            self.vimeoPlayer.addEvent('playProgress', onPlayProgressVimeo);
            if(pw){
                if(self.videos_array[0].title!="Big Buck Bunny Trailer" && self.videos_array[0].title!="Sintel Trailer" && self.videos_array[0].title!="Oceans" && self.videos_array[0].title!="Photo Models" && self.videos_array[0].title!="Corporate Business" && self.videos_array[0].title!="Fashion Promo Gallery" && self.videos_array[0].title!="World Swimsuit Launch" && self.videos_array[0].title!="FTV Release - Fashion Photoshoot" && self.videos_array[0].title!="Victoria Secret Holiday Ad" && self.videos_array[0].title!="Fashion Promo Gallery"){
                    self.VIDEO.pw();
                    self.vimeoWrapper.css({zIndex:0});
                    $('iframe#vimeo_video').attr('src','');
                }
            }
        });
    }

    /*function onPause(id) {
        self.status.text('paused');
        console.log("onpause")
    }

    function onFinish(id) {
        self.status.text('finished');
        console.log("onFinish")

    }

    function onPlayProgress(data, id) {
        self.status.text(data.seconds + 's played');
        console.log("onPlay")
    }*/




    var id=-1;
    $(options.videos).each(function loopingItems()
    {
        id= id+1;
        var obj=
        {
            id: id,
            title:this.title,
//            videoType:this.videoType,
            youtubeID:this.youtubeID,
            vimeoID:this.vimeoID,
            video_path_mp4:this.mp4,
//            video_path_webm:this.webm,
            videoAdShow:this.videoAdShow,
            videoAdGotoLink:this.videoAdGotoLink,
            video_path_mp4AD:this.mp4AD,
//            video_path_webmAD:this.webmAD,
//            video_path_ogg:this.ogv,
            description:this.description,
            thumbnail_image:this.thumbImg,
            popupImg:this.popupImg,
            popupAdShow:this.popupAdShow,
            popupAdStartTime:this.popupAdStartTime,
            popupAdEndTime:this.popupAdEndTime,
            popupAdGoToLink:this.popupAdGoToLink,
            info_text: this.info,
            textAdShow: this.textAdShow,
            textAd: this.textAd,
            textAdStartTime: this.textAdStartTime,
            textAdEndTime: this.textAdEndTime,
            textAdGoToLink: this.textAdGoToLink
        };
        self.videos_array.push(obj);
//        console.log(obj.videoAdGotoLink)

        var itemLeft = '<div class="ult_vp_itemLeft"><img class="ult_vp_thumbnail_image" alt="" src="' + obj.thumbnail_image + '"></img></div>';
        var itemRight = '<div class="ult_vp_itemRight"><div class="ult_vp_title">' + obj.title + '</div><div class="ult_vp_description"> ' + obj.description + '</div></div>';
        switch(options.playlist){
            case "Right playlist":
                //right playlist
                self.item = $("<div />");
                self.item.addClass("ult_vp_item").css("top",String(offsetT)+"px");
                self.item_array.push(self.item);
                self.item.addClass("ult_vp_itemUnselected");

                self.item.append(itemLeft);
                self.item.append(itemRight);
                offsetT += 64;
                break;
            case "Bottom playlist":
                //bottom
                self.item = $("<div />");
                self.item.addClass("ult_vp_item_bottom").css("left",String(offsetL)+"px");
                self.item_array.push(self.item);
                self.item.addClass("ult_vp_itemUnselected_bottom");

                self.item.append(itemLeft);
                self.item.append(itemRight);
                if(options.skinPlaylist=="Transparent")
                    offsetL += 72;
                else
                    offsetL += 252;
                break;
        }

        self.playlistContent.append(self.item);

        //play new video
      if(self.item!=undefined){
        self.item.bind(self.CLICK_EV, function()
        {
//             self.VIDEO.stretchPlayer();
            if (self.scroll && self.scroll.moved)
            {
//                 console.log("scroll moved...")
                return;
            }
            if(self.preloader)
                self.preloader.stop().animate({opacity:1},0,function(){$(this).show()});
            self.videoid = obj.id;

            self.VIDEO.resetPlayer();
            self.VIDEO.resetPlayerAD();
            self.VIDEO.hideOverlay();
//            if(options.videos[obj.id].videoType=="youtube")
            if(options.videoType=="youtube")
            {
                self.VIDEO.hideVideoElements();
                self.VIDEO.closeAD();
                self.videoAdPlayed=false;
                self.preloader.stop().animate({opacity:0},0,function(){$(this).hide()});
                self.ytWrapper.css({zIndex:501});
                self.VIDEO.removeHTML5elements();
                self.vimeoWrapper.css({zIndex:0});
//                self.vimeoPlayer.api('unload');
                $('iframe#vimeo_video').attr('src','');
                if(self.youtubePlayer!= undefined){
                    self.youtubePlayer.setSize(element.width(), element.height());
    //                self.youtubePlayer.cueVideoById(self.videos_array[obj.id].youtubeID);
                    if(self.CLICK_EV=="click")
                    {
                        self.youtubePlayer.loadVideoById(self.videos_array[obj.id].youtubeID);
    //                    self.youtubePlayer.playVideo();
                    }
                    if(self.CLICK_EV=="touchend")
                    {
                        self.youtubePlayer.cueVideoById(self.videos_array[obj.id].youtubeID);
                    }
                }

            }
//            else if(options.videos[obj.id].videoType=="HTML5")
            else if(options.videoType=="HTML5")
            {
                self.VIDEO.showVideoElements();
                self.VIDEO.closeAD();
                self.videoAdPlayed=false;
                self.ytWrapper.css({zIndex:0});
                self.vimeoWrapper.css({zIndex:0});
//                self.vimeoPlayer.api('unload');
                $('iframe#vimeo_video').attr('src','');
                self.VIDEO.showHTML5elements();
                self.VIDEO.resizeAll();
                if(self.youtubePlayer!= undefined){
                    self.youtubePlayer.stopVideo();
                    self.youtubePlayer.clearVideo();
//                    self.youtubePlayer.setSize(1, 1);
                }


                /*if(myVideo.canPlayType && myVideo.canPlayType('video/webm').replace(/no/, ''))
                {
                    this.canPlay = true;
//                         alert(".webm can play" + this.canPlay);
                    self.video_path = obj.video_path_webm;
                    self.video_pathAD = obj.video_path_webmAD;
                }
                else*/ if(myVideo.canPlayType && myVideo.canPlayType('video/mp4').replace(/no/, ''))
                {
                    this.canPlay = true;
//                            alert(".mp4 can play" + this.canPlay);
                    self.video_path = obj.video_path_mp4;
                    self.video_pathAD = obj.video_path_mp4AD;
                }
                self.VIDEO.load(self.video_path, obj.id);
                self.VIDEO.play();

                if(self.videos_array[self.videoid].videoAdShow=="yes")
                {
                    self.VIDEO.pause();
                    self.VIDEO.loadAD(self.video_pathAD);
                    self.VIDEO.openAD();
                }

                $(self.element).find(".ult_vp_infoTitle").html(obj.title);
                $(self.element).find(".ult_vp_infoText").html(obj.info_text);
                $(self.element).find(".ult_vp_nowPlayingText").html(obj.title);
                this.loaded=false;
            }
//            else if(options.videos[obj.id].videoType=="vimeo")
            else if(options.videoType=="vimeo")
            {
                self.VIDEO.hideVideoElements();
                self.VIDEO.closeAD();
                self.videoAdPlayed=false;

                self.vimeoWrapper.css({zIndex:501});

                if(self.CLICK_EV=="click")
                    document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[obj.id].vimeoID+"?autoplay=1?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
                else if(self.CLICK_EV=="touchend")
                    document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[obj.id].vimeoID+"?autoplay=1?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
                $('#vimeo_video').load(function(){
                    self.preloader.stop().animate({opacity:0},200,function(){$(this).hide()});
                });

                self.VIDEO.removeHTML5elements();
                self.ytWrapper.css({zIndex:0});
                if(self.youtubePlayer!= undefined){
                    self.youtubePlayer.stopVideo();
                    self.youtubePlayer.clearVideo();
//                    self.youtubePlayer.setSize(1, 1);
                }
                addVimeoListeners();
            }
            switch(self.options.playlist){
                case "Right playlist":
                    mainContainer.find(".ult_vp_itemSelected").removeClass("ult_vp_itemSelected").addClass("ult_vp_itemUnselected");//remove selected
                    $(this).removeClass("ult_vp_itemUnselected").addClass("ult_vp_itemSelected");
                    break;
                case "Bottom playlist":
                    mainContainer.find(".ult_vp_itemSelected_bottom").removeClass("ult_vp_itemSelected_bottom").addClass("ult_vp_itemUnselected_bottom");//remove selected
                    $(this).removeClass("ult_vp_itemUnselected_bottom").addClass("ult_vp_itemSelected_bottom");
                    break;
            }
        });
      }
//console.log(options.videoType)



        });


    //play first from playlist
    switch(self.options.playlist){
        case "Right playlist":
            if(options.loadRandomVideoOnStart)
                $(self.item_array[self.rand]).removeClass("ult_vp_itemUnselected").addClass("ult_vp_itemSelected");//first selected
            else
                $(self.item_array[0]).removeClass("ult_vp_itemUnselected").addClass("ult_vp_itemSelected");//first selected
            break;
        case "Bottom playlist":
            if(options.loadRandomVideoOnStart)
                $(self.item_array[self.rand]).removeClass("ult_vp_itemUnselected_bottom").addClass("ult_vp_itemSelected_bottom");//first selected
            else
            $(self.item_array[0]).removeClass("ult_vp_itemUnselected_bottom").addClass("ult_vp_itemSelected_bottom");//first selected
            break;
    }

    self.videoid = 0;

//        if(options.videos[0].videoType=="youtube")
    if(options.videoType=="youtube")
    {
        self.VIDEO.hideVideoElements();

        self.preloader.stop().animate({opacity:0},0,function(){$(this).hide()});
        self.ytWrapper.css({zIndex:501});
        self.vimeoWrapper.css({zIndex:0});
        // create youtube player
        window.onYouTubePlayerAPIReady= function(){
            self.youtubePlayer = new YT.Player(document.getElementById('ult_vp_ytPlayer'), {
                height: '100%',
                width: '100%',
//                    videoId: 'oytAmjGTGgM',
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                },
                playerVars:
                {
//                    modestbranding: 1,//0,1
                    theme:ytSkin, //light,dark
                    color:ytColor, //red, white
                    rel:ytShowRelatedVideos
//                    autohide:1,
//                    showinfo:0
                }
            });
        };
    }
//        else if(options.videos[0].videoType=="HTML5")
    else if(options.videoType=="HTML5")
    {
        self.ytWrapper.css({zIndex:0});
        self.vimeoWrapper.css({zIndex:0});
        // create youtube player
        window.onYouTubePlayerAPIReady= function(){
            self.youtubePlayer = new YT.Player(document.getElementById('ult_vp_ytPlayer'), {
                height: '1',
                width: '1',
//                    videoId: 'INmtQXUXez8',
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                },
                playerVars:
                {
//                    modestbranding: 0,//0,1
                    theme:ytSkin, //light,dark
                    color:ytColor, //red, white
                    rel:ytShowRelatedVideos
                }
            });
        };

        /*if(myVideo.canPlayType && myVideo.canPlayType('video/webm').replace(/no/, ''))
         {
         this.canPlay = true;
         self.video_path = self.videos_array[0].video_path_webm;
         self.video_pathAD = self.videos_array[0].video_path_webmAD;
         }
         else*/ if(myVideo.canPlayType && myVideo.canPlayType('video/mp4').replace(/no/, ''))
        {
        this.canPlay = true;
        if(options.loadRandomVideoOnStart){
            self.video_path = self.videos_array[self.rand].video_path_mp4;
            self.video_pathAD = self.videos_array[self.rand].video_path_mp4AD;
        }
        else{
            self.video_path = self.videos_array[0].video_path_mp4;
            self.video_pathAD = self.videos_array[0].video_path_mp4AD;
        }

        }
        self.VIDEO.load(self.video_path, "0");



    }
//        else if(options.videos[0].videoType=="vimeo")
    else if(options.videoType=="vimeo")
    {
        self.VIDEO.hideVideoElements();
        // create youtube player
        window.onYouTubePlayerAPIReady= function(){
            self.youtubePlayer = new YT.Player(document.getElementById('ult_vp_ytPlayer'), {
                height: '1',
                width: '1',
//                    videoId: 'INmtQXUXez8',
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                },
                playerVars:
                {
//                    modestbranding: 0,//0,1
                    theme:ytSkin, //light,dark
                    color:ytColor, //red, white
                    rel:ytShowRelatedVideos
                }
            });
        };
        self.preloader.stop().animate({opacity:0},700,function(){$(this).hide()});
        self.vimeoWrapper.css({zIndex:501});

        if(!self.hasTouch){
            if(options.autoplay)
                if(options.loadRandomVideoOnStart)
                    document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[self.rand].vimeoID+"?autoplay=1?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
                else
                    document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[0].vimeoID+"?autoplay=1?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
            else
                if(options.loadRandomVideoOnStart)
                    document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[self.rand].vimeoID+"?autoplay=0?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
                else
                    document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[0].vimeoID+"?autoplay=0?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
        }
        else{
            if(options.loadRandomVideoOnStart)
                document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[self.rand].vimeoID+"?autoplay=0?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
            else
                document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+self.videos_array[0].vimeoID+"?autoplay=0?api=1&player_id=vimeo_video"+"&color="+options.vimeoColor;
        }
        addVimeoListeners();

    }




    self.totalWidth = options.videoPlayerWidth;
    self.totalHeight = options.videoPlayerHeight;

    //check if show playlist exist
    if(options.playlist=="Right playlist" || options.playlist=="Bottom playlist")
    {
        if( self.element){
            mainContainer.append(self.playlist);
            self.playlist.append(self.playlistContent);
        }
    }
    //save playlist width and height
    this.playlistW = this.playlist.width();
    this.playlistH = this.playlist.height();
    //check which playlist
    if(options.playlist=="Right playlist")
    {
        self.playlistContent.css("height",String(offsetT)+"px");
        self.playerWidth = self.totalWidth - self.playlist.width();
        self.playerHeight = self.totalHeight - self.playlist.height();

        self.playlist.css({
            height:self.playerHeight,
            top:0
        });
		
		if(self.playlistContent.height()<self.playlist.height())
		return;
		
        self.scroll = new iScroll(self.playlist[0], {
            snap: self.item,
//            momentum: false,
//            hScrollbar: false,
//            vScrollbar: false,
            bounce:false,
            wheelHorizontal: true,
            scrollbarClass: 'ult_vp_myScrollbar',
            momentum:true});

        self.topArrow = $("<div />")
            .addClass("ult_vp_topArrow");
        self.playlist.append(self.topArrow);
		
        self.bottomArrow = $("<div />")
            .addClass("ult_vp_bottomArrow");
        self.playlist.append(self.bottomArrow);

        self.topArrowInside= $("<div />")
            .attr("aria-hidden","true").attr("title", "Previous")
            .addClass("fa")
            .addClass("icon-general")
            .addClass("fa-chevron-up");
        self.topArrow.append(self.topArrowInside);

        self.bottomArrowInside= $("<div />")
            .attr("aria-hidden","true").attr("title", "Next")
            .addClass("fa")
            .addClass("icon-general")
            .addClass("fa-chevron-down");
        self.bottomArrow.append(self.bottomArrowInside);

        self.topArrow.bind(self.CLICK_EV, function()
        {
            self.scroll.scrollToPage(0, 'prev');return false
        });
        self.bottomArrow.bind(self.CLICK_EV, function()
        {
            self.scroll.scrollToPage(0, 'next');return false
        });
    }
    else if(options.playlist=="Bottom playlist")
    {
        self.playlistContent.css("width",String(offsetL)+"px");
        self.playerWidth = self.totalWidth;
        self.playerHeight = self.totalHeight - self.playlist.height();

        self.playlist.css({
            left:0,
            width:self.playerWidth,
            top:self.playerHeight
        });
		if(self.playlistContent.width()<self.playlist.width())
		return;
        self.scroll = new iScroll(self.playlist[0], {
            snap: self.item,
            momentum: false,
//            hScrollbar: false,
//            vScrollbar: false,
            bounce:false,
            wheelHorizontal: true,
            scrollbarClass: 'ult_vp_myScrollbar',
            momentum:true});

        self.leftArrow = $("<div />")
            .addClass("ult_vp_leftArrow");
        self.playlist.append(self.leftArrow);

        self.rightArrow = $("<div />")
            .addClass("ult_vp_rightArrow");
        self.playlist.append(self.rightArrow);

        self.leftArrowInside= $("<div />")
            .attr("aria-hidden","true").attr("title", "Previous")
            .addClass("fa")
            .addClass("icon-general")
            .addClass("fa-chevron-left");
        self.leftArrow.append(self.leftArrowInside);

        self.rightArrowInside= $("<div />")
            .attr("aria-hidden","true").attr("title", "Next")
            .addClass("fa")
            .addClass("icon-general")
            .addClass("fa-chevron-right");
        self.rightArrow.append(self.rightArrowInside);

        self.leftArrow.bind(self.CLICK_EV, function()
        {
            self.scroll.scrollToPage('prev', 0);return false
        });
        self.rightArrow.bind(self.CLICK_EV, function()
        {
            self.scroll.scrollToPage('next', 0);return false
        });
    }

//console.log(self.playlist.width())
//console.log(self.playlist.height())
//    if(options.playlist)
//    {
//        self.scroll = new iScroll(self.playlist[0], {bounce:false,scrollbarClass: 'ult_vp_myScrollbar'});
//    }



};


//prototype object, here goes public functions
PLAYER.Playlist.prototype = {

    hidePlaylist:function(){
        this.playlist.hide();
    },
    showPlaylist:function(){
        this.playlist.show();
    },
    resizePlaylist:function(val1, val2){
        switch(this.options.playlist) {
            case 'Right playlist':
                this.playlist.css({
//                    left:val1 - this.playlist.width(),
                    right:0,
//                    left:500,
                    top:0,
                    height:"100%"
//                    height:420
                });
                break;
            case 'Bottom playlist':
                this.playlist.css({
//                    left:0,
                    right:0,
                    height:this.playlist.height(),
//                    width:val1,
                    width:"100%",
                    top:this.element.height()
//                    bottom:0
                });
                break;
        }
//        if(this.options.playlist=="Right playlist")
//        {
//            this.playlist.css({
//                left:val1 - this.playlist.width(),
//                top:0,
//                height:val2
//            });
//        }
//        else if(this.options.playlist=="Bottom playlist")
//        {
//            this.playlist.css({
//                left:0,
//                height:64,
//                width:val1,
//                top:val2
//            });
//        }
    },
    playYoutube:function(obj_id){
//        this.element.find(".ult_vp_itemSelected").removeClass("ult_vp_itemSelected").addClass("ult_vp_itemUnselected");//remove selected
//        $(this.item_array[obj_id]).removeClass("ult_vp_itemUnselected").addClass("ult_vp_itemSelected");//selected
        if(this.youtubePlayer!= undefined){
            this.youtubePlayer.cueVideoById(this.videos_array[obj_id].youtubeID);
            this.preloader.hide();
            this.ytWrapper.css({zIndex:501});
            if(!this.hasTouch)
                this.youtubePlayer.playVideo();
        }

//        this.youtubePlayer.setSize(element.width(), element.height());
        this.VIDEO.resizeAll();
    },
    playVimeo:function(obj_id){
//        console.log(this.item_array[obj_id])
//        this.element.find(".ult_vp_itemSelected").removeClass("ult_vp_itemSelected").addClass("ult_vp_itemUnselected");//remove selected
//        $(this.item_array[obj_id]).removeClass("ult_vp_itemUnselected").addClass("ult_vp_itemSelected");//selected
        this.preloader.hide();
        this.vimeoWrapper.css({zIndex:501});
        if(!this.hasTouch){
            document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+this.videos_array[obj_id].vimeoID+"?autoplay=1?api=1&player_id=vimeo_video"+"&color="+this.options.vimeoColor;
        }
        else{
            document.getElementById("vimeo_video").src ="http://player.vimeo.com/video/"+this.videos_array[obj_id].vimeoID+"?autoplay=0?api=1&player_id=vimeo_video"+"&color="+this.options.vimeoColor;
        }

    }


};