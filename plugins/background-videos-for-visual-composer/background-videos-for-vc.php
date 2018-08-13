<?php
/*
Plugin Name: Background Videos for Visual Composer
Plugin URI: https://html5backgroundvideos.com/background-video-addon-visual-composer/
Description: Add a video background to any visual composer row.
Version: 1.0.6
Author: BG Stock, theeighth
Author URI: https://html5backgroundvideos.com
License: GPLv2 or later
*/

// don't load directly
if (!defined('ABSPATH')) die('-1');

// Wrap entire plugin in a check for visual composer
if( function_exists( 'add_shortcode_param' ) ) {

	class VC_Video_Background {

		/*
		Constructor
		*/
		function __construct() {
			// We safely integrate with VC with this hook
			add_action( 'init', array( $this, 'add_bg_video_params' ) );

			// Register CSS and JS
			add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts_and_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts_and_styles' ) );

			// Register field types
			add_action( 'init', array( $this, 'create_number_field' ) );
			add_action( 'init', array( $this, 'create_media_field' ) );
		}

		/*
		Defines output for video
		*/
		public static function video_shortcode( $atts, $content ) {
			$output = '';

			// Custom atts
			$use_background_video = $mp4_url = $webm_url = $ogg_url = $poster_image = $fade_in = $pause_after = $pause_play_button = $pauseplay_xpos = $pauseplay_ypos = $video_overlay = $overlay_opacity = $overlay_color = $overlay_pattern = '';
			extract( shortcode_atts( array(
					"use_background_video" => "",
					"mp4_url" => "",
					"webm_url" => "",
					"ogg_url" => "",
					'poster_image'=>"",
					"fade_in" => "",
					"pause_after"=>"",
					"pause_play_button" => "",
					"pauseplay_xpos"=>"",
					"pauseplay_ypos" => "",
					"video_overlay" => "",
					"overlay_opacity" => "",
					"overlay_color" => "",
					"overlay_pattern" => ""
				), $atts ) );

			if( $use_background_video ) {

				$poster_image_src = wp_get_attachment_image_src( $poster_image, 'full' );
				$poster_image_src = $poster_image_src[0];
				$row_id = uniqid();

				// Pattern stuff
				$patterns_path = plugins_url('assets/patterns/', __FILE__);
				$is_pattern_overlay = false;
				$pattern_prefix = '';
				if( 'pattern_light' === $video_overlay ) {
					$pattern_prefix = 'white-';
					$is_pattern_overlay = true;
				} else if( 'pattern_dark' === $video_overlay ) {
					$pattern_prefix = 'black-';
					$is_pattern_overlay = true;
				}

				ob_start(); ?>

				<video 
					id="<?php echo 'vc_bgvideo_' . $row_id; ?>" 
					class="vc_video_bg jquery-background-video <?php echo is_admin() ? 'frontend_editor_active' : ''; ?>" 
					loop 
					autoplay 
					muted
					data-bgvideo
					<?php echo ( $poster_image ) ? 'poster="' . $poster_image_src . '"' : ''; ?>
					<?php echo ( $fade_in ) ? 'data-bgvideo-fade-in="500"' : 'data-bgvideo-fade-in="0"' ?>
					<?php echo ( $pause_after ) ? 'data-bgvideo-pause-after="' . $pause_after . '"' : ''; ?>
					<?php echo ( $pause_play_button ) ? 'data-bgvideo-show-pause-play=true' : 'data-bgvideo-show-pause-play=false'; ?>
					<?php echo ( $pauseplay_xpos ) ? 'data-bgvideo-pause-play-x-pos="' . $pauseplay_xpos . '"' : ''; ?>
					<?php echo ( $pauseplay_xpos ) ? 'data-bgvideo-pause-play-y-pos="' . $pauseplay_ypos . '"' : ''; ?> 
					>
						<?php echo ( $mp4_url ) ? '<source src="' . $mp4_url . '" type="video/mp4">' : ''; ?>
						<?php echo ( $webm_url ) ? '<source src="' . $webm_url . '" type="video/webm">' : ''; ?>
						<?php echo ( $ogg_url ) ? '<source src="' . $ogg_url . '" type="video/ogg">' : ''; ?>
				</video>

				<?php if( 'none' !== $video_overlay ) : ?>
					<div
						id="<?php echo 'vc_bgvideo_overlay_' . $row_id; ?>"
						class="vc_video_overlay"
						style="
							opacity: 0.<?php echo $overlay_opacity; ?>;
							<?php echo ( 'solid' === $video_overlay ) ? 'background-color: ' . $overlay_color . ';' : '' ?>
							<?php echo ( $is_pattern_overlay ) ? 'background-image: url(' . $patterns_path . $pattern_prefix . $overlay_pattern . '.png);' : '' ?>
						"
						>
					</div>
				<?php endif; ?>

				<script type="text/javascript">
				(function() {
					// Move the video and container into the row as the first child
					var video_tag = document.getElementById(<?php echo '"vc_bgvideo_' . $row_id . '"'; ?>);
					var video_overlay = document.getElementById(<?php echo '"vc_bgvideo_overlay_' . $row_id . '"'; ?>);
					// If video_tag is the first child of it's parent, we've already executed this (we're probably in the front end editor)
					if( video_tag.parentNode.firstChild === video_tag ) {
						video_tag.play();
					} else {
						var video_row = video_tag.previousSibling;
						while(video_row && video_row.nodeType != 1) {
							video_row = video_row.previousSibling;
						}
						video_row.insertBefore( video_tag, video_row.firstChild );
						if( video_overlay ) {
							video_row.insertBefore( video_overlay, video_tag.nextSibling);
						}
						video_row.className += ' vc_video_bg_row jquery-background-video-wrapper';
						video_tag.play();
					}
				}());
				</script>

				<?php
				$output .= ob_get_clean();
				return $output;
			}
		}
		
		/*
		Sets up the new fields for background videos on a row
		*/
		public function add_bg_video_params() {

			// Check if Visual Composer is installed
			if ( ! defined( 'WPB_VC_VERSION' ) ) {
				// Display notice that Visual Compser is required
				add_action('admin_notices', array( $this, 'show_vc_version_notice' ));
				return;
			}

			/*
			Add your Background Video option to the Visual Composer row settings.
			*/
			if(function_exists('vc_add_param')){

				$group      = 'Row Background Video';
				$shortcode  = 'vc_row';
				
				/* Intro */
				vc_add_param( $shortcode, array(
					'type'        => 'checkbox',
					'heading'     => 'Background Video',
					'param_name'  => 'use_background_video',
					'value'       => array(
										'Use background video' => 'true'
									 ),
					'description' => 'Looking for great background videos? Try <a href="https://html5backgroundvideos.com/?utm_source=VC%20Video%20Backgrounds%20Addon&utm_medium=Text%20Link&utm_content=Intro%20on%20background%20tab&utm_campaign=WordPress%20Plugins" target="_bank">BG Stock</a> - a library of stock videos specifically for website backgrounds.',
					'group'       => $group
				) );

				/* === Video Files === */

				/* Mp4 URL */
				vc_add_param( $shortcode, array(
					'type'        => 'media',
					'heading'     => 'Mp4 URL',
					'param_name'  => 'mp4_url',
					'mime'        => 'video/mp4',
					'value'       => '',
					'description' => 'Required file type. We recommend using <a href="https://html5backgroundvideos.com/converter?utm_source=VC%20Video%20Backgrounds%20Addon&utm_medium=Text%20Link&utm_content=Files%20section%20of%20background%20tab&utm_campaign=WordPress%20Plugins" target="_blank">this converter</a> to generate your background video files.',
					'group'       => $group,
					'dependency'  => array(
										'element' => 'use_background_video',
										'value'   => array( 'true' )
									 )
				) );

				/* Webm URL */
				vc_add_param( $shortcode, array(
					'type'        => 'media',
					'heading'     => 'Webm URL',
					'param_name'  => 'webm_url',
					'mime'        => 'video/webm',
					'value'       => '',
					'description' => 'Recommended file type.',
					'group'       => $group,
					'dependency'  => array(
										'element' => 'use_background_video',
										'value'   => array( 'true' ),
									 )
				) );

				/* Ogg URL */
				vc_add_param( $shortcode, array(
					'type'        => 'media',
					'heading'     => 'Ogg URL',
					'param_name'  => 'ogg_url',
					'value'       => '',
					'description' => 'Optional file type.',
					'group'       => $group,
					'dependency'  => array(
										'element' => 'use_background_video',
										'value'   => array( 'true' ),
									 )
				) );

				/* Poster */
				vc_add_param( $shortcode, array(
					'type'        => 'attach_image',
					'heading'     => 'Poster / fallback image',
					'param_name'  => 'poster_image',
					'value'       => '',
					'description' => 'This image will be used on devices that don\'t support background video, and will be displayed while the video is loading. We recommend a high-quality screenshot of one of the first few frames. For best results, you should also set this as the background image in the "Design Options" tab, and choose "Cover" as the background-size.',
					'group'       => $group,
					'dependency'  => array(
										'element' => 'use_background_video',
										'value'   => array( 'true' ),
									 )
				) );

				/* === Overlay === */

				/* Overlay Type */
				vc_add_param( $shortcode, array(
					'type'        => 'dropdown',
					'heading'     => 'Video Overlay',
					'param_name'  => 'video_overlay',
					'value'       => array(
										'No overlay'        => 'none',
										'Color' => 'solid',
										'Light Pattern'     => 'pattern_light',
										'Dark Pattern'      => 'pattern_dark'
									 ),
					'description' => 'An overlay can help to provide contrast with your row content, or disguise low quality video.',
					'group'       => $group,
					'dependency'  => array(
										'element' => 'use_background_video',
										'value'   => array( 'true' ),
									 )
				) );

				/* Overlay Opacity */
				vc_add_param( $shortcode, array(
					'type'        => 'number',
					'heading'     => 'Overlay Opacity',
					'param_name'  => 'overlay_opacity',
					'value'       => '0',
					'min'         => '0',
					'max'         => '99',
					'description' => 'Enter a number between 0 and 99.',
					'group'       => $group,
					'dependency'  => array(
										'element' => 'video_overlay',
										'value'   => array( 'solid', 'pattern_light', 'pattern_dark' ),
									 )
				) );

				/* Solid Overlay Colour */
				vc_add_param( $shortcode, array(
					'type'        => 'colorpicker',
					'heading'     => 'Overlay Color',
					'param_name'  => 'overlay_color',
					'value'       => '',
					'description' => '',
					'group'       => $group,
					'dependency'  => array(
										'element' => 'video_overlay',
										'value'   => array( 'solid' ),
									 )
				) );

				/* Pattern */
				vc_add_param( $shortcode, array(
					'type'        => 'dropdown',
					'heading'     => 'Overlay Pattern',
					'param_name'  => 'overlay_pattern',
					'value'       => array(
										'Dots' => 'dots',
										'Squares' => 'squares',
										'Small Checks' => 'small-checks',
										'Medium Checks' => 'medium-checks',
										'Large Checks' => 'large-checks',
										'Vertical Stripes' => 'vertical-stripes',
										'Vertical Lines' => 'vertical-lines',
										'Horizontal Stripes' => 'horizontal-stripes',
										'Horizontal Lines' => 'horizontal-lines',
										'Criss-cross' => 'criss-cross',
										'Diagonal Lines' => 'diagonal-lines',
										'Fly Screen' => 'fly-screen',
										'Plus Signs' => 'plus-signs',
										'Zig Zag' => 'zig-zag',
										'Broken Lines' => 'broken-lines'
									 ),
					'description' => 'Patterns are especially good at disguising low quality video. See a demo of these patterns <a href="https://html5backgroundvideos.com/pattern-overlays?utm_source=VC%20Video%20Backgrounds%20Addon&utm_medium=Text%20Link&utm_content=Pattern%20dropdown%20description&utm_campaign=WordPress%20Plugins" target="_blank">here</a>.',
					'group'       => $group,
					'dependency'  => array(
										'element' => 'video_overlay',
										'value'   => array( 'pattern_light', 'pattern_dark' ),
									 )
				) );

				/* === Options === */

				/* Fade in */
				vc_add_param( $shortcode, array(
					'type'        => 'checkbox',
					'heading'     => 'Fade in on start?',
					'param_name'  => 'fade_in',
					'value'       => array(
										'Fade in on start' => 'true'
									 ),
					'description' => 'Fading the video in on start can help avoid distracting the user.',
					'group'       => $group,
					'dependency'  => array(
										'element' => 'use_background_video',
										'value'   => array( 'true' ),
									 )
				) );

				/* Pause after */
				vc_add_param( $shortcode, array(
					'type'        => 'number',
					'heading'     => 'Pause video after',
					'param_name'  => 'pause_after',
					'value'       => '120',
					'min'         => '0',
					'max'         => '600',
					'description' => 'Enter a number of seconds to play before pausing, or 0 for no pause. We recommend pausing after a while and fading out, to reduce your users\' power consumption.',
					'group'       => $group,
					'dependency'  => array(
										'element' => 'use_background_video',
										'value'   => array( 'true' ),
									 )
				) );

				/* Pause button */
				vc_add_param( $shortcode, array(
					'type'        => 'checkbox',
					'heading'     => 'Add pause/play button?',
					'param_name'  => 'pause_play_button',
					'value'       => array(
										'Add pause/play button' => 'true'
									 ),
					'description' => 'It\'s a good idea to allow your user to pause the video if they wish.',
					'group'       => $group,
					'dependency'  => array(
										'element' => 'use_background_video',
										'value'   => array( 'true' ),
									 )
				) );

				/* Pause X Position */
				vc_add_param( $shortcode, array(
					'type'        => 'dropdown',
					'heading'     => 'Pause/play button X position',
					'param_name'  => 'pauseplay_xpos',
					'value'       => array(
										'Left'   => 'left',
										'Center' => 'center',
										'Right'  => 'right'
									 ),
					'description' => '',
					'group'       => $group,
					'dependency'  => array(
										'element' => 'use_background_video',
										'value'   => array( 'true' ),
									 )
				) );

				/* Pause Y Position */
				vc_add_param( $shortcode, array(
					'type'        => 'dropdown',
					'heading'     => 'Pause/play button Y position',
					'param_name'  => 'pauseplay_ypos',
					'value'       => array(
										'Top'   => 'top',
										'Center' => 'center',
										'Bottom'  => 'bottom'
									 ),
					'description' => '',
					'group'       => $group,
					'dependency'  => array(
										'element' => 'use_background_video',
										'value'   => array( 'true' ),
									 )
				) );

			}
		}

		/*
		Create a "number" field type
		*/
		public function create_number_field() {

			add_shortcode_param( 'number' , array( $this, 'number_field_output' ) );

		}

		/*
		Number field output
		*/
		public function number_field_output( $settings, $value ) {
			$dependency = vc_generate_dependencies_attributes( $settings );
			$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
			$min = isset( $settings['min'] ) ? $settings['min'] : '';
			$max = isset( $settings['max'] ) ? $settings['max'] : '';
			$output = '<input
						type="number"
						min="' . esc_attr( $min ) . '"
						max="' . esc_attr( $max ) . '"
						class="wpb_vc_param_value wpb-number-input"
						name="' . esc_attr( $param_name ) . '"
						value="' . esc_attr( $value ) . '"
						/>';
			return $output;
		}

		/*
		Create a "media" field type
		*/
		public function create_media_field() {

			add_shortcode_param( 'media' , array( $this, 'media_field_output' ), plugins_url('assets/admin-media-selector.js', __FILE__) );

		}

		/*
		Media field output
		*/
		public function media_field_output( $settings, $value ) {
			$dependency = vc_generate_dependencies_attributes( $settings );
			$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
			$mime = isset( $settings['mime'] ) ? $settings['mime'] : '';
			if( is_array( $mime ) ) {
				$mime = implode( ',', $mime );
			}
			$output = '<button type="button" class="button wpb-media-input-button" data-mime-type="' . esc_attr( $mime ) . '" style="float: right; margin-top: 3px;">Browse Media</button>';
			$output .= '<input
						type="text"
						class="wpb_vc_param_value wpb-media-input-field"
						name="' . esc_attr( $param_name ) . '"
						value="' . esc_attr( $value ) . '"
						style="width: calc(100% - 125px);"
						/>';
			
			return $output;
		}

		/*
		Load plugin css and javascript files
		*/
		public function load_scripts_and_styles() {
			wp_register_style( 'jquery-background-video', plugins_url('assets/jquery.background-video.css', __FILE__) );
			wp_register_style( 'vc_video_background', plugins_url('assets/vc_video_background.css', __FILE__) );
			wp_enqueue_style( 'jquery-background-video' );
			wp_enqueue_style( 'vc_video_background' );

			// If you need any javascript files on front end, here is how you can load them.
			wp_register_script( 'jquery-background-video', plugins_url('assets/jquery.background-video.js', __FILE__), array('jquery'), '1.1.1', true );
			wp_enqueue_script( 'jquery-background-video' );
		}

		/*
		Show notice if  plugin is activated but Visual Composer is not
		*/
		public function show_vc_version_notice() {
			$plugin_data = get_plugin_data(__FILE__);
			echo '
			<div class="updated">
				<p>'.sprintf(__('<strong>%s</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'vc_extend'), $plugin_data['Name']).'</p>
			</div>';
		}

	}

	// Finally initialize code
	new VC_Video_Background();

	// Do the things after row shortcode
	if ( !function_exists( 'vc_theme_after_vc_row' ) ) {
		function vc_theme_after_vc_row($atts, $content = null) {
			return VC_Video_Background::video_shortcode($atts, $content);
		}
	}

}
