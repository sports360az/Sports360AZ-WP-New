<?php
	function ultimate_video_player_shortcode($atts){
		$args = shortcode_atts( 
			array(
				'id'   => '-1'
			), 
			$atts
		);
		$id = (int) $args['id'];
		$ult_players = get_option('ult_players');
		$ult_player = $ult_players[$id];
		
		wp_enqueue_script("embed", plugins_url()."/ultimate-video-player/js/embed.js", array('jquery'),ULT_VIDEO_PLAYER_VERSION);
		wp_enqueue_script("IScroll4Custom", plugins_url()."/ultimate-video-player/js/IScroll4Custom.min.js", array('jquery'),ULT_VIDEO_PLAYER_VERSION);
		wp_enqueue_script("Froogaloop2", plugins_url()."/ultimate-video-player/js/froogaloop.min.js", array('jquery'),ULT_VIDEO_PLAYER_VERSION);
		wp_enqueue_script("THREEx.FullScreen", plugins_url()."/ultimate-video-player/js/THREEx.FullScreen.min.js", array('jquery'),ULT_VIDEO_PLAYER_VERSION);
		wp_enqueue_script("playlist", plugins_url()."/ultimate-video-player/js/Playlist.min.js", array('jquery'),ULT_VIDEO_PLAYER_VERSION);
		wp_enqueue_script("readvideo_player", plugins_url()."/ultimate-video-player/js/videoPlayer.min.js", array(),ULT_VIDEO_PLAYER_VERSION);
		
		wp_enqueue_style( 'video_player_style', plugins_url()."/ultimate-video-player/css/videoPlayerMain.css" , array(),ULT_VIDEO_PLAYER_VERSION);
		wp_enqueue_style( 'video_player_icons', plugins_url()."/ultimate-video-player/css/font-awesome.css" , array(),ULT_VIDEO_PLAYER_VERSION);
		/* ult_trace_vp($players[$id]);  */
		switch( $ult_players[$id]["skinPlayer"] ) {
			case 'Default':
				wp_enqueue_style( 'ult_skin1', plugins_url()."/ultimate-video-player/css/videoPlayer.theme1.css" , array(),ULT_VIDEO_PLAYER_VERSION);
				break;
			case 'Classic':
				wp_enqueue_style( 'ult_skin2', plugins_url()."/ultimate-video-player/css/videoPlayer.theme2.css" , array(),ULT_VIDEO_PLAYER_VERSION);
				break;
			case 'Minimal':
				wp_enqueue_style( 'ult_skin3', plugins_url()."/ultimate-video-player/css/videoPlayer.theme3.css" , array(),ULT_VIDEO_PLAYER_VERSION);
				break;
			case 'Transparent':
				wp_enqueue_style( 'ult_skin4', plugins_url()."/ultimate-video-player/css/videoPlayer.theme4.css" , array(),ULT_VIDEO_PLAYER_VERSION);
				break;
			case 'Silver':
				wp_enqueue_style( 'ult_skin5', plugins_url()."/ultimate-video-player/css/videoPlayer.theme5.css" , array(),ULT_VIDEO_PLAYER_VERSION);
				break;
		}
		switch( $ult_players[$id]["skinPlaylist"] ) {
			case 'Default':
				wp_enqueue_style( 'ult_skin_playlist1', plugins_url()."/ultimate-video-player/css/videoPlayer.theme1_Playlist.css" , array(),ULT_VIDEO_PLAYER_VERSION);
				break;
			case 'Classic':
				wp_enqueue_style( 'ult_skin_playlist2', plugins_url()."/ultimate-video-player/css/videoPlayer.theme2_Playlist.css" , array(),ULT_VIDEO_PLAYER_VERSION);
				break;
			case 'Minimal':
				wp_enqueue_style( 'ult_skin_playlist3', plugins_url()."/ultimate-video-player/css/videoPlayer.theme3_Playlist.css" , array(),ULT_VIDEO_PLAYER_VERSION);
				break;
			case 'Transparent':
				wp_enqueue_style( 'ult_skin_playlist4', plugins_url()."/ultimate-video-player/css/videoPlayer.theme4_Playlist.css" , array(),ULT_VIDEO_PLAYER_VERSION);
				break;
			case 'Silver':
				wp_enqueue_style( 'ult_skin_playlist5', plugins_url()."/ultimate-video-player/css/videoPlayer.theme5_Playlist.css" , array(),ULT_VIDEO_PLAYER_VERSION);
				break;
		}
		
		$ult_player['rootFolder'] = plugins_url()."/ultimate-video-player/";
		$output = ('<div class="videoplayer" id="'.$id.'" ><div id="options" style="display:none;">'.json_encode($ult_player).'</div></div>');
		return $output;
	}
	add_shortcode('ultimate_video_player', 'ultimate_video_player_shortcode');