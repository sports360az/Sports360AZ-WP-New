<?php
/**
 * Created by PhpStorm.
 * User: Fred
 * Date: 18/08/2016
 * Time: 01:58
 */
add_action( 'admin_menu', 'dcm_add_admin_menu' );
add_action( 'admin_init', 'dcm_settings_init' );


function dcm_add_admin_menu(  ) {
	add_menu_page( 'Divi Custom Modules', 'Divi Custom Modules', 'manage_options', 'divi_custom_modules', 'dcm_options_page' );
}

/**
 *
 */
function dcm_settings_init(  ) {
    register_setting( 'pluginPage', 'dcm_settings' );

    add_settings_section(
        'dcm_pluginPage_section',
        __( 'DCM Options', 'wordpress' ),
        'dcm_settings_section_callback',
        'pluginPage'
    );
    add_settings_section(
        'dcm_pluginPage_section_two',
        __( 'DCM Options - Custom Meta', 'wordpress' ),
        'dcm_settings_section__meta_callback',
        'pluginPage'
    );
    add_settings_field(
        'facebook',
        __( 'Facebook', 'wordpress' ),
        'facebook_render',
        'pluginPage',
        'dcm_pluginPage_section'
    );

    add_settings_field(
        'google',
        __( 'Google+', 'wordpress' ),
        'googleplus_render',
        'pluginPage',
        'dcm_pluginPage_section'
    );
    add_settings_field(
        'twitter',
        __( 'Twitter', 'wordpress' ),
        'twitter_render',
        'pluginPage',
        'dcm_pluginPage_section'
    );
    add_settings_field(
        'linkedin',
        __( 'Linkedin', 'wordpress' ),
        'linkedin_render',
        'pluginPage',
        'dcm_pluginPage_section'
    );
    add_settings_field(
        'email',
        __( 'Email', 'wordpress' ),
        'email_render',
        'pluginPage',
        'dcm_pluginPage_section'
    );
		add_settings_field(
		'whatsapp',
		__( 'Whatsapp', 'wordpress' ),
		'whatsapp_render',
		'pluginPage',
		'dcm_pluginPage_section'
);
    add_settings_field(
        'author',
        __( 'Author', 'wordpress' ),
        'author_render',
        'pluginPage',
        'dcm_pluginPage_section_two'
    );
    add_settings_field(
        'date',
        __( 'Date', 'wordpress' ),
        'date_render',
        'pluginPage',
        'dcm_pluginPage_section_two'
    );
    add_settings_field(
        'comments',
        __( 'Comments', 'wordpress' ),
        'comments_render',
        'pluginPage',
        'dcm_pluginPage_section_two'
    );
}
function facebook_render(  ) {

    $options = get_option( 'dcm_settings' );
    ?>
	<input type='checkbox' name='dcm_settings[facebook]' value='1' <?php if (isset($options['facebook']) && $options['facebook'] == 1) echo 'checked="checked"'; ?> />
    <?php

}


function googleplus_render(  )
{
    $options = get_option('dcm_settings');
    ?>

    <input type='checkbox' name='dcm_settings[google]' value='1' <?php if (isset($options['google']) && $options['google'] == 1) echo 'checked="checked"'; ?> />
    <?php
}
function twitter_render(  )
{
    $options = get_option('dcm_settings');
    ?>

    <input type='checkbox' name='dcm_settings[twitter]' value='1' <?php if (isset($options['twitter']) && $options['twitter'] == 1) echo 'checked="checked"'; ?> />
    <?php
}
function linkedin_render(  )
{
    $options = get_option('dcm_settings');
    ?>

    <input type='checkbox' name='dcm_settings[linkedin]' value='1' <?php if (isset($options['linkedin']) && $options['linkedin'] == 1) echo 'checked="checked"'; ?> />
    <?php
}
function email_render(  )
{
    $options = get_option('dcm_settings');
    ?>

    <input type='checkbox' name='dcm_settings[email]' value='1' <?php if (isset($options['email']) && $options['email'] == 1) echo 'checked="checked"'; ?> />
    <?php
}
function whatsapp_render(  )
{
    $options = get_option('dcm_settings');
    ?>

    <input type='checkbox' name='dcm_settings[whatsapp]' value='1' <?php if (isset($options['whatsapp']) && $options['whatsapp'] == 1) echo 'checked="checked"'; ?> />
    <?php
}

function author_render(  )
{
    $options = get_option('dcm_settings');
    ?>

    <input type='checkbox' name='dcm_settings[author]' value='1' <?php if (isset($options['whatsapp']) && $options['author'] == 1) echo 'checked="checked"'; ?> />
    <?php
}
function date_render(  )
{
    $options = get_option('dcm_settings');
    ?>

    <input type='checkbox' name='dcm_settings[date]' value='1' <?php if (isset($options['date']) && $options['date'] == 1) echo 'checked="checked"'; ?> />
    <?php
}
function comments_render(  )
{
    $options = get_option('dcm_settings');
    ?>

    <input type='checkbox' name='dcm_settings[comments]' value='1' <?php if (isset($options['comments']) && $options['comments'] == 1) echo 'checked="checked"'; ?> />
    <?php
}
function dcm_settings_section_callback(  ) {

    echo __( 'Enable/Disabile Social Share Icons', 'wordpress' );

}

function dcm_settings_section__meta_callback (){
    echo __('Enable/Disabile Custom Meta Fields', 'wordpress');
}

function Social_Share(){
    $options = get_option('dcm_settings');
		echo '<div id="social-links" class="blog-social">';
    if (isset($options['facebook']) && $options['facebook'] == 1): ?>
        <a class="facebook" href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&amp;t=<?php the_title(); ?>" target="blank"><i class="fa fa-facebook"></i></a>
    <?php endif; ?>
    <?php  if (isset($options['google']) && $options['google'] == 1): ?>
        <a class="google" href="https://plusone.google.com/_/+1/confirm?hl=en-US&amp;url=<?php the_permalink(); ?>" target="_blank"><i class="fa fa-google"></i></a>
    <?php endif; ?>
    <?php  if (isset($options['twitter']) && $options['twitter'] == 1): ?>
        <a class="twitter" href="https://twitter.com/intent/tweet?original_referer=<?php the_permalink(); ?>&amp;text=<?php the_title(); ?>&amp;tw_p=tweetbutton&amp;url=<?php the_permalink(); ?><?php echo isset( $twitter_user ) ? '&amp;via='.$twitter_user : ''; ?>" target="_blank"><i class="fa fa-twitter"></i></a>
    <?php endif; ?>
    <?php  if (isset($options['linkedin']) && $options['linkedin'] == 1): ?>
        <a class="linkedin" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>&amp;source=<?php bloginfo( 'name' ); ?>"><i class="fa fa-linkedin"></i></a>
    <?php endif; ?>
    <?php  if (isset($options['email']) && $options['email'] == 1): ?>
        <a class="email" href="mailto:?subject=<?php the_title(); ?>&amp;body=<?php the_permalink(); ?>"><i class="fa fa-envelope"></i></a>
			<?php endif; ?>
		<?php  if (isset($options['whatsapp']) && $options['whatsapp'] == 1): ?>
		<a class="whatsapp" href="whatsapp://send?text=<?php the_permalink( ); ?>"><i class="fa fa-whatsapp"></i></a>
	<?php endif;
	echo '</div>';
}

function Custom_Meta () {
    $options = get_option('dcm_settings');
	echo '<div class="blog-p-meta">';
    if (isset($options['author']) && $options['author'] == 1): ?>
        <span class="blog-author"> <i class="fa fa-pencil"></i><?php _e('by ','DIVI_TEXT_DOMAIN'); ?><?php the_author_posts_link(); ?> </span>
        <?php endif; ?>
    <?php if (isset($options['date']) && $options['date'] == 1): ?>
        <span class="blog-date"> <i class="fa fa-clock-o"></i><?php the_time('M d, Y') ?></span>
    <?php endif; ?>
    <?php if (isset($options['comments']) && $options['comments'] == 1): ?>
        <span class="blog-comments"> <i class="fa fa-comment"></i><?php comments_number(  ); ?></span>
    <?php endif;
	echo '</div>';
}

function dcm_options_page() {

    ?>
    <form action='options.php' method='post'>

        <h2>Divi Custom Modules</h2>

        <?php
        settings_fields( 'pluginPage' );
        do_settings_sections( 'pluginPage' );
        submit_button();
        ?>

    </form>
    <?php
}
