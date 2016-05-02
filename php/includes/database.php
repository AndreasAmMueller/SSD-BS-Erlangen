<?php

/**
 * database.php
 */

/**
 * This class handles all database requests.
 *
 * @author   Andreas Mueller <webmaster@am-wd.de>
 * @version  v1.0-20160501
 */

class Database {
	
	/**
	 * The connection to the database.
	 * @var PDO
	 */
	private $conn;
	
	/**
	 * Initializes a new instance of the Database class
	 * and creates the connection to the database.
	 */
	function __construct()
	{
		global $config;

		$this->conn = new PDO('mysql:dbname='.$config['db_name'].';host='.$config['db_host'].';port='.$config['db_port'].';charset=utf8', $config['db_user'], $config['db_pass']);
		$this->conn->setAttribute(PDO::ATTR_PERSISTENT, true);
		$this->conn->query("SET lc_time_names = 'de_DE';");
	}
	
	/**
	 * Gets all information to a user needed to login from its e-mail address.
	 * 
	 * @param   string  $email  The e-mail address of the user.
	 * @return  mixed           An object containting the users data or null.
	 */
	public function getUserLogin($email)
	{
		$sql = "SELECT
	  usr_id          AS id
	, usr_name        AS name
	, usr_permissions AS permissions
	, usr_password    AS password
FROM
	users
WHERE
	usr_email = :email
;";

		try
		{
			$stmt = $this->conn->prepare($sql);
			$stmt->bindValue(':email', $email);
			$stmt->execute();
			
			$res = $stmt->fetchAll(PDO::FETCH_OBJ);
			if (count($res) == 1)
			{
				$user = $res[0];
				$user->permissions = explode(',', $user->permissions);
				return $user;
			}
			
			return null;
		}
		catch (PDOException $e)
		{
			return null;
		}
	}
	
	/**
	 * Gets all information to a user by its id.
	 * 
	 * @param   integer  $id  The ID of the user in the database.
	 * @return  mixed         An object containting the users data or null.
	 */
	public function getUser($id)
	{
		$sql = "SELECT
	  usr_id            AS id
	, usr_name          AS name
	, usr_email         AS email
	, usr_class         AS class
	, usr_room          AS room
	, usr_mobile        AS mobile
	, usr_qualification AS qualification
	, usr_permissions   AS permissions
FROM
	users
WHERE
	usr_id = :id
;";
		
		try
		{
			$stmt = $this->conn->prepare($sql);
			$stmt->bindValue(':id', intval($id));
			$stmt->execute();
			
			$res = $stmt->fetchAll(PDO::FETCH_OBJ);
			if (count($res) == 1)
			{
				$user = $res[0];
				$user->permissions = explode(',', $user->permissions);
				return $user;
			}
			
			return null;
		}
		catch (PDOException $e)
		{
			return null;
		}
	}
	
	/**
	 * Gets the list with all users in the database.
	 * 
	 * @param   string   [$order       = 'name']  The field to sort.
	 * @param   boolean  [$descending  = false]   A value indicating whether to sort ascending or descending.
	 * @return  mixed                             An array of objects containing the users data or an empty array.
	 */
	public function getUserList($order = 'name', $descending = false)
	{
		$sql = "SELECT
	  usr_id            AS id
	, usr_name          AS name
	, usr_email         AS email
	, usr_class         AS class
	, usr_room          AS room
	, usr_mobile        AS mobile
	, usr_qualification AS qualification
	, usr_permissions   AS permissions
FROM
	users
ORDER BY
	";

		switch ($order)
		{
			case 'name':
				$sql .= "usr_name";
				break;
			case 'email':
				$sql .= "usr_email";
				break;
			case 'class':
				$sql .= "usr_class";
				break;
			case 'room':
				$sql .= "usr_room";
				break;
			default:
				$sql .= "usr_id";
				break;
		}

		if ($descending)
			$sql .= " DESC";

		$sql .= ";";
		
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		
		$res = array();
		
		foreach ($stmt->fetchAll(PDO::FETCH_OBJ) as $el)
		{
			$el->permissions = explode(',', $el->permissions);
			$res[] = $el;
		}
		
		return $res;
	}
	
	/**
	 * Updates the users data in the database.
	 * 
	 * @param   object   $user An object containing the latest data of the user.
	 * @return  boolean  True on success otherwise false.
	 */
	public function updateUser($user)
	{
		$sql = "UPDATE users SET
	  usr_email         = :email
	, usr_name          = :name
	, usr_mobile        = :mobile
	, usr_class         = :class
	, usr_room          = :room
	, usr_qualification = :quali";
		
		if (!empty($user->password))
		{
			$sql.= "
	, usr_password = :passwd";
		}
		
		if (isset($user->permissions))
		{
			$sql.= "
	, usr_permissions = :perms";
		}
		
		$sql.= "
WHERE
	usr_id = :id
;";

		$stmt = $this->conn->prepare($sql);
		$stmt->bindValue(':email', $user->email);
		$stmt->bindValue(':name', $user->name);
		$stmt->bindValue(':mobile', $user->mobile);
		$stmt->bindValue(':class', $user->class);
		$stmt->bindValue(':room', $user->room);
		$stmt->bindValue(':quali', $user->qualification);
		$stmt->bindValue(':id', $user->id);
		
		if (!empty($user->password))
			$stmt->bindValue(':passwd', crypt($user->password, '$2a$07$'.md5(time()).'$'));
		
		if (isset($user->permissions))
			$stmt->bindValue(':perms', implode(',', $user->permissions));
		
		return $stmt->execute();
	}
	
	public function addUser($user)
	{
		$sql = "INSERT INTO users (
	  usr_email
	, usr_name
	, usr_password
	, usr_mobile
	, usr_class
	, usr_room
	, usr_qualification
	, usr_permissions
) VALUES (
	  :email
	, :name
	, :password
	, :mobile
	, :class
	, :room
	, :quali
	, :perms
);";

		$stmt = $this->conn->prepare($sql);
		$stmt->bindValue(':name', $user->name);
		$stmt->bindValue(':email', $user->email);
		$stmt->bindValue(':password', crypt($user->password, '$2a$07$'.md5(time()).'$'));
		$stmt->bindValue(':class', $user->class);
		$stmt->bindValue(':room', $user->room);
		$stmt->bindValue(':mobile', $user->mobile);
		$stmt->bindValue(':quali', $user->qualification);
		$stmt->bindValue(':perms', implode(',', $user->permissions));
		
		if (!$stmt->execute())
			return 0;
		
		return $this->conn->lastInsertId();
	}
	
	public function deleteUser($id)
	{
		$sql = "DELETE FROM users WHERE usr_id = :id;";
		
		$stmt = $this->conn->prepare($sql);
		$stmt->bindValue(':id', $id);
		
		return $stmt->execute();
	}
	
	public function getSettings()
	{
		$sql = "SELECT
	  set_start    AS start
	, set_end      AS end
FROM
	settings
WHERE
	set_id = 1
;";

		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		
		$res = $stmt->fetch(PDO::FETCH_OBJ);
		
		return $res;
	}
	
	public function setSettings($settings)
	{
		$sql = "UPDATE settings SET
	  set_start   = :start
	, set_end     = :end
WHERE
	set_id = 1
;";

		if ($settings->end <= $settings->start)
			return false;

		$stmt = $this->conn->prepare($sql);
		$stmt->bindValue(':start', date('Y-m-d', $settings->start));
		$stmt->bindValue(':end', date('Y-m-d', $settings->end));
		
		return $stmt->execute();
	}
	
	public function getHolidays()
	{
		$sql = "SELECT
	  hol_id    AS id
	, hol_start AS start
	, hol_end   AS end
	, hol_weeks AS weeks
FROM
	  holidays
	, settings
WHERE
	set_start <= hol_end
	AND
	hol_start <= set_end
ORDER BY
	hol_start
;";

		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		
		$res = $stmt->fetchAll(PDO::FETCH_OBJ);
		foreach ($res as $r)
		{
			$r->weeks = explode(',', $r->weeks);
		}
		
		return $res;
	}
	
	public function setHolidays($range)
	{
		if ($range->id == 0)
			$sql = "INSERT INTO holidays (hol_start,hol_end,hol_weeks) VALUES (:start, :end, :weeks);";
		else
			$sql = "UPDATE holidays SET hol_start = :start, hol_end = :end, hol_weeks = :weeks WHERE hol_id = :id;";
		
		$weeks = array();
		for ($i = $range->start; $i <= $range->end; $i = strtotime('+1 day', $i))
		{
			if (!in_array(date('W', $i), $weeks))
				$weeks[] = date('W', $i);
		}
		
		$stmt = $this->conn->prepare($sql);
		$stmt->bindValue(':start', date('Y-m-d', $range->start));
		$stmt->bindValue(':end', date('Y-m-d', $range->end));
		$stmt->bindValue(':weeks', implode(',', $weeks));
		if ($range->id > 0)
			$stmt->bindValue(':id', intval($range->id));
		
		return $stmt->execute();
	}
	
	public function deleteHolidays($id)
	{
		if ($id < 1)
			return false;
			
		$sql = "DELETE FROM holidays WHERE hol_id = :id;";
		
		$stmt = $this->conn->prepare($sql);
		$stmt->bindValue(':id', intval($id));
		
		return $stmt->execute();
	}
}




?>