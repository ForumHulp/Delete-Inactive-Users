<?php
/**
*
* @package Inactive Users
* @copyright (c) 2014 ForumHulp.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace forumhulp\deleteinactiveusers\cron\task\core;

/**
* @ignore
*/

class delete_inactive_users extends \phpbb\cron\task\base
{
	protected $user;
	protected $config;
	protected $config_text;
	protected $db;
	protected $log;
	protected $phpbb_root_path;
	protected $php_ext;

	/**
	* Constructor.
	*
	* @param string $phpbb_root_path The root path
	* @param string $php_ext The PHP extension
	* @param phpbb_config $config The config
	* @param phpbb_db_driver $db The db connection
	*/
	public function __construct(\phpbb\user $user, \phpbb\config\config $config, \phpbb\config\db_text $config_text, \phpbb\db\driver\driver_interface $db, \phpbb\log\log $log, $phpbb_root_path, $php_ext)
	{
		$this->user = $user;
		$this->config = $config;
		$this->config_text = $config_text;
		$this->db = $db;
		$this->log = $log;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
}

	/**
	* Runs this cron task.
	*
	* @return null
	*/
	public function run()
	{
		$this->user->add_lang(array('acp/common'));
		$expire_date = time() - ($this->config['delete_inactive_users_days'] * 86400);
		$msg_list = $delete_list = $not_deleted_yet = array();
		$user_warnlist = json_decode($this->config_text->get('delete_inactive_users_warning'), true);

		$sql = 'SELECT user_id, username, user_email, user_lang, user_regdate, user_actkey 
				FROM ' . USERS_TABLE . ' 
				WHERE user_type = ' . USER_INACTIVE . ' AND user_inactive_reason = 1 AND user_inactive_time <> 0 AND user_new = 1 AND user_regdate < ' . $expire_date;
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			// Send reminder
			if (!in_array($row['user_id'], array_keys($user_warnlist)))
			{
				$msg_list[$row['user_id']] = array(
					'name' => $row['username'],
					'email' => $row['user_email'],
					'regdate' => $row['user_regdate'],
					'useractkey' => $row['user_actkey'],
					'lang' => $row['user_lang'],
					'time' => time()
				);

			} else
			{
				// Delete user
				if ($user_warnlist[$row['user_id']]['time'] < time() - 604800)
				{
					$delete_list[$row['user_id']] = $row['username'];
					unset($msg_list[$row['user_id']]);
				} else
				{
					$not_deleted_yet[$row['user_id']] = array(
						'name' => $row['username'],
						'email' => $row['user_email'],
						'regdate' => $row['user_regdate'],
						'useractkey' => $row['user_actkey'],
						'lang' => $row['user_lang'],
						'time' => $user_warnlist[$row['user_id']]['time']
					);
				}
			}
		}
		$this->db->sql_freeresult($result);

		if (sizeof($msg_list))
		{
			if ($this->config['email_enable'])
			{
				if (!class_exists('messenger'))
				{
					include($this->phpbb_root_path . 'includes/functions_messenger.' . $this->php_ext);
				}

				$server_url = generate_board_url();
				$messenger = new \messenger(false);

				foreach($msg_list as $key => $value)
				{
					$messenger->template('user_remind_inactive', $value['lang']);

					$messenger->to($value['email'], $value['name']);

					$messenger->headers('X-AntiAbuse: Board servername - ' . $this->config['server_name']);
					$messenger->headers('X-AntiAbuse: User_id - ' . $key);
					$messenger->headers('X-AntiAbuse: Username - ' . $value['name']);
					$messenger->headers('X-AntiAbuse: User IP - ' . $this->user->ip);

					$messenger->assign_vars(array(
						'USERNAME'		=> htmlspecialchars_decode($value['name']),
						'REGISTER_DATE'	=> date('g:ia \o\n l jS F Y', $value['regdate']),
						'U_ACTIVATE'	=> $server_url . '/ucp.'. $this->php_ext . '?mode=activate&u=' . $key . '&k=' . $value['useractkey']
						)
					);

					$messenger->send(NOTIFY_EMAIL);
				}
				$userlist = array_map(function ($entry)
				{
					return $entry['name'];
				}, $msg_list);

				$this->log->add('admin', $this->user->data['user_id'], $this->user->data['session_ip'], 'LOG_INACTIVE_REMIND', false, array(implode(', ', $userlist)));
			}
		}

		if (sizeof($delete_list))
		{
			$sql = 'DELETE FROM ' . USERS_TABLE . ' WHERE ' . $this->db->sql_in_set('user_id', array_keys($delete_list));
			$this->db->sql_query($sql);
			$this->log->add('admin', $this->user->data['user_id'], $this->user->data['session_ip'], 'LOG_INACTIVE_DELETE', false, array(implode(', ', $delete_list)));
		}

		if (!sizeof($delete_list) && !sizeof($msg_list))
		{
			$this->log->add('admin', $this->user->data['user_id'], $this->user->data['session_ip'], 'LOG_INACTIVE_USERS', false, array($this->user->lang['NO_INACTIVE_USERS']));
		}
		$msg_list += $not_deleted_yet;

		$this->config_text->set('delete_inactive_users_warning', json_encode($msg_list));
		$this->config->set('delete_inactive_users_last_gc', time());
	}

	/**
	* Returns whether this cron task can run, given current board configuration.
	*
	* @return bool
	*/
	public function is_runnable()
	{
		return (bool) $this->config['delete_inactive_users_days'];
	}

	/**
	* Returns whether this cron task should run now, because enough time
	* has passed since it was last run.
	*
	* @return bool
	*/
	public function should_run()
	{
		return $this->config['delete_inactive_users_last_gc'] < time() - $this->config['delete_inactive_users_gc'];
	}
}
