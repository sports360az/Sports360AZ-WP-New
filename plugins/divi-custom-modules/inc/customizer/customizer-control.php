<?php
function astra_customizer_css (){
  $css = '';

  $cat_tag = get_theme_mod( 'cat_tag', '#16a085');
  $custom_meta_module = get_theme_mod ('custom_meta_module', '#f5f5f5');
  $custom_meta_module_color = get_theme_mod ('custom_meta_module_color', '#8a8a8a');
  $grid_title_bg = get_theme_mod( 'grid_title_bg', '#16a085');
  $about_bg = get_theme_mod( 'about_bg', '#16a085');
  $about_name_color = get_theme_mod( 'about_name_color', '#fff');
  $about_pos_color = get_theme_mod( 'about_pos_color', '#fff');
  $about_content_color = get_theme_mod( 'about_content_color', '#fff');
  $about_social_icons_color = get_theme_mod( 'about_social_icons_color', '#fff');
  $wid_title_bg = get_theme_mod ('wid_title_bg', '#fff');
  $all_wid_title_color = get_theme_mod ('all_wid_title_color', '#000000');
  $wid_title_color = get_theme_mod ('wid_title_color', '#fff');
  $wid_title_bg_link = get_theme_mod ('wid_title_bg_link', '#16a085');

  $css .= 'span.blog-cat-top a {background: ' . $cat_tag .';}';
  $css .= '.blog-p-meta {background: ' . $custom_meta_module . ';}';
  $css .= '.blog-p-meta span, .blog-p-meta span a {color: ' . $custom_meta_module_color . ';}';
  $css .= '.et_pb_blog_grid.custom_title h2 {background: ' . $grid_title_bg .';}';
  $css .= '.astra-about {background: ' . $about_bg .';}';
  $css .= '.astra-about h4 {color: ' . $about_name_color .';}';
  $css .= '.astra-about h5 {color: ' . $about_pos_color .';}';
  $css .= '.astra-about p {color: ' . $about_content_color .';}';
  $css .= '#astra-social-links li a {color: ' . $about_social_icons_color .';}';
  $css .= 'h4.widgettitle {background: ' . $wid_title_bg . '; color: ' . $all_wid_title_color . ';}';
  $css .= '.et_pb_widget a.pop-posts-title {color: ' . $wid_title_color . '!important;}';
  $css .= '.et_pb_widget a.pop-posts-title {background: ' . $wid_title_bg_link . ';}';

  return $css;

}
