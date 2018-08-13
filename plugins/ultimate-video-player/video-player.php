<?php

	/*
	Plugin Name: Ultimate Video Player
	Plugin URI: http://codecanyon.net/user/_zac_
	Description: Video Player with Gallery with support for Youtube streaming, Vimeo streaming and Advertising system
	Version: 7.0.7
	Author: _zac_
	Author URI: http://codecanyon.net/item/ultimate-video-player-with-youtubevimeohtml5ads/7316469
	*/

	//define( 'WP_DEBUG', true );
	define('ULT_VIDEO_PLAYER_DIR', plugin_dir_url( __FILE__ ));
	define('ULT_VIDEO_PLAYER_VERSION', '7.0.7');
	
	function ult_vp_trace($var){
		echo("<pre style='background:#fcc;color:#000;font-size:12px;font-weight:bold'>");
		print_r($var);
		echo("</pre>");
	}

	if(!is_admin()) {
		include("includes/plugin-frontend.php");
	}
	else {
		include("includes/plugin-admin.php");
		register_deactivation_hook( __FILE__, "deactivate_ult_video_player");
		add_filter("plugin_action_links_" . plugin_basename(__FILE__), "ult_video_player_admin_link");
	}
	
	
	function ult_video_player_scripts() {
		/*wp_enqueue_script("IScroll4Custom", plugins_url()."/ultimate-video-player/js/IScroll4Custom.js", array('jquery'),ULT_VIDEO_PLAYER_VERSION);
		wp_enqueue_script("Froogaloop2", plugins_url()."/ultimate-video-player/js/froogaloop.js", array('jquery'),ULT_VIDEO_PLAYER_VERSION);
		wp_enqueue_script("THREEx.FullScreen", plugins_url()."/ultimate-video-player/js/THREEx.FullScreen.js", array('jquery'),ULT_VIDEO_PLAYER_VERSION);
		wp_enqueue_script("playlist", plugins_url()."/ultimate-video-player/js/Playlist.js", array('jquery'),ULT_VIDEO_PLAYER_VERSION);
		wp_enqueue_script("readvideo_player", plugins_url()."/ultimate-video-player/js/videoPlayer.js", array(),ULT_VIDEO_PLAYER_VERSION);
		
		wp_enqueue_style( 'video_player_style', plugins_url()."/ultimate-video-player/css/videoPlayerMain.css" , array(),ULT_VIDEO_PLAYER_VERSION);
		wp_enqueue_style( 'video_player_icons', plugins_url()."/ultimate-video-player/css/font-awesome.css" , array(),ULT_VIDEO_PLAYER_VERSION);*/
		/* wp_enqueue_style( 'video_player_style_theme', plugins_url()."/ultimate-video-player/css/videoPlayer.theme1.css" , array(),ULT_VIDEO_PLAYER_VERSION); */
		/* wp_enqueue_style( 'video_player_style_playlist', plugins_url()."/ultimate-video-player/css/videoPlayer.theme1_Playlist.css" , array(),ULT_VIDEO_PLAYER_VERSION); */
		
		
		//embed script
		/*wp_enqueue_script("embed", plugins_url()."/ultimate-video-player/js/embed.js", array('readvideo_player'),ULT_VIDEO_PLAYER_VERSION);*/
	}
	add_action( 'wp_enqueue_scripts', 'ult_video_player_scripts' );
	
	function ult_video_player_admin_scripts() {
		// wp_enqueue_media();
		// wp_enqueue_script("video_player_admin", plugins_url()."/ultimate-video-player/js/plugin_admin.js", array('jquery','jquery-ui-sortable','jquery-ui-resizable','jquery-ui-selectable','jquery-ui-tabs' ),ULT_VIDEO_PLAYER_VERSION);
		// wp_enqueue_style( 'video_player_admin_css', plugins_url()."/ultimate-video-player/css/player-admin.css",array(), ULT_VIDEO_PLAYER_VERSION );
		// wp_enqueue_style( 'jquery-ui-style', plugins_url()."/ultimate-video-player/css/jquery-ui.css",array(), ULT_VIDEO_PLAYER_VERSION );
		// pass $players to javascript
		// wp_localize_script( 'video_player_admin', 'options', json_encode($players[$current_id]) );
	}
	add_action( 'wp_enqueue_scripts', 'ult_video_player_admin_scripts' );
	
	function ult_video_player_admin_link($links) {
		array_unshift($links, '<a href="' . get_admin_url() . 'options-general.php?page=ult_video_player_admin">Admin</a>');
		return $links;
	}
	
	function deactivate_ult_video_player() {
		//delete_option("ult_players");
	}