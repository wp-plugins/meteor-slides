<?php global $post;
	
	$options = get_option( 'meteorslides_options' );
	
	$meteornav = $options['slideshow_navigation'];

	$i = 1;
	
	$loop = new WP_Query( array( 'post_type' => 'slide', 'slideshow' => $slideshow, 'posts_per_page' => $options['slideshow_quantity'] ) ); ?>
	
	<div id="meteor-slideshow" class="meteor-slides <?php echo $meteornav; ?>">
		
		<?php if($meteornav == "navboth"): ?>
	
			<ul class="meteor-nav">
		
				<li class="prev"><a href="#"><?php _e('Previous','meteor-slides') ?></a></li>
			
				<li class="next"><a href="#"><?php _e('Next','meteor-slides') ?></a></li>
			
			</ul><!-- .meteor-nav -->
		
			<div class="meteor-buttons"></div>
		
		<?php elseif($meteornav == "navprevnext"): ?>
	
			<ul class="meteor-nav">
		
				<li class="prev"><a href="#"><?php _e('Previous','meteor-slides') ?></a></li>
			
				<li class="next"><a href="#"><?php _e('Next','meteor-slides') ?></a></li>
			
			</ul><!-- .meteor-nav -->
		
		<?php elseif($meteornav == "navpaged"): ?>
	
			<div class="meteor-buttons"></div>
		
		<?php endif; ?>
		
		<div class="slides<?php if (isset($metadata)) { echo ' {' . $metadata . '}'; } ?>">
	
			<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>

				<div id="slide-<?php echo $i; ?>" class="slide">
				
					<?php if(get_post_meta($post->ID, "slide_url_value", $single = true) != ""): ?>
						
						<a href="<?php echo get_post_meta($post->ID, "slide_url_value", $single = true); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('featured-slide'); ?></a>
			
					<?php else: ?>
					
						<?php the_post_thumbnail('featured-slide'); ?>
					
					<?php endif; ?>
			
				</div><!-- .slide -->
			
				<?php $i++; ?>
			
			<?php endwhile; ?>
		
		</div><!-- .slides -->
		
		<?php wp_reset_query(); ?>
			
	</div><!-- #meteor-slideshow -->