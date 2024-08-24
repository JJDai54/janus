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
 * @copyright      module for xoops
 * @license        GPL 2.0 or later
 * @package        quizmaker
 * @since          1.0
 * @min_xoops      2.5.11
 * @author         Wedega - Email:<webmaster@wedega.com> - Website:<https://wedega.com>
 * @version        $Id: 1.0 images.php 1 Mon 2018-03-19 10:04:51Z XOOPS Project (www.xoops.org) $
 */

use Xmf\Request;

require __DIR__ . '/header.php';
$clPerms->checkAndRedirect('global_ac', QUIZMAKER_PERMIT_CLONE,'QUIZMAKER_PERMIT_CLONE', "index.php");

// It recovered the value of argument op in URL$
$op = Request::getString('op', 'list');
$clCloneModule = new CloneModule($quizmakerHelper->getModule());


switch ($op) {
    case 'list':
    default:
        echo $clCloneModule->getForm();
        break;

    case 'submit':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header('clone.php', 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        
        $clone = Request::getString('clone', '', 'POST');
        $clCloneModule->clone($clone);

        break;
}
require __DIR__ . '/footer.php';




