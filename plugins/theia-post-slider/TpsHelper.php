<?php

/*
 * Copyright 2012-2017, Theia Post Slider, WeCodePixels, http://wecodepixels.com
 */

class TpsHelper {
	/**
	 * Get the HTML for a navigation bar. Must be called AFTER/BELOW the post content.
	 *
	 * @param string|array $classes Any additional CSS classes to be added.
	 *
	 * @return string
	 */
	public static function get_navigation_bar( $classes = array() ) {
		global $post, $page, $pages;

		if ( !TpsMisc::is_compatible_post() ) {
			return '';
		}

		return TpsNavigationBar::get_navigation_bar( array(
			'currentSlide' => $page,
			'totalSlides'  => count( $pages ),
			'prevPostUrl'  => isset($post->theiaPostSlider['prevPostUrl']) ? $post->theiaPostSlider['prevPostUrl'] : '',
			'nextPostUrl'  => isset($post->theiaPostSlider['nextPostUrl']) ? $post->theiaPostSlider['nextPostUrl'] : '',
//			'prevPostUrl'  => $post->theiaPostSlider['prevPostUrl'],
//			'nextPostUrl'  => $post->theiaPostSlider['nextPostUrl'],
			'class'        => $classes,
			'title'        => TpsMisc::$current_post_title
		) );
	}

	/**
	 * @param array $classes
	 *
	 * @return string
	 *
	 * @deprecated since 1.7.0
	 */
	public static function getNavigationBar( $classes = array() ) {
		return self::get_navigation_bar( $classes );
	}
}