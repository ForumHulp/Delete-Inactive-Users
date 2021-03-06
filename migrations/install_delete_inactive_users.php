<?php
/**
*
* @package Inactive Users
* @copyright (c) 2014 ForumHulp.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace forumhulp\deleteinactiveusers\migrations;

class install_delete_inactive_users extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config_text->get['delete_inactive_users_warning']);
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\dev');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('delete_inactive_users_gc', 86400)),
			array('config.add', array('delete_inactive_users_last_gc', '0', 1)),
			array('config.add', array('delete_inactive_users_days', 30)),
			array('config.add', array('delete_inactive_send_message', 1)),
			array('config_text.add', array('delete_inactive_users_warning', 'a:1:{i:0;s:0:"";}'))
		);
	}
}
