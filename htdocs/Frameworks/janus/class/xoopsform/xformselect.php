<?php
/**
 * select form element
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       (c) 2000-2017 XOOPS Project (www.xoops.org)
 * @license             GNU GPL 2 (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package             kernel
 * @subpackage          form
 * @since               2.0.0
 * @author              Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @author              Taiwen Jiang <phppp@users.sourceforge.net>
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');



/**
 * A select field
 *
 * @author              Kazumi Ono <onokazu@xoops.org>
 * @author              Taiwen Jiang <phppp@users.sourceforge.net>
 * @author              John Neill <catzwolf@xoops.org>
 * @copyright       (c) 2000-2016 XOOPS Project (www.xoops.org)
 * @package             kernel
 * @subpackage          form
 * @access              public
 */
class JanusFormSelect extends XoopsFormSelect
{
var $_btnArr = [];
var $_addAll = false;
var $_libAll = '(*)';
var $_idAll = '_*_';


	/**
	 * XoopsFormButtonTray::getAddAll()
	 *
	 * @return
	 */
	function getAddAll() {
		return $this->_addAll;
	}

	/**
	 * XoopsFormButtonTray::setAddAll()
	 *
	 * @param boolean $value
	 * @return
	 */
	function setAddAll($value) {
		$this->_addAll = $value;
	}

	/**
	 * XoopsFormButtonTray::addAll()
	 *
	 * @param boolean $value
	 * @return
	 */
	function addAll($libAll = '', $idAll = '') {
        if ($libAll) $this->_libAll = $libAll;
        if ($idAll)  $this->_idAll  = $idAll;
		$this->_addAll = true;
	}
    
//------------------------------------------------------------------
	/**
	 * XoopsFormButtonTray::setValue()
	 *
	 * @param mixed $value
	 * @return array
	 */
	function getButtons() {
		return $this->_btnArr;
	}

	function addButton($libelle, $name='', $inputType='button',  $action='') {
		//if (!$action) $action = "document.getElementById(\"{$this->getName()}\").value=\"\";'";
		if (!$action && $inputType=='button') $action = "self.value=\"{$this->_idAll}\"";
        if (!$name) $name = $this->getName() . '_' . count($this->_btnArr);
		$action = str_replace('self', "document.getElementById(\"{$this->getName()}\")", $action); 
        $this->_btnArr[] = array('libelle'=>$libelle, 'name'=>$name, 'type'=>$inputType, 'action'=>$action);
	}
    
    /**
     * Prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
    {
        global $xoTheme;
        $xoTheme->addStylesheet(JANUS_URL_XFORM . "/xform.css");
        //$xoTheme->addScript("{$url}/iconselect.js");

//         if ($this->_addAll){
//             $allArr = [$this->_idAll => $this->_libAll];
//             $this->_options = array_merge($allArr,$this->_options);
//         }
//         
        
        //pas d'utilisation de array_merge pour ne pas changer lex clés numériques
        if ($this->_addAll){
            $allArr = [$this->_idAll => $this->_libAll];
            foreach($this->_options AS $key=>$option){
            $allArr[$key] = $option;
            }
            $this->_options = $allArr;
        }
        
        if (count($this->_btnArr) > 0){
            $html = '<div style=\'white-space: nowrap;\'>' . XoopsFormRenderer::getInstance()->get()->renderFormSelect($this);
            foreach($this->_btnArr AS $key=>$btn){
                $html .= "<input type='{$btn['type']}' id='{$btn['name']}'  name='{$btn['name']}' value='{$btn['libelle']}' class='xform' onclick='{$btn['action']}'>";
            }
            $html .= '</div>';
            return $html;
        }else{
            return XoopsFormRenderer::getInstance()->get()->renderFormSelect($this);
        }
        
    }


}
