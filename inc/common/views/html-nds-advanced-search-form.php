<?php
/*
 * Markup for the custom search form goes here.
 *
 * Note: Form input is stored inside an array with the plugin's name
 * e.g. $_POST['plugin_name']
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<div class="nds-advanced-search-form-container">
	<form id="nds-advanced-search-form" role="search" method="POST" class="search-form" action="">
		<button class="nds-search-button" type="submit" class="search-submit"><i class="fa fa-search" aria-hidden="true"><span class="screen-reader-text"><?php echo esc_html_x( 'Search', 'submit button', '$this->plugin_text_domain' ); ?></span></i></button>
		<div class="nds-input-container">
			<label>
				<span class="screen-reader-text"><?php echo esc_attr_x( 'Search for:', 'label', $this->plugin_text_domain ); ?></span>
				<input required class="nds-search-input" id="nds-search-box" type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'start typing for suggestions &hellip;', 'placeholder', $this->plugin_text_domain ); ?>" name="<?php echo esc_attr( $this->plugin_name ); ?>[search_key]" />
			</label>
		</div> <!-- nds-input-container -->
	</form> <!-- nds-advanced-search-for -->
</div> <!-- nds-advanced-search-form-container -->
