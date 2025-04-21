<?php
/**
 * XOOPS form element
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
 */

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * A simple text field
 */
class JanusFormText extends XoopsFormText
{
var $_btnArr = [];

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
		if (!$action && $inputType=='button') $action = "self.value=\"\";'";
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

        if (count($this->_btnArr) > 0){
            $html = '<div style=\'white-space: nowrap;\'>' .XoopsFormRenderer::getInstance()->get()->renderFormText($this);
            foreach($this->_btnArr AS $key=>$btn){
                $html .= "<input type='{$btn['type']}' id='{$btn['name']}'  name='{$btn['name']}' value='{$btn['libelle']}' class='xform' onclick='{$btn['action']}'>";
            }
            $html .= '</div>';
            return $html;
        }else{
            return XoopsFormRenderer::getInstance()->get()->renderFormText($this) . $btnInit;
        }
    }
}
