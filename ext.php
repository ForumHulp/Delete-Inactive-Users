<?php
/**
*
* @package Inactive Users
* @copyright (c) 2014 ForumHulp.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace forumhulp\deleteinactiveusers;

class ext extends \phpbb\extension\base
{
	function enable_step($old_state)
	{
		switch ($old_state)
		{
			case '': // Empty means nothing has run yet
			if (empty($old_state))
			{
				$this->container->get('user')->add_lang_ext('forumhulp/deleteinactiveusers', 'info_acp_deleteinactiveusers');
				$this->container->get('template')->assign_var('L_EXTENSION_ENABLE_SUCCESS', $this->container->get('user')->lang['EXTENSION_ENABLE_SUCCESS'] .
					(isset($this->container->get('user')->lang['INACTIVE_USERS_NOTICE']) ?
						sprintf($this->container->get('user')->lang['INACTIVE_USERS_NOTICE'],
								$this->container->get('user')->lang['ACP_CAT_GENERAL'],
								$this->container->get('user')->lang['ACP_BOARD_FEATURES'],
								$this->container->get('user')->lang['INACTIVE_USERS_DAYS']) : ''));
			}

			// Run parent enable step method
			return parent::enable_step($old_state);

			break;

			default:

				// Run parent enable step method
				return parent::enable_step($old_state);

			break;
		}
	}
}
