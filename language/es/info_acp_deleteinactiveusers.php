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
	'INACTIVE_USERS_DAYS'			=> 'Borrar usuarios inactivos',
	'INACTIVE_USERS_DAYS_EXPLAIN'	=> 'Días registrado antes de que el cron elimine nuevos usuarios inactivos, que nunca iniciarón sesión',
	'LOG_INACTIVE_USERS'			=> '<strong>Delete inactive users</strong><br />» %s',
	'INACTIVE_USERS_NOTICE'			=> '<div class="phpinfo"><p class="entry">Config settings in %1$s » %2$s » %3$s.</p></div>'
));
