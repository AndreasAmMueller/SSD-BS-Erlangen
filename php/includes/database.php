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
				return $res[0];
			
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
}




?>