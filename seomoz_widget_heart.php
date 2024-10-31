<?php
/**
 * IHeartSeomozWidget Class
 * Create a widget that displays a SEOmoz badge.
 */
class IHeartSeomozWidget extends WP_Widget {
		/** constructor */
		function IHeartSeomozWidget() {
				parent::WP_Widget(false, 'I Heart Seomoz', array('description' => 'Declare your love for SEOmoz, robot style.'));
		}

		/** @see WP_Widget::widget */
		function widget($args, $instance) {
      extract( $args );
      echo $before_widget;
			echo '<a href="http://www.seomoz.org"><img src="http://www.seomoz.org/img/i-heart-seomoz.png" alt="I &lt;3 SEO moz" /></a>';
      echo $after_widget;
		}

} // class IHeartSeomozWidget

// register IHeartSeomozWidget widget
add_action('widgets_init', create_function('', 'return register_widget("IHeartSeomozWidget");'));
?>