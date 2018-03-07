<?php 

$message= array();

if (isset($_POST['publish_changeset'])) {

	$post_id = $_POST['changeset_id'];

	if (isset($post_id) && is_numeric($post_id)) {
		global $wpdb;

		$wpdb->update( $wpdb->posts, array( 'post_status' => 'publish' ), array( 'ID' => $post_id ) );
		clean_post_cache( $post_id );

		$message['text']= "Changeset <strong>saved</strong>.";
		$message['type']= "updated";
	}
}

if (isset($_POST['customize_changeset'])) {

	$post_id = $_POST['changeset_id'];

	if (isset($post_id) && is_numeric($post_id)) {
		global $wpdb;

		$wpdb->update( $wpdb->posts, array( 'post_status' => 'draft' ), array( 'ID' => $post_id ) );
		clean_post_cache( $post_id );

		wp_redirect(esc_url(add_query_arg( 'changeset_uuid', $_POST['changeset_name'], admin_url( 'customize.php' ) )));
	}
}

if (isset($_POST['edit_changeset'])) {

	$post_id = $_POST['changeset_id'];

	if (isset($post_id) && is_numeric($post_id)) {

		if (isset($_POST['changeset_excerpt'])) {
			$args = array('ID' => $_POST['changeset_id'], 'post_excerpt' => sanitize_text_field( $_POST['changeset_excerpt'] ));
			wp_update_post($args);

			$message['text']= "Changeset <strong>edited</strong>.";
			$message['type']= "updated";
		}
	}
}

if (isset($_POST['trash_changeset'])) {

	$post_id = $_POST['changeset_id'];

	if (isset($post_id) && is_numeric($post_id)) {
		wp_delete_post($post_id, true);

		$message['text']= "Changeset <strong>deleted</strong>.";
		$message['type']= "error";
	}
}

$args = array(
		'orderby'     => 'modified',
     	'order'       => 'DESC',
		'posts_per_page'   => -1, 
		'post_type' => 'customize_changeset', 
		'post_status' => array('draft', 'trash', 'publish', 'getbowtied')
	);

$changesets = get_posts($args);
?>

<div class="wrap getbowtied-presets">

<?php if (!empty($message)) { ?>
	<div id="message" class="<?php echo $message['type']; ?> notice is-dismissible">
		<p><?php echo $message['text']; ?></p>
		<button type="button" class="notice-dismiss">
			<span class="screen-reader-text">Dismiss this notice.</span>
		</button>
	</div>
<?php } ?>

<table class="wp-list-table widefat fixed striped">
	<thead>
		<td width="10%">Last Modified</td>
		<td width="10%">ID</td>
		<td width="50%">Description</td>
		<td width="10%">Status</td>
		<td>Actions</td>
		<!-- <td>URL Fragment</td> -->
	</thead>

<?php 
foreach ( $changesets as $changeset ) {

	$changes = json_decode($changeset->post_content, true);


	echo '<tr>';

	echo '<td>' . $changeset->post_modified. '</td>';
	// echo '<td><a href="'.admin_url( 'admin.php?page=getbowtied-presets&changeset_id='.$changeset->ID ) .'">' . $changeset->ID. '</a></td>';
	echo '<td>' . $changeset->ID . '</td>';
	
	// echo '<td>' . $changeset->post_excerpt . '</td>';
	// echo '<td>'; 
	// foreach ($changes as $k=>$v) {
	// 	if (is_string($v['value'])):
	// 		echo $k . ':' . $v['value'] .' <br/> ';
	// 	endif;
	// }
	// echo '</td>';
	// 
	?>
	<!-- Edit changeset name -->
	<td class="edit">
		<span class="excerpt"><?php echo empty($changeset->post_excerpt)? 'Generic changeset' : $changeset->post_excerpt; ?></span>
		<a href="#" class="edit">Edit</a>
		<a href="#" class="raw">Raw Data</a>
		<form method="POST" action="" class="edit">
			<input type="hidden" name="changeset_id" value="<?php echo $changeset->ID; ?>" />
			<input type="hidden" name="edit_changeset" value="1" />
			<input type="text" name="changeset_excerpt" value="<?php echo empty($changeset->post_excerpt)? 'Generic changeset' : $changeset->post_excerpt; ?>" />
			<input class="button" type="submit" value="Edit" />
		</form>
		<div class="raw-data">
			<pre><?php
			foreach ($changes as $k=>$v) {
				if (is_string($v['value'])):
					echo '<span>'.$k. '=> ' . $v['value'].'</span>';
				endif;
			}
			?></pre>
		</div>
	</td>
	<?php
	$status_message = 'unknown';
	switch ($changeset->post_status) {
		case 'publish':
			$status_message = 'Saved';
		break;

		case 'trash':
			$status_message = 'Expiring';
		break;

		case 'draft':
			$status_message = 'Draft';
		break;

		default:
		break;
	}

	echo '<td class="status"><span class="'. $changeset->post_status .'">' . $status_message  . '</span></td>';

	$status = $changeset->post_status == 'publish' ? '<span class="dashicons dashicons-star-filled">' : '<span class="dashicons dashicons-star-empty">';
	$disabled = $changeset->post_status == 'publish' ? 'disabled' : '';

	echo '<td class="actions">';
		echo '<form method="POST" action="">';
				echo '<input type="hidden" name="publish_changeset" value="1" />';
				echo '<input type="hidden" name="changeset_id" value="' . $changeset->ID .'" />';
				echo '<button ' . $disabled . ' class="button" title="Keep this" type="submit" value="submit"> ' . $status . '</button>';
		echo '</form>';


	echo '<a class="button" target="_blank" title="View" href="'. esc_url(add_query_arg(
		'customize_changeset_uuid',
		$changeset->post_name,
		site_url()
	)) . '"><span class="dashicons dashicons-visibility"></span></a>';

	// echo '<td><a target="_blank" href="'. esc_url(add_query_arg(
	// 	'changeset_uuid',
	// 	$changeset->post_name,
	// 	admin_url( 'customize.php' )
	// )) . '">Edit</a><td>';
	// 
		echo '<form method="POST" action="">';
				echo '<input type="hidden" name="customize_changeset" value="1" />';
				echo '<input type="hidden" name="changeset_id" value="' . $changeset->ID .'" />';
				echo '<input type="hidden" name="changeset_name" value="' . $changeset->post_name .'" />';
				echo '<button class="button" type="submit" value="submit" title="Customize"><span class="dashicons dashicons-edit"></span></button>';
		echo '</form>';

		echo '<form method="POST" action="" onsubmit="return confirm(\'Permanently delete changeset?\');">';
				echo '<input type="hidden" name="trash_changeset" value="1" />';
				echo '<input type="hidden" name="changeset_id" value="' . $changeset->ID .'" />';
				echo '<button class="button" type="submit" value="submit" title="Trash"><span class="dashicons dashicons-trash"></span></button>';
		echo '</form>';
	echo '</td>';

	// echo '<td><input type="text" value="?customize_changeset_uuid=' . $changeset->post_name . '" /></td>';

	echo '</tr>';

}
?>

</table>
</div>