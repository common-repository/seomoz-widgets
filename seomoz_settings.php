<?php
/**
 * The HTML for the admin settings page.
 *
 */

// Load SEOmoz functions
require_once('seomoz_functions.php');

$seomoz_options = get_option('seomoz_options');
$error = '';
$message = '';

if( $_POST['seomoz_hidden'] == 'Y' ) {

	// API Settings
	$seomoz_options['access_id'] = trim($_POST['seomoz_access_id']);
	$seomoz_options['secret_key'] = trim($_POST['seomoz_secret_key']);

	update_option( 'seomoz_options' , $seomoz_options );

	$auth_sucess = seomoz_test_auth($seomoz_options['access_id'], $seomoz_options['secret_key']);

	if ($auth_sucess) {
		$message = __( 'SEOmoz API credentials verified and saved.' , 'seomoz_text_domain' );
	} else {
		$error = __( 'Invalid API credentials.' , 'seomoz_text_domain' );
	}
}
?>

<div class="wrap">
	<?php echo '<h2>' . __( 'SEOmoz Options', 'seomoz_text_domain' ) . '</h2>'; ?>

	<?php if (!empty($error)) : ?>
	<div class="error"><p><?php echo $error; ?></p></div>
	<?php endif; ?>

	<?php if (!method_exists('WpFileCache', 'instance')) : ?>
	<div class="updated"><p>
	 We recommend that you install the <a href="http://wordpress.org/extend/plugins/wp-file-cache/">WP-File-Cache</a> plugin to improve performance.
	</p></div>
	<?php endif; ?>

	<?php if (!empty($message)) : ?>
	<div class="updated fade"><p><?php echo $message; ?></p></div>
	<?php endif; ?>

	<?php echo '<h4>' . __( 'SEOmoz Overview', 'seomoz_text_domain' ) . '</h4>'; ?>
	<p>
		Provides dashboard elements and themes widgets to display SEO related info, like inbound links, retrieved using the SEOmoz Linkscape API.
	</p>
	<p>
		If you have an SEOmoz.org account, you can log in and find your credentials on the `http://www.seomoz.org/api` page.	If you don't
		have a free SEOmoz.org account, sign up, and visit the API page to retrieve your API credentials.
	</p>

	<form name="seomoz_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="seomoz_hidden" value="Y">

		<?php echo '<h4>' . __( 'API Settings' , 'seomoz_text_domain' ) . '</h4>'; ?>
		<p><?php _e("Access ID: " ); ?><input type="text" name="seomoz_access_id" value="<?php echo $seomoz_options['access_id']; ?>" size="45"><?php echo __(" ex: member-122e9ce221", 'seomoz_text_domain'); ?></p>
			<p><?php _e("Secret Key: " ); ?><input type="text" name="seomoz_secret_key" value="<?php echo $seomoz_options ['secret_key']; ?>" size="45"><?php echo __(" ex: 209cf2a38842a139cc8979af4a6947be", 'seomoz_text_domain'); ?></p>

		<p class="submit">
		<input type="submit" name="Submit" value="<?php _e( 'Update Options', 'seomoz_text_domain' ) ?>" />
		</p>
	</form>
</div>

