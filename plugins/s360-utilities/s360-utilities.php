<?php
/*
Plugin Name: sports360 Widgets
Description: Installs widgets & plugins for sports360 website
Version: 1.0
Author: Kyle Cesmat
*/

// 01 - Sports360 Social Widget
// 02 - Sports360 Latest Category Widget

///////////////////// Sports360 SOCIAL WIDGET START //////////////////

class sports360_social_widget extends WP_Widget {
function __construct() {
parent::__construct(
// Base ID of your widget
'sports360_social_widget',
// Widget name will appear in UI
__('Social Sites', 'sports360_social_widget_domain'),
// Widget description
array( 'description' => __( 'A theme widget to display your social sites', 'sports360_social_widget_domain' ), )
);

}

public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
$twitter = apply_filters( 'twitter', $instance['twitter'] );
$facebook = apply_filters( 'facebook', $instance['facebook'] );
$instagram = apply_filters( 'instagram', $instance['instagram'] );
$youtube = apply_filters( 'youtube', $instance['youtube'] );
$gplus = apply_filters( 'gplus', $instance['gplus'] );

echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

?>
<div class="social-sidebar small-12 columns">
    <ul class="social-btns inline-list">
        <?php if ( ! empty( $twitter ) ) echo '<li><a href="https://twitter.com/' . $twitter . '" id="twitter" target="_blank"><i class="fa fa-twitter fa-2x"></i></a></li>' ?>

        <?php if ( ! empty( $facebook ) ) echo '<li><a href="https://facebook.com/' . $facebook . '" id="facebook" target="_blank"><i class="fa fa-facebook fa-2x"></i></a></li>' ?>

        <?php if ( ! empty( $instagram ) ) echo '<li><a href="https://instagram.com/' . $instagram . '" id="instagram" target="_blank"><i class="fa fa-instagram fa-2x"></i></a></li>' ?>

        <?php if ( ! empty( $youtube ) ) echo '<li><a href="https://www.youtube.com/user/' . $youtube . '" id="youtube" target="_blank"><i class="fa fa-youtube fa-2x"></i></a></li>' ?>

        <?php if ( ! empty( $gplus ) ) echo '<li><a href="https://plus.google.com/' . $gplus . '" id="gplus" target="_blank"><i class="fa fa-google-plus fa-2x"></i></a></li>' ?>

    </ul>
</div>

<?php
echo $args['after_widget'];
}

// Widget Backend
public function form( $instance ) {
// Title
if ( isset( $instance[ 'title' ] ) ) {
    $title = $instance[ 'title' ];
}

// Facebook
if ( isset( $instance[ 'facebook' ] ) ) {
    $facebook = $instance[ 'facebook' ];
}
else {
    $facebook = __( '', 'sports360_social_widget_domain' );
}

// Twitter
if ( isset( $instance[ 'twitter' ] ) ) {
    $twitter = $instance[ 'twitter' ];
}
else {
    $twitter = __( '', 'sports360_social_widget_domain' );
}

// instagram
if ( isset( $instance[ 'instagram' ] ) ) {
    $instagram = $instance[ 'instagram' ];
}
else {
    $instagram = __( '', 'sports360_social_widget_domain' );
}

// youtube
if ( isset( $instance[ 'youtube' ] ) ) {
    $youtube = $instance[ 'youtube' ];
}
else {
    $youtube = __( '', 'sports360_social_widget_domain' );
}

// gplus
if ( isset( $instance[ 'gplus' ] ) ) {
    $gplus = $instance[ 'gplus' ];
}
else {
    $gplus = __( '', 'sports360_social_widget_domain' );
}


// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Widget Title' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id( 'facebook' ); ?>"><?php _e( 'Facebook Username:' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'facebook' ); ?>" name="<?php echo $this->get_field_name( 'facebook' ); ?>" type="text" value="<?php echo esc_attr( $facebook ); ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id( 'twitter' ); ?>"><?php _e( 'Twitter Username:' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'twitter' ); ?>" name="<?php echo $this->get_field_name( 'twitter' ); ?>" type="text" value="<?php echo esc_attr( $twitter ); ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id( 'instagram' ); ?>"><?php _e( 'Instagram Username:' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'instagram' ); ?>" name="<?php echo $this->get_field_name( 'instagram' ); ?>" type="text" value="<?php echo esc_attr( $instagram ); ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id( 'youtube' ); ?>"><?php _e( 'Youtube Username:' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'youtube' ); ?>" name="<?php echo $this->get_field_name( 'youtube' ); ?>" type="text" value="<?php echo esc_attr( $youtube ); ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id( 'gplus' ); ?>"><?php _e( 'Google+ Username:' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'gplus' ); ?>" name="<?php echo $this->get_field_name( 'gplus' ); ?>" type="text" value="<?php echo esc_attr( $gplus ); ?>" />
</p>
<?php
}

// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
$instance['facebook'] = ( ! empty( $new_instance['facebook'] ) ) ? strip_tags( $new_instance['facebook'] ) : '';
$instance['twitter'] = ( ! empty( $new_instance['twitter'] ) ) ? strip_tags( $new_instance['twitter'] ) : '';
$instance['instagram'] = ( ! empty( $new_instance['instagram'] ) ) ? strip_tags( $new_instance['instagram'] ) : '';
$instance['youtube'] = ( ! empty( $new_instance['youtube'] ) ) ? strip_tags( $new_instance['youtube'] ) : '';
$instance['gplus'] = ( ! empty( $new_instance['gplus'] ) ) ? strip_tags( $new_instance['gplus'] ) : '';
return $instance;
}
} // Class sports360_author_widget ends here

// Register and load the widget
function sports360_social_load_widget() {
    register_widget( 'sports360_social_widget' );
}
add_action( 'widgets_init', 'sports360_social_load_widget' );

////////////////// sports360 SOCIAL WIDGET END //////////////////
///
///
///
///
///
////////////////// Sports360 LATEST CATEGORY WIDGET START //////////////////

class sports360_latest_cat_widget extends WP_Widget {
function __construct() {
parent::__construct(
// Base ID of your widget
'sports360_latest_cat_widget',
// Widget name will appear in UI
__('Latest Category', 'sports360_latest_cat_widget_domain'),
// Widget description
array( 'description' => __( 'Widget Displays X Latest from X Category', 'sports360_latest_cat_domain' ), )
);

}

public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
$categoryID = apply_filters( 'categoryID', $instance['categoryID'] );
$numPosts = apply_filters( 'numPosts', $instance['numPosts'] );

echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

    // The Query
    $args = array(
        'cat'                    => $categoryID,
        'posts_per_page'         => $numPosts,
        'update_post_meta_cache' => false
    );

    $the_query = new WP_Query( $args );

    // The Loop
    echo '<ul class="latest-category layout__content layout__panel">';
    while ( $the_query->have_posts() ) {
        $the_query->the_post();
        echo '<li class="latest-category__item">';
        ?><a href="<?php the_permalink(); ?>"><?php
        echo the_post_thumbnail('article-square');
        echo '<span class="latest-category__text">' . get_the_title() . '</span>' .'</a></li>';
    }
    echo '</ul>';
    /* Restore original Post Data */
    wp_reset_postdata();

echo $args['after_widget'];
}

// Widget Backend
public function form( $instance ) {
// Title
if ( isset( $instance[ 'title' ] ) ) {
    $title = $instance[ 'title' ];
}

// Category ID
if ( isset( $instance[ 'categoryID' ] ) ) {
    $categoryID = $instance[ 'categoryID' ];
}
else {
    $categoryID = __( '1', 'sports360_latest_cat_widget_domain' );
}

// Number of Posts
if ( isset( $instance[ 'numPosts' ] ) ) {
    $numPosts = $instance[ 'numPosts' ];
}
else {
    $numPosts = __( '5', 'sports360_latest_cat_widget_domain' );
}

// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Widget Title' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id( 'categoryID' ); ?>"><?php _e( 'Category ID(s):' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'categoryID' ); ?>" name="<?php echo $this->get_field_name( 'categoryID' ); ?>" type="text" placeholder="1,17,33" value="<?php echo esc_attr( $categoryID ); ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id( 'numPosts' ); ?>"><?php _e( 'Number of Posts:' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'numPosts' ); ?>" name="<?php echo $this->get_field_name( 'numPosts' ); ?>" type="text" placeholder="10" value="<?php echo esc_attr( $numPosts ); ?>" />
</p>
<?php
}

// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
$instance['categoryID'] = ( ! empty( $new_instance['categoryID'] ) ) ? strip_tags( $new_instance['categoryID'] ) : '';
$instance['numPosts'] = ( ! empty( $new_instance['numPosts'] ) ) ? strip_tags( $new_instance['numPosts'] ) : '';
return $instance;
}
} // Class sports360_author_widget ends here

// Register and load the widget
function sports360_latest_cat_load_widget() {
    register_widget( 'sports360_latest_cat_widget' );
}
add_action( 'widgets_init', 'sports360_latest_cat_load_widget' );

////////////////// sports360 AUTHOR WIDGET END //////////////////

