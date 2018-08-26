<?php
/**
*
* @package Inactive Users
* @copyright (c) 2014 ForumHulp.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace forumhulp\deleteinactiveusers\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/* @var \phpbb\controller\helper */
	protected $helper;

	/**
	* Constructor
	*
	* @param \phpbb\controller\helper    $helper        Controller helper object
	*/
	public function __construct(\phpbb\controller\helper $helper)
	{
		$this->helper = $helper;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.acp_board_config_edit_add'	=> 'load_config_on_setup',
		);
	}

	public function load_config_on_setup($event)
	{
		if ($event['mode'] == 'features')
		{
			$display_vars = $event['display_vars'];

			$add_config_var['delete_inactive_users_days'] = [
					'lang' 		=> 'INACTIVE_USERS_DAYS',
					'validate'	=> 'int',
					'type'		=> 'custom',
					'function' => __NAMESPACE__.'\listener::delete_users_options',
					'explain'	=> true
			];
			$display_vars['vars'] = phpbb_insert_config_array($display_vars['vars'], $add_config_var, array('after' =>'allow_quick_reply'));
			$event['display_vars'] = array('title' => $display_vars['title'], 'vars' => $display_vars['vars']);
		}
	}


	static function delete_users_options($value, $key)
	{
		global $config, $user;
		
		return '<input type="text" name="config[delete_inactive_users_days]" value="' . $config['delete_inactive_users_days'] . '" />
		' . $user->lang['SEND_MESSAGE'] . ' <label><input name="config[delete_inactive_send_message]" class="radio" id="delete_inactive_send_message" type="radio"' . (($config['delete_inactive_send_message']) ? ' checked="checked"' : '') . ' value="1"> ' . $user->lang['YES'] . '</label> <label><input name="config[delete_inactive_send_message]" class="radio" type="radio"' . (($config['delete_inactive_send_message']) ? '' : ' checked="checked"') . ' value="0"> ' . $user->lang['NO'] . '</label>';
		
	}
}
