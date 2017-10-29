<?php 
/*
Plugin Name: Podcast Statistics
Plugin URI: https://thelovebug.org/
Description: A WordPress plugin to handle media redirect, with the primary purpose of capturing download statistics
Version: 1.1
Author: Dave Lee
Author URI: https://thelovebug.org/
License: GPL2
*/ 
function media_redirect() {
	list( $tbc_arg0, $tbc_arg1, $tbc_arg2, $tbc_arg3 ) = explode ( '/', $_SERVER["REQUEST_URI"] . '///' );
	$tbc_include = dirname(__FILE__) . '/functions.php';

	switch( $tbc_arg1 ) {
		case 'media':
			include( $tbc_include );
			$new_mediafile = get_redirect_mediafile( $tbc_arg2 );
			if( $new_mediafile != '') {
				wp_redirect( $new_mediafile );
				exit;
			}
			break;
	}
}
add_action ( 'plugins_loaded', 'media_redirect' );
?>
