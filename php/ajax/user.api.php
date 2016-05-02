<?php

function user_getUser($id)
{
	global $db;
	
	if (empty($_SESSION['id']))
		return null;
	
	return $db->getUser($id);
}


?>