<?php

/*              janus
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

if (JANUS_DEBUG) echo "<hr>========= " . __FILE__. " =================<hr>";

/*******************************************************
 *
 *******************************************************/
function echoArray($t, $title='', $bolExit = false){
 
  if(is_string($t)) return echoGPF($t, $title,  $bolExit);
  
  $tr = print_r($t,true);
  if ($title==''){
    echo "<code><pre>{$tr}</pre></code><hr>";
  }else{
    echo "<hr>{$title}<hr><code><pre>{$tr}</pre></code>";
  }
  echo "nb items = ". count($t) . "<hr>";
  if ($bolExit) exit();
}

/*******************************************************
 *
 *******************************************************/
function displayArray($t, $title = "", $bolExit = false){
  echoArray($t, $title,$bolExit);  
}

/**
*
**/
function echoGPF($arr = 'GPF' ,$title = '',  $bolExit = false)
{
   $arr = strtoupper($arr);
   echo "<hr>";
   if ($title) echo "message : {$title}<br>";
   if (strPos($arr,'G') !== false && count($_GET) > 0)   echo "_GET   : <pre>" . print_r($_GET, true)   . "</pre><hr>";
   if (strPos($arr,'P') !== false && count($_POST) > 0)  echo "_POST  : <pre>" . print_r($_POST, true)  . "</pre><hr>";
   if (strPos($arr,'F') !== false && count($_FILES) > 0) echo "_FILES : <pre>" . print_r($_FILES, true) . "</pre><hr>";

   if($bolExit) exit($title);
   return true;
}

function echoRequest($arr = 'GPFC' ,$title = '',  $bolExit = false)
{
   $arr = strtoupper($arr);
   echo "<hr>";
   if ($title) echo "message : {$title}<br>";
   if (strPos($arr,'G') !== false) echo "_GET    : <pre>" . print_r($_GET, true)   . "</pre><hr>";
   if (strPos($arr,'P') !== false) echo "_POST   : <pre>" . print_r($_POST, true)  . "</pre><hr>";
   if (strPos($arr,'F') !== false) echo "_FILES  : <pre>" . print_r($_FILES, true) . "</pre><hr>";
   if (strPos($arr,'C') !== false) echo "_COOKIE : <pre>" . print_r($_COOKIE, true) . "</pre><hr>";

   if($bolExit) exit($title);
   return true;
}



/***************************************************************************
Charge les fichiers de langues
****************************************************************************/
function loadLanguageJanus($file){
global $xoopsConfig;
  // au cas ou un autre module tenterait de charger les constantes de langue  
  if(defined ('_CO_JANUS_ABOUT_BY')) return true;
  
  $root =  JANUS_PATH . "/language/"; 
  $lang = $root . $xoopsConfig['language'] . "/" . $file . ".php";
//echo "<hr>lg = {$lang}<hr>";
  if (!file_exists($lang)){
    $lang = $root . "english/{$file}.php";
  }
  
  if (file_exists($lang)){
//echo "<hr>lg [{$file}] = {$lang}<hr>";
    include_once $lang;
  }
  return true;
exit;  
}

/***************************************************************************
Charge les fichiers JS
****************************************************************************/
function load_js($file, $suffix = '.min'){
global $xoTheme;
    $mini = str_replace('.js', $suffix  . '.js',$file);
      if(file_exists(XOOPS_ROOT_PATH . $mini)){
        $xoTheme->addScript(XOOPS_URL . $mini);
      }else{

        $xoTheme->addScript(XOOPS_URL . $file);
      }
}


/***************************************************************************
renvoi la liste des colonnes d'un tables en excluant certains champ
****************************************************************************/
function getColoumnsFromTable($name, $strFldExclude = '', $asArray = false){
global $xoopsDB;

    $arrExclus = explode(',', $strFldExclude);
    $sql = 'SHOW COLUMNS FROM '. $xoopsDB->prefix($name) . ';';
    $rst = $xoopsDB->query($sql);
    $fldArr = array();
    while ($row = $xoopsDB->fetchArray($rst)){
    //echoArray($row);
        if(!in_array($row['Field'], $arrExclus)) $fldArr[] = $row['Field'];
    } 
    if($asArray){
        return $fldArr;
    }else{
        return implode(',', $fldArr);
    }
}
        

/**
 * getUserByEmail : renvoie un objet euser qui correspond à l'mail en parametre
 * @param $email string : email a rechercher
 * @return object
function getUserByEmail($email){
        if (!$email) return null;
        
        $usershandler = xoops_getHandler('user');        
        $criteria = new Criteria('email', $email);
        $users = $usershandler->getObjects($criteria);
        if(count($users) > 0){
            return array_shift($users);
        }else{
            return null;
        }

}
 */
 
/**
 * getUserByEmail : renvoie un objet euser qui correspond à l'mail en parametre
 * @param $email string : email a rechercher
 * @return object
 */
function getUser($search, $field='email'){
        if (!$search) return null;
        
        $usershandler = xoops_getHandler('user');        
        $criteria = new Criteria($field, $search);
        $users = $usershandler->getObjects($criteria);
        if(count($users) > 0){
            return array_shift($users);
        }else{
            return null;
        }

}

          
?>
