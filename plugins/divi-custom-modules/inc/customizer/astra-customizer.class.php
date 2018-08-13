<?php

/**
 *
 */
class Astra_Customizer
{

  function __construct()
  {
    add_action( 'customize_register', array( $this, 'register_customize_sections' ) );
  }
   public function register_customize_sections($wp_customize)
  {
    $wp_customize->add_section( 'colors_astra', array(
		'title'    => __( 'Divi Custom Blog Layout', 'dcm' ),
		'priority' => 101
	) );
  $this->colours_section( $wp_customize );
  }
  private function colours_section( $wp_customize ) {
    //SETTINGS//
    $wp_customize->add_setting( 'cat_tag', array(
  	'default'           => '#16a085',
  	'sanitize_callback' => 'sanitize_hex_color'
  ) );
  $wp_customize->add_setting( 'custom_meta_module', array(
  'default'           => '#f5f5f5',
  'sanitize_callback' => 'sanitize_hex_color'
  ) );
  $wp_customize->add_setting( 'custom_meta_module_color', array(
  'default'           => '#8a8a8a',
  'sanitize_callback' => 'sanitize_hex_color'
  ) );
  $wp_customize->add_setting( 'grid_title_bg', array(
  'default'           => '#16a085',
  'sanitize_callback' => 'sanitize_hex_color'
  ) );
    $wp_customize->add_setting( 'about_bg', array(
    'default'           => '#16a085',
    'sanitize_callback' => 'sanitize_hex_color'
  ) );
  $wp_customize->add_setting( 'about_name_color', array(
  'default'           => '#fff',
  'sanitize_callback' => 'sanitize_hex_color'
  ) );
  $wp_customize->add_setting( 'about_pos_color', array(
  'default'           => '#fff',
  'sanitize_callback' => 'sanitize_hex_color'
  ) );
  $wp_customize->add_setting( 'about_content_color', array(
  'default'           => '#fff',
  'sanitize_callback' => 'sanitize_hex_color'
  ) );
  $wp_customize->add_setting( 'about_social_icons_color', array(
  'default'           => '#fff',
  'sanitize_callback' => 'sanitize_hex_color'
  ) );
  $wp_customize->add_setting( 'wid_title_bg', array(
  'default'           => '#fff',
  'sanitize_callback' => 'sanitize_hex_color'
  ) );
  $wp_customize->add_setting( 'all_wid_title_color', array(
  'default'           => '#000',
  'sanitize_callback' => 'sanitize_hex_color'
  ) );
  $wp_customize->add_setting( 'wid_title_color', array(
  'default'           => '#fff',
  'sanitize_callback' => 'sanitize_hex_color'
  ) );
  $wp_customize->add_setting( 'wid_title_bg_link', array(
  'default'           => '#16a085',
  'sanitize_callback' => 'sanitize_hex_color'
  ) );
  //CONTROL//
  $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cat_tag', array(
  	'label'    => esc_html__( 'Category Tag Color', 'dcm' ),
  	'section'  => 'colors_astra',
  	'settings' => 'cat_tag',
  	'priority' => 10
  ) ) );
  $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'custom_meta_module', array(
    'label'    => esc_html__( 'Custom Post Meta Module Backgound', 'dcm' ),
    'section'  => 'colors_astra',
    'settings' => 'custom_meta_module',
    'priority' => 10
  ) ) );
  $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'custom_meta_module_color', array(
    'label'    => esc_html__( 'Custom Post Meta Module Color', 'dcm' ),
    'section'  => 'colors_astra',
    'settings' => 'custom_meta_module_color',
    'priority' => 10
  ) ) );
  $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'grid_title_bg', array(
    'label'    => esc_html__( 'Grid Post Title Background', 'dcm' ),
    'section'  => 'colors_astra',
    'settings' => 'grid_title_bg',
    'priority' => 10
  ) ) );
  $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'about_bg', array(
    'label'    => esc_html__( 'About Widget Background', 'dcm' ),
    'section'  => 'colors_astra',
    'settings' => 'about_bg',
    'priority' => 10
  ) ) );
  $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'about_name_color', array(
    'label'    => esc_html__( 'About Widget Name Font Color', 'dcm' ),
    'section'  => 'colors_astra',
    'settings' => 'about_name_color',
    'priority' => 10
  ) ) );
  $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'about_pos_color', array(
    'label'    => esc_html__( 'About Widget Position Font Color', 'dcm' ),
    'section'  => 'colors_astra',
    'settings' => 'about_pos_color',
    'priority' => 10
  ) ) );
  $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'about_content_color', array(
    'label'    => esc_html__( 'About Widget Content Font Color', 'dcm' ),
    'section'  => 'colors_astra',
    'settings' => 'about_content_color',
    'priority' => 10
  ) ) );
  $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'about_social_icons_color', array(
    'label'    => esc_html__( 'About Widget Social Icons Color', 'dcm' ),
    'section'  => 'colors_astra',
    'settings' => 'about_social_icons_color',
    'priority' => 10
  ) ) );
  $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wid_title_bg', array(
    'label'    => esc_html__(  '(All)Widgets Title Background', 'dcm' ),
    'section'  => 'colors_astra',
    'settings' => 'wid_title_bg',
    'priority' => 10
  ) ) );
  $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'all_wid_title_color', array(
    'label'    => esc_html__(  '(All)Widgets Title Color', 'dcm' ),
    'section'  => 'colors_astra',
    'settings' => 'all_wid_title_color',
    'priority' => 10
  ) ) );
  $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wid_title_color', array(
    'label'    => esc_html__( '(Latest - Popular)Posts Widget Link color', 'dcm' ),
    'section'  => 'colors_astra',
    'settings' => 'wid_title_color',
    'priority' => 10
  ) ) );
  $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wid_title_bg_link', array(
    'label'    => esc_html__( 'Posts Widget Link Background', 'dcm' ),
    'section'  => 'colors_astra',
    'settings' => 'wid_title_bg_link',
    'priority' => 10
  ) ) );

}
}
