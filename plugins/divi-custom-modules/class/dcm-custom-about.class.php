<?php

/**
 * Created by PhpStorm.
 * User: Fred
 * Date: 16/08/2016
 * Time: 01:30
 */
class Astra_About extends WP_Widget{

	function __construct() {
		$params = array(
			'description' => 'DCM About Widget',
			'name'        => 'DCM-About'
		);
		parent::__construct( 'Astra_Custom_About', '', $params );

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
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'Name' ) ?>">Name:</label>
			<input type="text"
			       class="widefat"
			       id="<?php echo $this->get_field_id( 'name' ) ?>"
			       name="<?php echo $this->get_field_name( 'name' ) ?>"

			       value="<?php if ( isset( $name ) ) {
				       echo esc_attr( $name );
			       } ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'Position' ) ?>">Position:</label>
			<input type="text"
			       class="widefat"
			       id="<?php echo $this->get_field_id( 'position' ) ?>"
			       name="<?php echo $this->get_field_name( 'position' ) ?>"

			       value="<?php if ( isset( $position ) ) {
				       echo esc_attr( $position );
			       } ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'imageurl' ) ?>">Image URL:</label>
			<input type="text"

			       class="widefat"
			       id="<?php echo $this->get_field_id( 'imageurl' ) ?>"
			       name="<?php echo $this->get_field_name( 'imageurl' ) ?>"

			       value="<?php if ( isset( $imageurl ) ) {
				       echo esc_attr( $imageurl );
			       } ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'description' ) ?>">Description:</label>
			<textarea
				class="widefat"
				id="<?php echo $this->get_field_id( 'description' ) ?>"
				name="<?php echo $this->get_field_name( 'description' ) ?>"

			><?php if ( isset( $description ) ) {
					echo esc_attr( $description );
				} ?></textarea>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'facebook' ) ?>">Facebook:</label>
			<input type="text"
			       class="widefat"
			       id="<?php echo $this->get_field_id( 'facebook' ) ?>"
			       name="<?php echo $this->get_field_name( 'facebook' ) ?>"

			       value="<?php if ( isset( $facebook ) ) {
				       echo esc_attr( $facebook );
			       } ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'twitter' ) ?>">Twitter:</label>
			<input type="text"
			       class="widefat"
			       id="<?php echo $this->get_field_id( 'twitter' ) ?>"
			       name="<?php echo $this->get_field_name( 'twitter' ) ?>"

			       value="<?php if ( isset( $twitter ) ) {
				       echo esc_attr( $twitter );
			       } ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'googleplus' ) ?>">Google Plus:</label>
			<input type="text"
			       class="widefat"
			       id="<?php echo $this->get_field_id( 'googleplus' ) ?>"
			       name="<?php echo $this->get_field_name( 'googleplus' ) ?>"

			       value="<?php if ( isset( $googleplus ) ) {
				       echo esc_attr( $googleplus );
			       } ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'instagram' ) ?>">Instagram:</label>
			<input type="text"
			       class="widefat"
			       id="<?php echo $this->get_field_id( 'instagram' ) ?>"
			       name="<?php echo $this->get_field_name( 'instagram' ) ?>"

			       value="<?php if ( isset( $instagram ) ) {
				       echo esc_attr( $instagram );
			       } ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'linkedin' ) ?>">Linkedin:</label>
			<input type="text"
			       class="widefat"
			       id="<?php echo $this->get_field_id( 'linkedin' ) ?>"
			       name="<?php echo $this->get_field_name( 'linkedin' ) ?>"

			       value="<?php if ( isset( $linkedin ) ) {
				       echo esc_attr( $linkedin );
			       } ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'youtube' ) ?>">Youtube:</label>
			<input type="text"
			       class="widefat"
			       id="<?php echo $this->get_field_id( 'youtube' ) ?>"
			       name="<?php echo $this->get_field_name( 'youtube' ) ?>"

			       value="<?php if ( isset( $youtube ) ) {
				       echo esc_attr( $youtube );
			       } ?>"/>
		</p>
		<?php
	}


	public function widget( $args, $instance ) {
		extract( $args );
		extract( $instance );
		?>
		<?php echo $before_widget; ?>
		<?php
		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}
		?>
		<div class="astra-about">
			<?php
			if ( ! empty( $imageurl ) ) {
				echo '<img alt="" src="' . $imageurl . '" />';
			}
			if ( ! empty( $name ) ) {
				echo '<h4>' . $name . '</h4>';
			}
			if ( ! empty( $position ) ) {
				echo '<h5>' . $position . '</h5>';
			}
			if ( ! empty( $description ) ) {
				echo '<p>' . $description . '</p>';
			}
			?>
			<ul id="astra-social-links" class="et-social-icons">
				<?php
				if ( ! empty( $facebook ) ) {
					echo "<li class='et-social-icon et-social-facebook'><a href='$facebook'class='icon' target='_blank'><span>Facebook</span></a></li>";
				}
				if ( ! empty( $twitter ) ) {
					echo "<li class='et-social-icon et-social-twitter'><a href='$twitter'class='icon' target='_blank'><span>Twitter</span></a></li>";
				}
				if ( ! empty( $googleplus ) ) {
					echo "<li class='et-social-icon et-social-google-plus'><a href='$googleplus'class='icon' target='_blank'><span>Google Plus</span></a></li>";
				}
				if ( ! empty( $instagram ) ) {
					echo "<li class='et-social-icon et-social-instagram'><a href='$instagram'class='icon' target='_blank'><span>Instagram</span></a></li>";
				}
				if ( ! empty( $linkedin ) ) {
					echo "<li class='et-social-icon et-social-linkedin'><a href='$linkedin'class='icon' target='_blank'><span>Linkedin</span></a></li>";
				}
				if ( ! empty( $youtube ) ) {
					echo "<li class='et-social-icon et-social-youtube'><a href='$youtube'class='icon' target='_blank'><span>Youtube</span></a></li>";
				}
				?>
				<div class="clear"></div>
			</ul>
		</div>
		<?php echo $after_widget; ?><!-- Disclaimer -->
		<?php
	}
}

add_action( 'widgets_init', 'register_custom_divi_child_about_widget' );
function register_custom_divi_child_about_widget() {
	register_widget( 'Astra_About' );

}

/*----END CUSTOM WIDGET ---*/
