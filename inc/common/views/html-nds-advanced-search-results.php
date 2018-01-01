<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$transient_name = $this->plugin_transients['autosuggest_transient'];

// retrieve the post types to search from the plugin settings.
$plugin_options = get_option( $this->plugin_name );
$post_types = array_keys( $plugin_options, 1, true );

// check if cached posts are available.
$cached_posts = get_transient( $transient_name );
if ( false === $cached_posts ) {

	// retrieve posts for the specified post types by running get_posts and cache the posts as well.
	$cached_posts = $this->cache_posts_in_post_types();
}

// extract the cached post ids from the transient into an array.
$cached_post_ids = array_column( $cached_posts, 'id' );

// run a new query to against the search key and the cached post ids for the seleted post types.
$args = array(
	'post_type'           => $post_types,
	'posts_per_page'      => -1,
	'no_found_rows'       => true, // as we don't need pagination.
	'post__in'            => $cached_post_ids, // use post ids that were cached in the query earlier.
	'ignore_sticky_posts' => true,
	's'                   => $search_term,  // the keyword/phrase to search.
	'sentence'            => true, // perform a phrase search.
);
$search_query = new \WP_Query( $args );
?>

<!-- Search Results -->
<div class="nds-search-results">
	<?php if ( $search_query->have_posts() ) : ?>
		<ul class="flex-grid-container">
			<!-- Start the Loop. -->
			<?php
			while ( $search_query->have_posts() ) :
					$search_query->the_post();
			?>

					<li class="flex-grid-item">

						<!-- the thumbnail -->
						<p>
							<?php if ( has_post_thumbnail() ) : ?>
								<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium' ); ?></a>
							<?php endif; ?>
						</p>
						<!-- title -->
						<p class="card-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</p>
						<!-- excerpt -->
						<p class="card-excerpt">
							<?php echo wp_trim_words( get_the_content(), 30, ' ...' ); ?>
						</p>

					</li> <!-- flex-grid-item -->
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
		</ul> <!-- flex-grid-container -->
		<?php else : ?>
			<p>
				<?php echo __( 'Nothing Found ...', $this->plugin_text_domain ); ?>
			</p>
		<?php endif; ?>
</div> <!-- nds-search-results -->


