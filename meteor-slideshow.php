<?php global $post;
	
	$options = get_option( 'meteorslides_options' );
	
	$loop = new WP_Query( array( 'post_type' => 'slide', 'posts_per_page' => $options['slideshow_quantity'] ) ); ?>
	
	<div id="meteor-slideshow">
	
		<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>

			<div class="slide">
				
				<?php if(get_post_meta($post->ID, "slide_url_value", $single = true) != ""): ?>
						
					<a href="<?php echo get_post_meta($post->ID, "slide_url_value", $single = true); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('featured-slide'); ?></a>
			
				<?php else: ?>
					
					<?php the_post_thumbnail('featured-slide'); ?>
					
				<?php endif; ?>
			
			</div><!-- .slide -->
			
		<?php endwhile; ?>
		
		<?php wp_reset_query(); ?>
			
	</div><!-- #meteor-slideshow -->