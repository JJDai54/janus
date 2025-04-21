<?php
/*              janus
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

define('JANUS_DEBUG', false);
if (JANUS_DEBUG) echo "<hr>========= " . __FILE__. " =================<hr>";

define('JANUS_FW', basename(dirname(dirname(__FILE__))));
define('JANUS_PATH', XOOPS_ROOT_PATH . '/Frameworks/' . JANUS_FW);
define('JANUS_URL',  XOOPS_URL       . '/Frameworks/' . JANUS_FW);
define('JANUS_URL_XFORM', JANUS_URL . '/class/xoopsform');
define('JANUS_XFORM_URL',  JANUS_URL_XFORM); //obsolete, a virer àa l'occcasion

include_once JANUS_PATH . "/xoops_version.php";

define('JANUS_VERSION', $versionArr['version']);
define('JANUS_DATE_RELEASE', $versionArr['status']);
define('JANUS_STATUS', $versionArr['release_date']);
define('JANUS_FULL_VERSION', $versionArr['name'] .'-'. JANUS_VERSION .'-'. JANUS_STATUS .'-'. JANUS_DATE_RELEASE);
//------------------------------------------------------------

define('JANUS_PATH_XFORMS', JANUS_PATH . '/class/xoopsform');
define('JANUS_URL_XFORMS',  JANUS_URL  . '/class/xoopsform');
define('JANUS_PATH_CSS', JANUS_PATH . '/css');
//echo"<hr>JANUS_PATH => " . JANUS_PATH . "<br>JANUS_PATH_XFORMS => " . JANUS_PATH_XFORMS . "<hr>";

define('JANUS_ICO16', JANUS_URL . "/images/icons/16");
define('JANUS_ICO32', JANUS_URL . "/images/icons/32");


//echo __FILE__."<hr>";
// define  ("XOOPS_JANUS_PATH", XOOPS_ROOT_PATH ."/modules/jjd_tools/_xoops_plus");
// define  ("XOOPS_JANUS_URL", XOOPS_URL . "/modules/jjd_tools/_xoops_plus");

?>

