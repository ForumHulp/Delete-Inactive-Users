<?php
/**
*
* @package Inactive Users
* @copyright (c) 2014 ForumHulp.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace forumhulp\deleteinactiveusers\migrations;

class update_delete_inactive_users extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\forumhulp\deleteinactiveusers\migrations\install_delete_inactive_users');
	}

	public function update_data()
	{
		return array(
			array('config_text.update', array('delete_inactive_users_warning', '[[]]'))
		);
	}
}
