<?php

function get_redirect_mediafile ( $filename ) {

	global $wpdb;

	$sql = "SELECT 	DISTINCT
				post_id,
				(
				SELECT 		meta_value
				FROM		$wpdb->postmeta pm2
				WHERE 		pm2.meta_key = 'show_id'
					AND	pm2.post_id = pm1.post_id
				) show_id,
				(
				SELECT 		meta_value
				FROM		$wpdb->postmeta pm4
				WHERE 		pm4.meta_key = 'podcast_id'
					AND	pm4.post_id = pm1.post_id
				) podcast_id
		FROM 		$wpdb->postmeta pm1
		WHERE 		EXISTS	(
					SELECT 		1
					FROM 		$wpdb->postmeta pm3
					WHERE 		pm3.meta_key LIKE '%%enclosure'
						AND	pm3.meta_value LIKE '%%%s%%'
						AND	pm3.post_id = pm1.post_id
					) ";

	$row = $wpdb->get_row( $wpdb->prepare( $sql, trim($filename) ) );

	if( $row ) {
		$remote_addr     = $_SERVER["REMOTE_ADDR"];
		$request_uri     = $_SERVER["REQUEST_URI"];
		$http_user_agent = strtr( $_SERVER["HTTP_USER_AGENT"], "'", "''");
		$podcast_id      = $row->podcast_id;
		$show_id         = $row->show_id;
		$post_id         = $row->post_id;
		$http_referrer   = $_SERVER["HTTP_REFERER"];
		$wpdb->query( $wpdb->prepare( "
				INSERT INTO 	bugcast_log 
						(
						remote_addr, request_uri, http_user_agent, podcast_id, 
						show_id, post_id, http_referer
						) 
				VALUES 		('%s', '%s', '%s', %d, %d, %d, '%s')"
			, $remote_addr, $request_uri, $http_user_agent, $podcast_id, $show_id, $post_id, $http_request ) ); 		

		return 'https://media.blubrry.com/thebugcast/p/archive.org/download/thebugcast/' . $filename;
		exit;
	}
	else {
		return;
	}

}

?>
