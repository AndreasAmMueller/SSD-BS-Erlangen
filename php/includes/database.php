<?php

/**
 * database.php
 */

/**
 * This class handles all database requests.
 *
 * @author   Andreas Mueller <webmaster@am-wd.de>
 * @version  v1.0-20160504
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

		$this->conn = new PDO('sqlite:'.DIR.'/'.$config['db_path']);
		$this->conn->setAttribute(PDO::ATTR_PERSISTENT, true);
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
	  usr_id                            AS id
	, usr_firstname || ' ' || usr_name  AS name
	, usr_permissions                   AS permissions
	, usr_password                      AS password
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
	
	public function lastLogin($id)
	{
		$sql = "UPDATE users SET usr_login = datetime('now', 'localtime') WHERE usr_id = :id;";
		
		try
		{
			$stmt = $this->conn->prepare($sql);
			$stmt->bindValue(':id', $id);
			
			return $stmt->execute();
		}
		catch (PDOException $e)
		{
			return false;
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
	  usr_id                            AS id
	, usr_name                          AS name
	, usr_firstname                     AS firstname
	, usr_firstname || ' ' || usr_name  AS fullname
	, usr_email                         AS email
	, usr_class                         AS class
	, usr_room                          AS room
	, usr_mobile                        AS mobile
	, usr_qualification                 AS qualification
	, usr_permissions                   AS permissions
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
	  usr_id                            AS id
	, usr_name                          AS name
	, usr_firstname                     AS firstname
	, usr_firstname || ' ' || usr_name  AS fullname
	, usr_email                         AS email
	, usr_class                         AS class
	, usr_room                          AS room
	, usr_mobile                        AS mobile
	, usr_qualification                 AS qualification
	, usr_permissions                   AS permissions
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

		try
		{
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
		catch (PDOException $e)
		{
			return array();
		}
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
	, usr_firstname     = :firstname
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

		try
		{
			$stmt = $this->conn->prepare($sql);
			$stmt->bindValue(':email', $user->email);
			$stmt->bindValue(':name', $user->name);
			$stmt->bindValue(':firstname', $user->firstname);
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
		catch (PDOException $e)
		{
			return false;
		}
	}

	/**
	 * Inserts a new user in the database.
	 *
	 * @param   object   $user  The user's information.
	 * @return  integer         Zero on failure, otherwise the new id of the user.
	 */
	public function addUser($user)
	{
		$sql = "INSERT INTO users (
	  usr_email
	, usr_name
	, usr_firstname
	, usr_password
	, usr_mobile
	, usr_class
	, usr_room
	, usr_qualification
	, usr_permissions
) VALUES (
	  :email
	, :name
	, :firstname
	, :password
	, :mobile
	, :class
	, :room
	, :quali
	, :perms
);";

		try
		{
			$stmt = $this->conn->prepare($sql);
			$stmt->bindValue(':name', $user->name);
			$stmt->bindValue(':firstname', $user->firstname);
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
		catch (PDOException $e)
		{
			return 0;
		}
	}

	/**
	 * Deletes a user by his id.
	 *
	 * @param   integer  $id  The user's unique ID in the database.
	 * @return  boolean       True on success, otherwise false.
	 */
	public function deleteUser($id)
	{
		$sql = "DELETE FROM users WHERE usr_id = :id;";

		try
		{
			$stmt = $this->conn->prepare($sql);
			$stmt->bindValue(':id', $id);
	
			return $stmt->execute();
		}
		catch (PDOException $e)
		{
			return false;
		}
	}

	/**
	 * Gets the beginning and the ending of a school year.
	 *
	 * @return  object  The dates.
	 */
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

		try
		{
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();

			return $stmt->fetch(PDO::FETCH_OBJ);
		}
		catch (PDOException $e)
		{
			return array();
		}
	}

	/**
	 * Sets the beginning and the ending of a school year.
	 *
	 * @param   object   $settings  The dates.
	 * @return  boolean             True on success, otherwise false.
	 */
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

		try
		{
			$stmt = $this->conn->prepare($sql);
			$stmt->bindValue(':start', date('Y-m-d', $settings->start));
			$stmt->bindValue(':end', date('Y-m-d', $settings->end));
	
			return $stmt->execute();
		}
		catch (PDOException $e)
		{
			return false;
		}
	}

	/**
	 * Gets the list with all inserted holidays for the current school year.
	 *
	 * @return  object[]  An array with all date ranges of holidays.
	 */
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
	set_id = 1
	AND
	DATE(set_start) <= DATE(hol_end)
	AND
	DATE(hol_start) <= DATE(set_end)
ORDER BY
	DATE(hol_start)
;";

		try
		{
			$stmt = $this->conn->prepare($sql);
			$stmt->execute();

			$res = array();
			foreach ($stmt->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$r->weeks = explode(',', $r->weeks);
				$res[] = $r;
			}

			return $res;
		}
		catch (PDOException $e)
		{
			return array();
		}
	}

	/**
	 * Inserts or updates a holiday by its date range.
	 *
	 * @param   object   $range  The date range of the holiday.
	 * @return  boolean          True on success, otherwise false.
	 */
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

		try
		{
			$stmt = $this->conn->prepare($sql);
			$stmt->bindValue(':start', date('Y-m-d', $range->start));
			$stmt->bindValue(':end', date('Y-m-d', $range->end));
			$stmt->bindValue(':weeks', implode(',', $weeks));
			if ($range->id > 0)
				$stmt->bindValue(':id', intval($range->id));
	
			return $stmt->execute();
		}
		catch (PDOException $e)
		{
			return false;
		}
	}

	/**
	 * Deletes a holiday by it's id in the database.
	 *
	 * @param   integer  $id  The unique id from the database.
	 * @return  boolean       True on success, otherwise false.
	 */
	public function deleteHolidays($id)
	{
		if ($id < 1)
			return false;

		$sql = "DELETE FROM holidays WHERE hol_id = :id;";

		try
		{
			$stmt = $this->conn->prepare($sql);
			$stmt->bindValue(':id', intval($id));

			return $stmt->execute();
		}
		catch (PDOException $e)
		{
			return false;
		}
	}

	/**
	 * Checks whether a unix timestamp is a holiday.
	 *
	 * @param   integer  $unix_time  Timestamp.
	 * @return  boolean              True if it is a holiay, otherwise false.
	 */
	public function isHoliday($unix_time)
	{
		if (intval($unix_time) <= 0)
			return false;

		$sql = "SELECT
	COUNT(*) AS count
FROM
	holidays
WHERE
	DATE(hol_start) <= DATE(:date)
	AND
	DATE(:date) <= DATE(hol_end)
;";

		try
		{
			$stmt = $this->conn->prepare($sql);
			$stmt->bindValue(':date', date('Y-m-d', $unix_time));

			$stmt->execute();

			$res = $stmt->fetch(PDO::FETCH_OBJ);
			return $res->count > 0;
		}
		catch (PDOException $e)
		{
			return false;
		}
	}

	/**
	 * Gets all attendences by a user id.
	 *
	 * @param   integer   $id  The user's unique id.
	 * @return  object[]       An array with attendences of a user.
	 */
	public function getAttendence($id)
	{
		$sql = "SELECT
	  att_week AS week
	, att_mon  AS mon
	, att_tue  AS tue
	, att_wed  AS wed
	, att_thu  AS thu
	, att_fri  AS fri
FROM
	attendences
JOIN
	settings ON set_id = 1
WHERE
	att_user = :id
	AND
	att_year = CAST(STRFTIME('%Y', set_start) AS INT)
ORDER BY
	att_year, att_week
;";

		try
		{
			$stmt = $this->conn->prepare($sql);
			$stmt->bindValue(':id', $id);

			$stmt->execute();

			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}
		catch (PDOException $e)
		{
			return array();
		}
	}
	
	public function updateAttendence($user, $week, $day, $value)
	{
		// secure here against injections
		$allowed_days = array('mon', 'tue', 'wed', 'thu', 'fri');
		if (!in_array($day, $allowed_days))
			return false;
		
		$sql = "SELECT
	  YEAR(set_start) AS year
	, att_user AS user
	, att_mon  AS mon
	, att_tue  AS tue
	, att_wed  AS wed
	, att_thu  AS thu
	, att_fri  AS fri
FROM
	settings
LEFT JOIN
	attendences ON att_year = CAST(STRFTIME('%Y', set_start) AS INT)
	           AND att_user = :user
	           AND att_week = :week
;";
		
		try
		{
			$stmt = $this->conn->prepare($sql);
			$stmt->bindValue(':user', $user);
			$stmt->bindValue(':week', $week);
			$stmt->execute();

			$res = $stmt->fetch(PDO::FETCH_OBJ);
			
			// currently no entry in table
			if ($res->user == null)
			{
				if (!$value)
				{
					// no entry and no attendence => SKIP
					return true;
				}
				
				// no entry but attendence => create new entry
				$sql = "INSERT INTO attendences VALUES(:user, :week, :year, :mon, :tue, :wed, :thu, :fri);";
				$stmt = $this->conn->prepare($sql);
				$stmt->bindValue(':user', $user);
				$stmt->bindValue(':week', $week);
				$stmt->bindValue(':year', $res->year);
				$stmt->bindValue(':mon', $day == 'mon');
				$stmt->bindValue(':tue', $day == 'tue');
				$stmt->bindValue(':wed', $day == 'wed');
				$stmt->bindValue(':thu', $day == 'thu');
				$stmt->bindValue(':fri', $day == 'fri');
				return $stmt->execute();
			}
			// entry in table found => update/delete
			else
			{
				$res->$day = $value ? 1 : 0;
				
				$found = false;
				foreach ($allowed_days as $try)
				{
					if ($res->$try == 1)
						$found = true;
				}
				
				// found at least one day with attendence left
				if ($found)
				{
					$sql = "UPDATE attendences
SET
	  att_mon = :mon
	, att_tue = :tue
	, att_wed = :wed
	, att_thu = :thu
	, att_fri = :fri
WHERE
	att_user = :user
	AND att_week = :week
	AND att_year = :year
;";
					$stmt = $this->conn->prepare($sql);
					$stmt->bindValue(':user', $user);
					$stmt->bindValue(':week', $week);
					$stmt->bindValue(':year', $res->year);
					$stmt->bindValue(':mon', $res->mon == 1);
					$stmt->bindValue(':tue', $res->tue == 1);
					$stmt->bindValue(':wed', $res->wed == 1);
					$stmt->bindValue(':thu', $res->thu == 1);
					$stmt->bindValue(':fri', $res->fri == 1);
					return $stmt->execute();
				}
				// user not attendent in this week => delete row
				else
				{
					$sql = "DELETE FROM attendences WHERE att_user = :user AND att_week = :week AND att_year = :year;";
					$stmt = $this->conn->prepare($sql);
					$stmt->bindValue(':user', $user);
					$stmt->bindValue(':week', $week);
					$stmt->bindValue(':year', $res->year);
					return $stmt->execute();
				}
			}
		}
		catch (PDOException $e)
		{
			return false;
		}
	}

	/**
	 * Gets all attendences of all users by a week.
	 *
	 * @param   integer   $week  The wanted week.
	 * @return  object[]         An array with attendences of all users.
	 */
	public function getAttendenceWeek($week)
	{
		$sql = "SELECT
	  usr_id                            AS id
	, usr_firstname || ' ' || usr_name  AS name
	, usr_class                         AS class
	, usr_qualification                 AS qualification
	, att_mon                           AS mon
	, att_tue                           AS tue
	, att_wed                           AS wed
	, att_thu                           AS thu
	, att_fri                           AS fri
FROM
	attendences
JOIN
	users ON usr_id = att_user
JOIN
	settings ON set_id = 1
WHERE
	att_year = CAST(STRFTIME('%Y', set_start) AS INT)
	AND
	att_week = :week
ORDER BY
	usr_name
;";

		try
		{
			$stmt = $this->conn->prepare($sql);
			$stmt->bindValue(':week', $week);
	
			$stmt->execute();
	
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}
		catch (PDOException $e)
		{
			return array();
		}
	}

	/**
	 * Sets the attendences for a user for the current schoolyear.
	 *
	 * @param   integer   $id           The user's id.
	 * @param   object[]  $attendences  An array with all attendences for the school year.
	 * @return  boolean                 True on success, otherwise false.
	 */
	public function setAttendences($id, $attendences)
	{
		$sql = "SELECT CAST(STRFTIME('%Y', set_start) AS INT) AS year FROM settings WHERE set_id = 1;";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();

		$year = $stmt->fetch(PDO::FETCH_OBJ)->year;

		try
		{
			$this->conn->beginTransaction();

			$stmt = $this->conn->prepare("DELETE FROM attendences WHERE att_user = :user AND att_year = :year;");
			$stmt->bindValue(':user', $id);
			$stmt->bindValue(':year', $year);
			$stmt->execute();

			foreach ($attendences as $week => $att)
			{
				$stmt = $this->conn->prepare("INSERT INTO attendences (
	  att_user
	, att_year
	, att_week
	, att_mon
	, att_tue
	, att_wed
	, att_thu
	, att_fri
) VALUES (
	  :id
	, :year
	, :week
	, :mon
	, :tue
	, :wed
	, :thu
	, :fri
);");

				$stmt->bindValue(':id', $id);
				$stmt->bindValue(':year', $year);
				$stmt->bindValue(':week', $week);
				$stmt->bindValue(':mon', (isset($att->mon) && $att->mon), PDO::PARAM_BOOL);
				$stmt->bindValue(':tue', (isset($att->tue) && $att->tue), PDO::PARAM_BOOL);
				$stmt->bindValue(':wed', (isset($att->wed) && $att->wed), PDO::PARAM_BOOL);
				$stmt->bindValue(':thu', (isset($att->thu) && $att->thu), PDO::PARAM_BOOL);
				$stmt->bindValue(':fri', (isset($att->fri) && $att->fri), PDO::PARAM_BOOL);
				$stmt->execute();
			}

			$this->conn->commit();
			return true;
		}
		catch (PDOException $e)
		{
			$this->conn->rollBack();
			return false;
		}
	}

	/**
	 * Gets the disposed users for a week.
	 *
	 * @param   integer   $week  The wanted week.
	 * @return  object[]         An array with all disposed users.
	 */
	public function getDuty($week)
	{
		$sql = "SELECT
	  usr_id                            AS id
	, usr_firstname || ' ' || usr_name  AS name
	, usr_class                         AS class
	, dut_mon                           AS mon
	, dut_tue                           AS tue
	, dut_wed                           AS wed
	, dut_thu                           AS thu
	, dut_fri                           AS fri
FROM
	duties
JOIN
	users ON usr_id = dut_user
JOIN
	settings ON set_id = 1
WHERE
	dut_year = CAST(STRFTIME('%Y', set_start) AS INT)
	AND
	dut_week = :week
ORDER BY
	usr_name
;";

		try
		{
			$stmt = $this->conn->prepare($sql);
			$stmt->bindValue(':week', $week);
			$stmt->execute();
			
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}
		catch (PDOException $e)
		{
			return array();
		}
	}

	/**
	 * Sets the disposed users.
	 *
	 * @param   object   $duty  An object with the date information and an array with disposed users and times.
	 * @return  boolean         True on success, otherwise false.
	 */
	public function setDuty($duty)
	{
		try
		{
			$this->conn->beginTransaction();

			$stmt = $this->conn->prepare("DELETE FROM duties WHERE dut_year = :year AND dut_week = :week;");
			$stmt->bindValue(':year', $duty->year);
			$stmt->bindValue(':week', $duty->week);
			$stmt->execute();

			foreach ($duty->duty as $user => $d)
			{
				$stmt = $this->conn->prepare("INSERT INTO duties (
	  dut_user
	, dut_year
	, dut_week
	, dut_mon
	, dut_tue
	, dut_wed
	, dut_thu
	, dut_fri
) VALUES (
	  :user
	, :year
	, :week
	, :mon
	, :tue
	, :wed
	, :thu
	, :fri
);");

				$stmt->bindValue(':user', $user);
				$stmt->bindValue(':year', $duty->year);
				$stmt->bindValue(':week', $duty->week);
				$stmt->bindValue(':mon', (isset($d->mon) && $d->mon), PDO::PARAM_BOOL);
				$stmt->bindValue(':tue', (isset($d->tue) && $d->tue), PDO::PARAM_BOOL);
				$stmt->bindValue(':wed', (isset($d->wed) && $d->wed), PDO::PARAM_BOOL);
				$stmt->bindValue(':thu', (isset($d->thu) && $d->thu), PDO::PARAM_BOOL);
				$stmt->bindValue(':fri', (isset($d->fri) && $d->fri), PDO::PARAM_BOOL);
				$stmt->execute();
			}

			$this->conn->commit();
			return true;
		}
		catch (PDOException $e)
		{
			$this->conn->rollBack();
			return false;
		}
	}

	/**
	 * Gets the disposed users with flags for illness.
	 *
	 * @param   integer   $week  The wanted week.
	 * @return  object[]         An array with disposed users.
	 */
	public function getPlan($week)
	{
		$sql = "SELECT
	  usr_id                                                    AS id
	, usr_firstname || ' ' || usr_name                          AS name
	, usr_class                                                 AS class
	, dut_mon                                                   AS mon
	, dut_tue                                                   AS tue
	, dut_wed                                                   AS wed
	, dut_thu                                                   AS thu
	, dut_fri                                                   AS fri
	, (CASE WHEN dut_mon = 1 AND att_mon = 0 THEN 1 ELSE 0 END) AS flag_mon
	, (CASE WHEN dut_tue = 1 AND att_tue = 0 THEN 1 ELSE 0 END) AS flag_tue
	, (CASE WHEN dut_wed = 1 AND att_wed = 0 THEN 1 ELSE 0 END) AS flag_wed
	, (CASE WHEN dut_thu = 1 AND att_thu = 0 THEN 1 ELSE 0 END) AS flag_thu
	, (CASE WHEN dut_fri = 1 AND att_fri = 0 THEN 1 ELSE 0 END) AS flag_fri
FROM
	duties
JOIN
	attendences ON att_user = dut_user
	           AND att_year = dut_year
	           AND att_week = dut_week
JOIN
	users ON usr_id = dut_user
JOIN
	settings ON set_id = 1
WHERE
	dut_year = CAST(STRFTIME('%Y', set_start) AS INT)
	AND
	dut_week = :week
ORDER by
	usr_name
;";

		try
		{
			$stmt = $this->conn->prepare($sql);
			$stmt->bindValue(':week', $week);
			$stmt->execute();
			
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}
		catch (PDOException $e)
		{
			return array();
		}
	}

	/**
	 * Sets the modified attendence for a user.
	 *
	 * @param   integer   $id           The user's id.
	 * @param   object[]  $attendences  An array with all attendences for the next weeks.
	 * @return  boolean                 True on success, otherwise false.
	 */
	public function setSick($id, $attendences)
	{
		$sql = "SELECT CAST(STRFTIME('%Y', set_start) AS INT) AS year FROM settings WHERE set_id = 1;";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();

		$year = $stmt->fetch(PDO::FETCH_OBJ)->year;

		try
		{
			$this->conn->beginTransaction();

			foreach ($attendences as $week => $att)
			{
				$stmt = $this->conn->prepare("DELETE FROM
	attendences
WHERE
	att_user = :user
	AND
	att_year = :year
	AND
	att_week = :week
;");
				$stmt->bindValue(':user', $id);
				$stmt->bindValue(':year', $year);
				$stmt->bindValue(':week', $week);
				$stmt->execute();

				$stmt = $this->conn->prepare("INSERT INTO attendences (
	  att_user
	, att_year
	, att_week
	, att_mon
	, att_tue
	, att_wed
	, att_thu
	, att_fri
) VALUES (
	  :user
	, :year
	, :week
	, :mon
	, :tue
	, :wed
	, :thu
	, :fri
);");

				$stmt->bindValue(':user', $id);
				$stmt->bindValue(':year', $year);
				$stmt->bindValue(':week', $week);
				$stmt->bindValue(':mon', (isset($att->mon) && $att->mon), PDO::PARAM_BOOL);
				$stmt->bindValue(':tue', (isset($att->tue) && $att->tue), PDO::PARAM_BOOL);
				$stmt->bindValue(':wed', (isset($att->wed) && $att->wed), PDO::PARAM_BOOL);
				$stmt->bindValue(':thu', (isset($att->thu) && $att->thu), PDO::PARAM_BOOL);
				$stmt->bindValue(':fri', (isset($att->fri) && $att->fri), PDO::PARAM_BOOL);
				$stmt->execute();
			}

			$this->conn->commit();
			return true;
		}
		catch (PDOException $e)
		{
			$this->conn->rollBack();
			return false;
		}
	}

}

?>
