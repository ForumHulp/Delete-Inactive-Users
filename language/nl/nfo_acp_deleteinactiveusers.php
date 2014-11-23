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
	'INACTIVE_USERS_DAYS' => 'Verwijder inactieve gebruikers',
	'INACTIVE_USERS_DAYS_EXPLAIN'	=> 'Dagen geregistreerd alvorens een herinnering te sturen. Een week later zullen de nieuwe inactive gebruikers welke nooit ingelogd zijn geweest, verwijderd worden.'
));
