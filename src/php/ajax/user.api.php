<?php

/**
 * user.api.php
 * (c) Andreas Mueller <webmaster@am-wd.de>
 */

/**
 * Reads the user by his id from the database.
 * 
 * @param integer  $id  The user's ID in the database.
 * 
 * @return object|null  The user's information or null.
 */
function user_getUser($id)
{
	global $db;
	
	if (empty($_SESSION['id']) || !in_array('admin', $_SESSION['permissions']))
		return null;
	
	return $db->getUser($id);
}

?>