<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://nuancedesignstudio.in
 * @since      1.0.0
 *
 * @package    NDS_Advanced_Search
 * @subpackage NDS_Advanced_Search/inc/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<h4 class="nav-tab-wrapper"><?php echo esc_html( 'Select Post Types to Include in the Advanced Search', $this->plugin_text_domain ); ?></h4>
	<br>
	<form method="post" name="<?php echo $this->plugin_name; ?>_search_options" action="options.php">
	<?php

		$plugin_options = get_option( $this->plugin_name );

		// add the nonce, option_page, action and referer.
		settings_fields( $this->plugin_name );
		do_settings_sections( $this->plugin_name );

		// manually add only posts and pages.
		$posts_array = array(
			'name' => 'post',
			'label' => 'Posts',
		);
		$pages_array = array(
			'name' => 'page',
			'label' => 'Pages',
		);

		$args = array(
			'public' => true,
			'_builtin' => false, //exclude attachment, revision etc.
		);

		// append posts and pages to the post types.
		$post_types = get_post_types( $args, 'objects' );
		$post_types['post'] = (object) $posts_array;
		$post_types['page'] = (object) $pages_array;

		foreach ( $post_types  as $post_type ) {
			$the_post_type = $post_type->name;
			$the_post_type_label = $post_type->label;
			$option_checked = isset( $plugin_options[ $the_post_type ] ) ? $plugin_options[ $the_post_type ] : 0;
			?>
			<fieldset>
				<legend class="screen-reader-text"><span><?php echo esc_attr__( 'Setting for ', $this->plugin_text_domain ) . $the_post_type_label; ?></span></legend>
				<label for="<?php echo esc_attr( $this->plugin_name . '_' . $the_post_type ); ?>">
					<input type="checkbox" id="<?php echo esc_attr( $this->plugin_name . '-' . $the_post_type ); ?>" name="<?php echo $this->plugin_name . '[' . esc_attr( $the_post_type ) . ']'; ?>" value="<?php echo esc_attr( $the_post_type ); ?>" <?php checked( $option_checked, 1, true ); ?> />
					<span><?php echo esc_attr__( 'Include ', $this->plugin_text_domain ) . $the_post_type_label; ?></span>
				</label>
			</fieldset>
		<?php

		}
		?>
		<?php submit_button( 'Save all changes', 'primary','submit', true ); ?>

	</form>

</div>
