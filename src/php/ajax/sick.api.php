<?php

/**
 * attendence.api.php
 * (c) Andreas Mueller <webmaster@am-wd.de>
 */

function sick_update($data)
{
	global $db;
	
	if (empty($_SESSION['id']))
		return false;
	
	return $db->updateAttendence($_SESSION['id'], $data->week, $data->day, $data->value);
}

?>