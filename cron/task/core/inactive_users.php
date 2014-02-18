<?php
/**
*
* @package Inactive Users
* @copyright (c) 2014 ForumHulp.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbb\inactive_users\cron\task\core;

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

class inactive_users extends \phpbb\cron\task\base
{
	protected $phpbb_root_path;
	protected $php_ext;
	protected $config;
	protected $db;
	protected $table_prefix;

	/**
	* Constructor.
	*
	* @param string $phpbb_root_path The root path
	* @param string $php_ext The PHP extension
	* @param phpbb_config $config The config
	* @param phpbb_db_driver $db The db connection
	*/
	public function __construct($phpbb_root_path, $php_ext, \phpbb\config\config $config, \phpbb\db\driver\driver $db)
	{
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->config = $config;
		$this->db = $db;
	}

	/**
	* Runs this cron task.
	*
	* @return null
	*/
	public function run()
	{
		global $db;
		$expire_date = time() - ($this->config['inactive_users_days'] * 86400);
		$user_list = array();
	
		$sql = 'SELECT user_id, username, user_regdate FROM ' . USERS_TABLE . ' WHERE user_type = 1 AND user_new = 1 AND user_regdate < ' . $expire_date;
		$result = $db->sql_query($sql);
	
		while ($row = $db->sql_fetchrow($result))
		{
			$user_list[$row['user_id']] = $row['username'];
		}
		$db->sql_freeresult($result);
		
	
		if (sizeof($user_list))
		{
			$sql = 'DELETE FROM ' . USERS_TABLE . ' WHERE ' . $db->sql_in_set('user_id', array_keys($user_list));
		//	$db->sql_query($sql);
		//	add_log('admin', 'LOG_INACTIVE_DELETE', implode(', ', $user_list));
		} else
		{
			add_log('admin', 'NO_INACTIVE_USERS');
		}
		set_config('inactive_users_last_gc', time(), true);
	}

	/**
	* Returns whether this cron task can run, given current board configuration.
	*
	* @return bool
	*/
	public function is_runnable()
	{
		return (bool) true;
	}

	/**
	* Returns whether this cron task should run now, because enough time
	* has passed since it was last run.
	*
	* @return bool
	*/
	public function should_run()
	{
		return $this->config['inactive_users_last_gc'] < time() - $this->config['inactive_users_gc'];
	}
}
