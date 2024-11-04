<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Quizmaker module for xoops
 *
 * @copyright     2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        quizmaker
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         Jean-Jacques Delalandre - Email:<jjdelalandre@orange.fr> - Website:<http://xmodules.jubile.fr>
 */

// 
$moduleDirName      = basename(__DIR__);
$moduleDirNameUpper = mb_strtoupper($moduleDirName);
// ------------------- Informations ------------------- //


$versionArr = [
	'name'                => 'janus',
	'version'             => 4.4,
	'status'              => 'beta 1',
	'release_date'        => '2024/10/28',
	'description'         => 'Ce Framework a pour but de mutualiser des fonctionalité manquante dans le noyau',
	'author'              => 'Jean-Jacques Delalandre',
	'author_mail'         => 'jjdelalandre@orange.fr',
	'author_website_url'  => 'https://xmodules.oritheque.fr',
	'author_website_name' => 'Origami du Monde',
	'license'             => 'GPL 2.0 or later',
	'license_url'         => 'http://www.gnu.org/licenses/gpl-3.0.en.html',
	'min_php'             => '5.5',
	'min_xoops'           => '2.5.9',
	'min_admin'           => '1.2',
	'min_db'              => array('mysql' => '5.5', 'mysqli' => '5.5'),
];


?>


