<?php
/**
 * The HTML markup for the Top Pages dashboard panel.
 *
 */
require_once( 'seomoz_functions.php' );

$domain = seomoz_get_wordpress_domain();
$top_pages = seomoz_get_top_pages( $domain );
$seomoz_options = get_option( 'seomoz_options' );
$num_pages = 25;
if ( isset( $seomoz_options['dashboard_top_pages']['pages'] ) ) {
 $num_pages = $seomoz_options['dashboard_top_pages']['pages'];
}


echo '<div>';
echo "<h4 style=\"float:left;\">Top pages on '$domain'</h4>";
echo '<a style="float:right;" href="http://www.seomoz.org/linkscape"><img src="'.seomoz_get_plugin_base_url().'images/powered_by_linkscape.jpg" alt="Powered by Linkscape"/></a>';
echo '</div>';

echo '<div style="clear:both;">';
if (false === $top_pages) {
	echo "Top Pages currently requires the Site Intelligence APIs.";
} else if ( count( $top_pages ) == 0 ) {
	echo "No pages found.";
} else {
	echo '<ol>';
	for ($i = 0; $i < count( $top_pages ) && $i < $num_pages; $i++) {
			$source_url = $top_pages[$i]->uu;
			$source_title = $top_pages[$i]->ut;
			echo "<li>$source_title: <a href=\"http://$source_url\">$source_url</a></li>";
	}
	echo '</ol>';
	echo "<p>Showing up to $num_pages pages. <a target=\"_blank\" href=\"http://www.opensiteexplorer.org/$domain/a!links\">Full report</a></p>";
}
echo '</div>';
?>