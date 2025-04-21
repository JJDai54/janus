<?php
//namespace JANUS;
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
 * @copyright     2020 XOOPS Project (https://github.com/JJDai54)
 * @license        GPL 2.0 or later
 * @package        janus
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         Jean-Jacques Delalandre - Email:<jjdelalandre@orange.fr> - Website:<https://xmodules.oritheque.fr.fr>
 */

use JANUS AS JANUS;

/*
Chargement des constantes commune au frontoffice et au backoffice
*/
include_once (dirname(__FILE__) . "/include/constantes.php");
if (JANUS_DEBUG) echo "<hr>========= " . __FILE__. " =================<hr>";


class Janus
{



  /**
   * @var Singleton
   * @access private
   * @static
   */
   private static $_instance = null;
 
   /**
    * Constructeur de la classe
    *
    * @param void
    * @return void
    */
   private function __construct($mode = 0) {  
        if($mode == 1)
            self::load_frontoffice();
        else
            self::load_backoffice();
            
    }

 
   /**
    * Méthode qui crée l'unique instance de la classe
    * si elle n'existe pas encore puis la retourne.
    *
    * @param void
    * @return Singleton
    */
   public static function getInstance($mode = 0) {
 
     if(is_null(self::$_instance)) {
       self::$_instance = new Janus();  
     }
 
     return self::$_instance;
   }
   

/*********************************************************************
 *                  Load Janus
 * *******************************************************************/
public static function loadAllXForms(){
    \JANUS\loadAllXForms();
}
/*********************************************************************
 *                  Load Janus
 * *******************************************************************/
public static function load_frontoffice(){
}

/*********************************************************************
 *                  Load Janus
 * *******************************************************************/
public static  function load_backoffice(){

echo "function load_backoffice<br>";
    global $xoopsConfig;
    //---------------------------------------------------------------------

    /*********************************************************************
     *                  functions du back office
     * *******************************************************************/
    include_once (JANUS_PATH . "/include/globales-functions.php");
    include_once (JANUS_PATH . "/include/fw-functions.php");
    include_once (JANUS_PATH . "/include/fso.php");
    include_once (JANUS_PATH . "/include/date-functions.php");
    include_once (JANUS_PATH . "/include/xform-functions.php");
    include_once (JANUS_PATH . "/include/language-functions.php");

    /*********************************************************************
     *                  classe du back office
     * *******************************************************************/
    include_once (JANUS_PATH . "/class/About.php");
    include_once (JANUS_PATH . "/class/Feedback.php");
    include_once (JANUS_PATH . "/class/Permissions.php");

    /*********************************************************************
     *                  classes Goffy
     *Classes déplacées des modules dans le framework pour mutualiser le code
     * *******************************************************************/
    include_once (JANUS_PATH . "/Goffy/class/XoopsConfirm.php");


    //pas utile de charger cette classe systematiqueent
    //include_once (JANUS_PATH . "/class/CloneModule.php");

    /*********************************************************************
     *                  fichiers de langues
     * constante de langue générique de l'admin (Definition tout module, new,add,edit,...)
     * *******************************************************************/
    loadLanguageJanus('common');
}


} // ==================== END CLASS ============================
?>


