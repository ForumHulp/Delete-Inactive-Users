<?php
/**
*
* @package Inactive Users
* @copyright (c) 2014 ForumHulp.com; Estonian language pack by phpBBeesti.com
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
	'INACTIVE_USERS_DAYS' => 'Kustuta mitteaktiivsed kasutajad',
	'INACTIVE_USERS_DAYS_EXPLAIN'	=> 'Registreerimisest möödunud päevade arv, mil <i>cron</i> saadab meeldetuletuse. Üks nädal hiljem, uued mitteaktiivsed liikmed, kes ei ole kordagi sisseloginud - kustutatakse.',
	'SEND_MESSAGE'					=> 'Send message',
	'LOG_INACTIVE_USERS'			=> '<strong>Delete inactive users</strong><br />» %s',
	'INACTIVE_USERS_NOTICE'			=> '<div class="phpinfo"><p class="entry">Config settings in %1$s » %2$s » %3$s.</p></div>'
));
