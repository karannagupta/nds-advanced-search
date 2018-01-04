<?php

namespace NDS_Advanced_Search\Inc\Common;
use NDS_Advanced_Search as NS;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the Shortcode, Search Form, Hooks and
 * the public-facing stylesheet and JavaScript.
 *
 * @link       http://nuancedesignstudio.in
 * @since      1.0.0
 *
 * @author    Karan NA Gupta
 */
class Common {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The text domain of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_text_domain    The text domain of this plugin.
	 */
	private $plugin_text_domain;

	/**
	 * The object to hold details for the plugin transient.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      Array    $plugin_transients    The transients for this plugin.
	 */
	private $plugin_transients;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 * @param string $plugin_text_domain The text domain of this plugin.
	 */
	public function __construct( $plugin_name, $version, $plugin_text_domain ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->plugin_text_domain = $plugin_text_domain;
		$this->plugin_transients = json_decode( NS\PLUGIN_TRANSIENT, true );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( 'jquery-ui', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'font-awesome.min', plugin_dir_url( dirname( __DIR__ ) ) . 'assets/css/font-awesome.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/nds-advanced-search-common.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		$transient_name = $this->plugin_transients['autosuggest_transient'];
		$cached_posts_titles = array();

		// check if cached posts are available.
		$cached_posts = get_transient( $transient_name );
		if ( $cached_posts ) {
			foreach ( $cached_posts as $index => $post ) {
				$cached_posts_titles[ $index ] = $post['title'];
			}
		}

		$params = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'cached_post_titles' => $cached_posts_titles,
		);
		wp_enqueue_script( 'nds_advanced_search', plugin_dir_url( __FILE__ ) . 'js/nds-advanced-search.js', array( 'jquery', 'jquery-ui-autocomplete' ), $this->version, true );
		wp_localize_script( 'nds_advanced_search', 'params', $params );

	}

	/**
	 * Delete cached posts from the transient when a post belonging to a
	 * post type specified in the plugin settings has been published or updated.
	 *
	 * @since 1.0.0
	 * @param string  $new_status New Post Status.
	 * @param string  $old_status Old Post Status.
	 * @param WP_Post $post  The Post Object.
	 */
	public function delete_post_cache_for_post_type( $new_status, $old_status, $post ) {

		// TODO combine transient operations in a separate class.
		$transient_name = $this->plugin_transients['autosuggest_transient'];

		$plugin_options = get_option( $this->plugin_name );
		if ( $plugin_options ) {
			$post_types = array_keys( $plugin_options, 1, true );
			foreach ( $post_types as $post_type ) {
				if ( $post_type === $post->post_type && get_transient( $transient_name ) ) {

					// delete the transitent.
					delete_transient( $transient_name );
				}
			}
		}
	}

	/**
	 * Cache WordPress posts for post types that are specified in the
	 * plugin setting to be included in the custom search.
	 *
	 * @since 1.0.0
	 */
	public function cache_posts_in_post_types() {
		$transient_name = $this->plugin_transients['autosuggest_transient'];
		$transient_expiration = $this->plugin_transients['autosuggest_transient_expiration'];

		// retrieve the selected post types from the plugin settings to include in the custom search.
		$plugin_options = get_option( $this->plugin_name );
		$post_types = array_keys( $plugin_options, 1, true );

		// check the transient for existing cached data.
		$cached_posts = get_transient( $transient_name );
		if ( false === $cached_posts ) {
			$args = array(
				'post_type'           => $post_types,
				'post_status'         => 'publish',
				'posts_per_page'      => -1,
				'no_found_rows'       => true, // true by default.
				'suppress_filters'    => false, // true by default.
				'ignore_sticky_posts' => true, // true by default.
			);

			// get_posts() to retrieve posts belonging to the required post types.
			$posts_in_required_post_types = get_posts( $args );

			// Check if posts were found.
			if ( $posts_in_required_post_types ) {
				foreach ( $posts_in_required_post_types as $key => $post ) {

					// cache the post titles and post ids.
					$cached_post = array(
						'id' => $post->ID,
						'title' => esc_html( $post->post_title ),
					);
					$cached_posts[] = $cached_post;
				}

				/**
				 * Save the post data in a transient.
				 * For better performance cache only the post ids, titles
				 * instead of the entire WP Query.
				 */
				set_transient( $transient_name, $cached_posts, $transient_expiration );
			}
		}
		return $cached_posts;
	}

	/**
	 * AJAX handler for the auto-suggest.
	 *
	 * Callback for the "wp_ajax_nds_advanced_search_autosuggest" and
	 * "wp_ajax_nopriv_nds_advanced_search_autosuggest" hooks in "class-init.php"
	 *
	 * @since    1.0.0
	 */
	public function advanced_search_autosuggest_handler() {

		$transient_name = $this->plugin_transients['autosuggest_transient'];

		// check if cached posts are available.
		$cached_posts = get_transient( $transient_name );
		if ( false === $cached_posts ) {

			// retrieve posts by running a new query and cache the posts in the transient as well.
			$cached_posts = $this->cache_posts_in_post_types();
		}

		$cached_post_titles = array();
		foreach ( $cached_posts as $index => $post ) {
			$cached_post_titles[ $index ] = $post['title'];
		}

		// Echo the response to the AJAX request.
		wp_send_json( $cached_post_titles );

		// wp_send_json will also die().
	}

	/**
	 * Override get_search_form HTML markup.
	 *
	 * @since 1.0.0
	 *
	 * @param string $form Form HTML.
	 * @return string Modified form HTML.
	 */
	public function advanced_search_form_markup( $form ) {

		ob_start();
		include_once( 'views/html-nds-advanced-search-form.php' );
		$form = ob_get_contents();
		ob_end_clean();

		return $form;
	}

	/**
	 * Register shortcodes.
	 *
	 * @since    1.0.0
	 */
	public function register_shortcodes() {

		add_shortcode( 'nds-advanced-search', array( $this, 'shortcode_nds_advanced_search' ) );

	}

	/**
	 * Shortcode to add the advanced search form.
	 *
	 * Loads the custom search form added via the get_search_form filter hook.
	 * The custom search form is retrieved from the "advanced_search_form_markup" method.
	 * Returns the custom search form, and if the form was submitted the Search Results as well.
	 *
	 * @since    1.0.0
	 *
	 * @param  mixed  $atts an associative array of attributes, or an empty string if no attributes are given.
	 * @param  string $content the enclosed content.
	 */
	public function shortcode_nds_advanced_search( $atts, $content = null ) {
		/*
		 * Hook in a custom search form to override searchform.php in the theme or the
		 * default search form using the "get_search_form" filter hook.
		 *
		 * https://developer.wordpress.org/reference/functions/get_search_form/
		 *
		 * Note: I am adding and removing the "get_search_form" filter as I want my
		 * advanced form to load only when I invoke it using the plugin shortcode.
		 * This will ensure that any form defined in the theme's searchform.php is not
		 * overwritten.
		 *
		 * To completely override searchform.php detele the add_filter and remove_filter
		 * lines below and uncomment line 172 in the method "define_common_hooks" of
		 * inc/core/class-init.php.
		 */
		add_filter( 'get_search_form', array( $this, 'advanced_search_form_markup' ) );

		// https://core.trac.wordpress.org/browser/trunk/src/wp-includes/general-template.php#L182.
		$echo = false; // return and not echo the form.
		$form_content = get_search_form( $echo );

		remove_filter( 'get_search_form', array( $this, 'advanced_search_form_markup' ) );

		// form input is stored inside an associative array with the plugin's name.
		$get_form_input = filter_input( INPUT_POST, $this->plugin_name , FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		$search_term = isset( $get_form_input['search_key'] ) ? sanitize_text_field( $get_form_input['search_key'] ) : false;

		// check if the form was submitted.
		if ( isset( $search_term ) && ! empty( $search_term ) ) {

			// append the search results to $form_content.
			ob_start();
			include_once( 'views/html-nds-advanced-search-results.php' );
			$form_content .= ob_get_contents();
			ob_end_clean();
		}

		// return the content of the shortcode.
		return $form_content;

	}
}
