<?php

/**
 * Created by PhpStorm.
 * User: Fred
 * Date: 16/08/2016
 * Time: 01:15
 */
class DCM_Latest_Posts extends WP_Widget{

	function __construct() {
		$params = array(
			'description' => 'Display Latest Posts',
			'name'        => 'Divichild Latest Posts'
		);
		parent::__construct( 'DCM_Latest_Posts', '', $params );

	}

	public function form( $instance ) {
		extract( $instance );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ) ?>">Title:</label>
			<input
				type="text"
				class="widefat"
				id="<?php echo $this->get_field_id( 'title' ) ?>"
				name="<?php echo $this->get_field_name( 'title' ) ?>"
				value="<?php if ( isset( $title ) ) {
					echo esc_attr( $title );
				} ?>"
			/>

		<p>
			<label for="<?php echo $this->get_field_id( 'numberOfPosts' ) ?>">Number of Posts:</label>
			<input
				type="text"
				class="widefat"
				id="<?php echo $this->get_field_id( 'numberOfPosts' ) ?>"
				name="<?php echo $this->get_field_name( 'numberOfPosts' ) ?>"
				value="<?php if ( isset( $numberOfPosts ) ) {
					echo esc_attr( $numberOfPosts );
				} ?>"
			/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'categ' ) ?>">Category:</label>
			<input
				type="text"
				class="widefat"
				id="<?php echo $this->get_field_id( 'categ' ) ?>"
				name="<?php echo $this->get_field_name( 'categ' ) ?>"
				value="<?php if ( isset( $categ ) ) {
					echo esc_attr( $categ );
				} ?>"
			/>
		</p>
		<?php
	}


	public function widget( $args, $instance ) {
		extract( $args );
		extract( $instance );
		if ( ! isset( $title ) ) {
			$title = '';
		}
		if ( ! isset( $numberOfPosts ) ) {
			$numberOfPosts = 5;
		}
		if ( ! isset( $categ ) ) {
			$categ = '';
		}
		?>
		<?php echo $before_widget; ?>
		<?php
		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}
		?>
		<div class="side-list">
			<ul>
				<?php
				$wpbp = new WP_Query( array( 'post_type'      => 'post',
				                             'paged'          => 1,
				                             'posts_per_page' => $numberOfPosts,
				                             'order'          => 'DESC',
				                             'category_name'  => $categ
				) );
				$temp_out = "";
				if ( $wpbp->have_posts() ) : while ( $wpbp->have_posts() ) : $wpbp->the_post();
					?>
					<li>
						<div class="wid_img_container">
						<a class="pop-posts-title" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium_crop'); the_title(); ?></a>
						</div>
					</li>
					<?php
				endwhile; endif;
				wp_reset_query();
				?>


			</ul>
		</div>

		<?php echo $after_widget; ?><!-- Disclaimer -->
		<?php
	}
}

add_action( 'widgets_init', 'register_divichild_LatestPosts' );
function register_divichild_LatestPosts() {
	register_widget( 'DCM_Latest_Posts' );

}

/*---END CUSTOM LATEST POSTS WIDGET---*/
