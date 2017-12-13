<?php

/*
 * Markup for the custom search form goes here.
 */
?>

<div class="nds-advanced-search-form-container">
	<form id="nds-advanced-search-form" role="search" method="POST" class="search-form" action="">
		<button class="nds-search-button" type="submit" class="search-submit"><i class="fa fa-search" aria-hidden="true"><span class="screen-reader-text"><?php echo _x( 'Search', 'submit button', '$this->plugin_text_domain' ); ?></span></i></button>
		<div class="nds-input-container">
			<label>
				<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', $this->plugin_text_domain ); ?></span>
				<input class="nds-search-input" id="nds-search-box" type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'start typing for suggestions &hellip;', 'placeholder', $this->plugin_text_domain ); ?>" name="search_key" />
			</label>
		</div>
	</form>
</div>
