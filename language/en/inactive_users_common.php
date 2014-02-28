<?php
/**
*
* @package Inactive Users
* @copyright (c) 2014 ForumHulp.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
        exit;
}

if (empty($lang) || !is_array($lang))
{
        $lang = array();
}

$lang = array_merge($lang, array(
    'INACTIVE_USERS_DAYS' => 'Delete inactive users',
	'INACTIVE_USERS_DAYS_EXPLAIN'	=> 'Days registered before cron wil delete new inacive users who never where logged in'
));