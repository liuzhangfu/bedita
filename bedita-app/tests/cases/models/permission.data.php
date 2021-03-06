<?php 
/**
 * 
 *
 * @version			$Revision$
 * @modifiedby 		$LastChangedBy$
 * @lastmodified	$LastChangedDate$
 * 
 * $Id$
 */
 
class PermissionTestData extends BeditaTestData {

	var $data =  array(
		'min'	=> array('title' => 'Test title'),
		'addPerms1' => array(
				// write permission
				array('switch' => 'user', 'flag' => 1, 'name' => 'bedita'),
				// frontend_access_with_block
				array('switch' => 'group', 'flag' => 2, 'name' => 'reader'),
		),
		'removePerms1' => array(
				// frontend_access_with_block
				array('switch' => 'group', 'flag' => 2, 'name' => 'reader'),
		),
		'resultDeletePerms1' => array(
				// write permission
				array('switch' => 'user', 'flag' => 1, 'name' => 'bedita'),
		),
		'permsFrontendCheck' => array(
				// frontend_access_with_block
				array('switch' => 'group', 'flag' => 2, 'name' => 'reader'),
				// frontend_access_without_block
				array('switch' => 'group', 'flag' => 3, 'name' => 'editor')
		),
		'permsFrontendCheck2' => array(
				// frontend_access_without_block
				array('switch' => 'group', 'flag' => 3, 'name' => 'editor')
		),
		'permsFrontendCheck3' => array(
				// frontend_access_with_block
				array('switch' => 'group', 'flag' => 2, 'name' => 'editor')
		),
		'permsForbiddenCheck' => array(
				// backend_private
				array('switch' => 'group', 'flag' => 4, 'name' => 'editor')
		),
	);

}

?>