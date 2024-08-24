<?php
/**
 * XOOPS Form Class Elements
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/ 
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         janus
 * @subpackage      form
 * @since           2.5.0
 * @author          JJDai <jjdelalandre@orange.fr>
 * @version         $Id: formbuttontray.php 8066 2011-11-06 05:09:33Z beckmi $
 * 
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
if(defined('JANUS_DEBUG'))
if (JANUS_DEBUG) echo "<hr>========= " . __FILE__. " =================<hr>";

/**
 *
 * @author 		JJDai <jjdelalandre@orange.fr>
 * @package 	janus
 * @access 		public
 */
class JanusPermissions{
var $memberHandler;
var $groupList;
var $fullList;
var $grouppermHandler;
var $dirname;
var $module;
var $mid;
var $isUserAdmin = false;

/***************************************************************************
Renvoie la valeur d'un bit préciser par un index dans la valeur binaire
****************************************************************************/
  function __construct($dirname='')
  {global $xoopsModule, $xoopsUser;
  //echo "<hr>clPermissions : dirname = {$dirname}<hr>";
		$this->memberHandler = xoops_getHandler('member');
		$this->groupList = $this->memberHandler->getGroupList();
		$this->fullList[] = array_keys($this->groupList);
		$this->grouppermHandler = xoops_getHandler('groupperm');
        
        if($dirname){
		  $this->dirname = $dirname;
          $module_handler = xoops_getHandler('module');
          $this->module = $module_handler->getByDirname($this->dirname);
        }else{
          $this->module = $xoopsModule;
		  $this->dirname = $xoopsModule->dirname();
        }
        
        $this->mid = $this->module->getVar('mid');        
        if($xoopsUser)
        $this->isUserAdmin = ($xoopsUser && $xoopsUser->isAdmin($this->module->mid()));


  }


	/**
	 * @public function checkAndRedirect
	 * redirige sur "url" si pas de permisssion
	 *
	 * @param $permName  nom des permissions "gpermName"
	 * @param $permId  id des permisssions "gpermId"
	 * @param $varName  nom de la variable qui contient $permId
	 * @param $url  url de redirection si il n'y a pas de permission
	 * @return true si pas e redirection
	 */
    function checkAndRedirect($permName, $permId, $varName, $url, $adminOk=false){
    global $clPerms;

        if (!$clPerms->isPermit($permName, $permId, $adminOk)){
            $msg =  sprintf(_CO_JANUS_NO_PERM2, $this->module->mid(), $permName,$varName,$permId);
            redirect_header($url, 5, $msg);
        }  
    }
    
	/**
	 * @public function getGroupIds
	 * returns arr liste des groupes
	 *
	 * @param $adminOk bool force topus les groupes si admin
	 * @return array
	 */
	function getGroupIds(){
        global $xoopsUser, $memberHandler;
        
		$currentuid = 0;
		if (isset($xoopsUser) && is_object($xoopsUser)) {
            $currentuid = ($xoopsUser) ? $xoopsUser->uid() : 2;
		}
        
        $mid = $this->module->mid();
		if ($currentuid == 0) {
			$my_group_ids = [XOOPS_GROUP_ANONYMOUS]; //XOOPS_GROUP_ADMIN
		}else{
		    $memberHandler = xoops_getHandler('member');
			$my_group_ids = $memberHandler->getGroupsByUser($currentuid);
        }
        
        //echo "<code>mid = {$mid} - uid = {$currentuid}<br><pre>" . print_r($my_group_ids,true) . "</pre></code><hr>";
		return $my_group_ids;
    }

	/**
	 * @public function getPermNames
	 * returns right for global approve
	 * @return array of permissions name
	 */
 //getObjects(CriteriaElement $criteria = null, $id_as_key = false)
	function getPermNames($itemId = 0, $arrNames = null, $adminOk=false)
	{global $xoopsDB;
        
        if ($arrNames){   
            $arrVal = array_fill(0, count($arrNames), 0);
            $retArr = array_combine($arrNames, $arrVal);
        }else{
            $retArr = [];
        }
        //-----------------------------------------------------
        $lgDirName = strlen($this->dirname) +1;
     
     
        $criteria = new CriteriaCompo(new Criteria('gperm_itemid', $itemId));
        $criteria->add(new Criteria('gperm_modid', $this->module->mid()));
        $GroupIds = implode(',',  $this->getGroupIds());
        $criteria->add(new Criteria('gperm_groupid', "({$GroupIds})", 'IN')) ;
        
        
        
        
        $sql   = 'SELECT * FROM ' . $xoopsDB->prefix('group_permission');
        $sql .= ' ' . $criteria->renderWhere();

        $result = $xoopsDB->query($sql);

        while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
            $permName = substr($myrow['gperm_name'], $lgDirName);
            if (!$arrNames){
                $retArr[$permName] = true; 
            }else if(array_key_exists($permName, $retArr)){
                $retArr[$permName] = true; 
            }
        }
        
//  echoArray($arrNames, "getPermNames 1 ({$lgDirName})");       
//  echoArray($retArr, "getPermNames 2");       
        return  $retArr;
	}
    
	/**
	 * @public function permGlobalApprove
	 * returns right for global approve
	 *
	 * @param $itemId int id des permission
	 * @param $permName string nom des permissions sans le nom du module
	 * @return bool
	 */
	function getPermissions($permName, $itemId = 0, $adminOk=false)
	{

        return $this->isPermit($permName, $itemId, $adminOk);
	}

	function isPermit($permName, $itemId = 0, $adminOk=false)
	{
		global $xoopsUser, $xoopsModule;
        
        if($itemId == 0){
            $ids = $this->getIdsPermissions($permName, $adminOk);
            return(isset($ids));
        }
		$mid = $this->module->mid();
 
		//-----------------------------------------------------------
        //if($xoopsUser->isAdmin($mid) && $adminOk) return true;
        if($this->isUserAdmin) return true;
		//-----------------------------------------------------------
        $fullPermName = $this->module->getVar('dirname') . '_' . $permName; 
        $my_group_ids = $this->getGroupIds($adminOk);

        return $this->grouppermHandler->checkRight($fullPermName, $itemId, $my_group_ids, $mid, false);
//         if ($this->grouppermHandler->checkRight($fullPermName, $itemId, $my_group_ids, $mid, false)) {
// 			return true;
// 		}
//  
// 		return false;
	}

	function getIdsPermissions($permName, $adminOk=false)
	{
		global $xoopsUser, $xoopsModule;
		$fullPermName = $this->module->getVar('dirname') . '_' . $permName; 
        $idsArr = null;
        
        $my_group_ids = $this->getGroupIds($adminOk);

		$this->grouppermHandler = xoops_getHandler('groupperm');
		$mid = $this->module->mid();
		$memberHandler = xoops_getHandler('member');
        
        $idsArr =  $this->grouppermHandler->getItemIds($fullPermName, $my_group_ids, $mid);
		return $idsArr;
	}
    
	/**
	 * @public function getWhereIdsPermissions
	 * returns right for global approve
	 *
	 * @param $permName string nom des permissions sans le nom du module
	 * @param $fldId nom du champ qui contient l'id qui correspopnd a celui des permission (gperm_id)
	 * @return string condition pour la clause Where
	 */
	function getWhereIdsPermissions($permName, $fldId){
        $arr = getIdsPermissions($permName);
        if ($arr){
          $ids = implode(',', $arr);
          $where = "{$fldId} IN ($ids)";
          return $where;
          }
    }
    
    
    
	/**
	 * @public function addPermissions
	 * returns right for global approve
	 *
	 * @param &$criteria Criteria passé en reference object criteria deja instancie ou non
	 * @param $permName string nom des permissions sans le nom du module
	 * @param $fldId nom du champ qui contient l'id qui correspopnd a celui des permission (gperm_id)
	 * @return bool
	 */
	function addPermissions(&$criteria,$permName, $fldId){
    //echo "<hr>addPermissions :$permName = {$permName}<hr>";
        $arr = $this->getIdsPermissions($permName);
        if(!$criteria) $criteria = new CriteriaCompo();
        if ($arr){
            $ids = implode(',', $arr);
          $criteria->add(new Criteria($fldId, '(' . $ids . ')', 'IN'));
          //echo "<hr>addPermissions => " . $criteria->renderWhere(). "<hr>";
          return true;
        }else{
          $criteria->add(new Criteria($fldId, -1, '='));
          return false;
        }
        
    }
    
    
    
	/**
	 * @public function getPermForm
	 * returns form for perm
	 *
	 * @param $formTitle Titre du formulaire
	 * @param $permName  Nom des permissions sans le nom du module
	 * @param $permDesc  Description des permissions
	 * @param $permArr   Tableau itemId/name des permission
	 * @return XoopsGroupPermForm
	 */
     
	function getPermissionsForm($formTitle, $permName, $permDesc, $permArr, $op='')
	{
    global $xoopsModule;
		$fullPermName = $this->module->getVar('dirname') . '_' . $permName;
		//$handler = $quizmakerHelper->getHandler('quiz');
        $moduleId = $this->module->getVar('mid');
        $permform = new \XoopsGroupPermForm($formTitle, $moduleId, $fullPermName, $permDesc, "admin/permissions.php?op={$op}");
    	foreach($permArr as $permId => $name) {
    		$permform->addItem($permId, $name);
    	}


        return $permform;
	}

	/**
	 * @public function getPermForm
	 * returns form for perm
	 *
	 * @param $formTitle Titre du formulaire
	 * @param $permName  Nom des permissions sans le nom du module
	 * @param $permDesc  Description des permissions
	 * @param $permArr   Tableau itemId/name des permission
	 * @return XoopsGroupPermForm
	 */
	function getCheckboxByGroup($label, $name, $itemId, $permName, $isNew)
	{
        $fullPermName = $this->module->getVar('dirname') . '_' . $permName;
		
        if ($isNew) { ;
			//$inpGroups = new \XoopsFormCheckBox( $label, $name, array_keys($this->groupList));
            //par default on ne met que les admin pour une nouvelle categorie
			$groupsIds = [1];
        }else{
			$idsArr = $this->grouppermHandler->getGroupIds($fullPermName, $itemId, $GLOBALS['xoopsModule']->getVar('mid'));
			$groupsIds = array_values($idsArr);
        }
		$inpGroups = new \XoopsFormCheckBox($label , $name, $groupsIds);
        $inpGroups->addOptionArray($this->groupList);
        return $inpGroups;
	}
	function getCheckboxByGroup2($label, $permName, $itemId, $isNew)
	{
        return $this->getCheckboxByGroup($label, $permName, $itemId, $permName, $isNew);    
	}


/* ************************
* 
* 
* ************************* */
	function savePermission($permName, $permId, $groupArr)
	{
        $fullPermName = $this->module->getVar('dirname') . '_' . $permName;
        $mid = $this->module->getVar('mid') ;
        // delete permissions before add new
        $this->grouppermHandler->deleteByModule($mid, $fullPermName, $permId);
        
		if (isset($groupArr)) {
			foreach($groupArr as $onegroupId) {
				$this->grouppermHandler->addRight($fullPermName , $permId, $onegroupId, $mid);
			}
		}
    }
/* ************************
* la même chose que savePermissions sans le probleme du:
* Database updates are not allowed during processing of a GET request
* ************************* */
	function addRight($permName, $permId, $groupArr = null)
	{global $xoopsDB;
        if (!$groupArr) $groupArr = $this->getGroupIds();
        
        $table = $xoopsDB->prefix('group_permission');
        $fullPermName = $this->module->getVar('dirname') . '_' . $permName;
        
        $this->clearAllRight($permName, $permId);
//         $sql = "DELETE FROM {$table} WHERE gperm_modid={$mid}"
//              . " AND gperm_name='{$fullPermName}'"
//              . " AND gperm_itemid={$permId};";
//              
//         $xoopsDB->queryF($sql);
        
		if (isset($groupArr)) {
			foreach($groupArr as $onegroupId) {
                $sql = "INSERT INTO {$table} (gperm_groupid, gperm_itemid, gperm_modid, gperm_name) "
                     . " VALUES ({$onegroupId},{$permId},{$this->mid},'{$fullPermName}')";
                $xoopsDB->queryF($sql);
			}
		}
    }
    
/* ************************
* function clearAllRight : supprime toutes les permission d'un module
* @$permId int : 0 : supprime toutes les permission du module : 
*                1 : supprime toutes le permission du module pour itemId          
* ************************* */
	function clearAllRight($permName = '', $permId = 0)
	{global $xoopsDB;
        $table = $xoopsDB->prefix('group_permission');
        
        $sql = "DELETE FROM {$table} WHERE gperm_modid={$this->mid}";
        if ($permId > 0)    $sql .= " AND gperm_itemid={$permId}";
        if ($permName != '') $sql .= " AND gperm_name='" . $this->module->getVar('dirname') . "_{$permName}'";
        $xoopsDB->queryF($sql . ';');
    
    }


 }  // ----- Fin de la classe -----
 
?>
