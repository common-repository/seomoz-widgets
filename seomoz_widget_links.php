<?php
/**
 * SEOmozLinksWidget Class
 */
class SEOmozLinksWidget extends WP_Widget {
		/** constructor */
		function SEOmozLinksWidget() {
				parent::WP_Widget(false, $name = 'SEOmoz Links', array('description' => 'Show inbound links to current page or domain.'));
		}

		/** @see WP_Widget::widget */
		function widget( $args, $instance ) {
				extract( $args );
				$title = apply_filters( 'widget_title' , $instance['title'] );
				$num_links = intval($instance['num_links']);
				?>
					<?php echo $before_widget; ?>
					<?php if ( $title ) echo $before_title . $title . $after_title; ?>

							<?php
							include( 'seomoz_functions.php' );

							$inbound_links = seomoz_get_inbound_domain_links( seomoz_get_wordpress_domain() );

							echo '<div style="clear:both;">';
							echo '<ol>';
							for ($i = 0; $i < count( $inbound_links ) && $i < intval( $num_links ) ; $i++) {
									$source_url = $inbound_links[$i]->uu;
									echo "<li><a href=\"http://$source_url\">$source_url</a></li>";
							}
							echo '</ol>';

							if ( count( $inbound_links	) == 0 ) {
								echo 'No links to this domain.';
							}
							echo '<p style="padding-top: 10px;">';
							echo '<a href="http://www.seomoz.org/linkscape"><img src="'.seomoz_get_plugin_base_url().'images/powered_by_linkscape.jpg" alt="Powered by Linkscape"/></a>';
							echo '</p>';
							echo '</div>';
							?>

					<?php echo $after_widget; ?>
				<?php
		}

		/** @see WP_Widget::update */
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['num_links'] = intval(strip_tags($new_instance['num_links']));
			return $instance;
		}

		/** @see WP_Widget::form */
		function form( $instance ) {
				$title = esc_attr( $instance['title'] );
				$num_links = esc_attr( $instance['num_links'] );
				if ($num_links == "") {
					$num_links = 10;
				}
				?>
				<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
				<p><label for="<?php echo $this->get_field_id('num_links'); ?>"><?php _e( 'Number of Links:' ); ?> <input class="widefat" id="<?php echo $this->get_field_id('num_links'); ?>" name="<?php echo $this->get_field_name('num_links'); ?>" type="text" value="<?php echo $num_links; ?>" /></label></p>
				<?php
		}
} // class SEOmozLinksWidget

// register SEOmozLinksWidget widget
add_action('widgets_init', create_function('', 'return register_widget("SEOmozLinksWidget");'));

?>