<?php
/**
 * The HTML markup for the dashboard panel.
 *
 */
require_once( 'seomoz_functions.php' );

$domain = seomoz_get_wordpress_domain();
$inbound_links = seomoz_get_inbound_domain_links( $domain );
$seomoz_options = get_option( 'seomoz_options' );
$num_links = 25;
if ( isset( $seomoz_options['dashboard_inbound_links']['links'] ) ) {
 $num_links = $seomoz_options['dashboard_inbound_links']['links'];
}


echo '<div>';
echo "<h4 style=\"float:left;\">Inbound links to '$domain'</h4>";
echo '<a style="float:right;" href="http://www.seomoz.org/linkscape"><img src="'.seomoz_get_plugin_base_url().'images/powered_by_linkscape.jpg" alt="Powered by Linkscape"/></a>';
echo '</div>';

echo '<div style="clear:both;">';
if (false === $inbound_links) {
	echo "Error connecting to SEOmoz. Please check your API credentials.";
} else if ( count( $inbound_links ) == 0 ) {
	echo "No inbound links found.";
} else {
	echo '<ol>';
	for ($i = 0; $i < count( $inbound_links ) && $i < $num_links; $i++) {
			$source_url = $inbound_links[$i]->uu;
			echo "<li><a href=\"http://$source_url\">$source_url</a></li>";
	}
	echo '</ol>';
	echo "<p>Showing up to $num_links links, ordered by source Domain Authority. <a target=\"_blank\" href=\"http://www.opensiteexplorer.org/$domain/a!links\">Full report</a></p>";
}
echo '</div>';
?>