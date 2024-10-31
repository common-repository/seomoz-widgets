<?php
/**
 * The uninstall hook to remove the options from the database.
 *
 */
if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') )
    exit();

delete_option( 'seomoz_options' );
?>