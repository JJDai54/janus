<?php
/**
 * XoopsSpinMap
 *  
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code 
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         kernel
 * @subpackage      form
 * @since           2.0.0
 * @author          Jean-Jacques DELALANDRE <jjdelalandre@orange.fr>
 * @version         v 1.2
 */
 
defined('XOOPS_ROOT_PATH') or die('Restricted access');

xoops_load('XoopsFormSelect');

/**
 * A select field
 *
 * @author 		Jean-Jacques DELALANDRE <jjdelalandre@orange.fr>
 * @copyright JJD https:xoops.janus.fr
 * @access 		public
 */




/*----------------------------------------------------------*/

class XoopsEditList extends XoopsFormSelect
{
/*----------------------------------------------------------*/
/* set here the folder of the clas relative at the root     */
/*----------------------------------------------------------*/
//const _EDITLIST_FOLDER = '/class/xoopsform/editlist/editlist';
const _EDITLIST_VERSION = 1.2;

    /**
     * $_btnArr array of buttons
     *
     * @var array
     * @access private
     */
var $_btnArr = [];

    /**
     * backcolor ol list
     *
     * @var string
     * @access private
     */
    var $_background = '#FFE598';

    /**
     * backcolor ol list
     *
     * @var string
     * @access private
     */
    var $_height = 100;
    
    /**
     * width
     *
     * @var long
     * @access private
     */
    var $_width = 0;
    /**********************************************************************/
    /*-----------------------------------------------------------------*/
    /**
     * Get the values
     */
    function getBackground()
    {
        return $this->_background;
    }

    /**
     * Set the value
     *
     * @param  $value int
     */
     
    function setBackground($background)
    {
       $this->_background = $background;
    }
    /*-----------------------------------------------------------------*/
    /**
     * Get the values
     */
    function getHeight()
    {
        return $this->_height;
    }

    /**
     * Set the value
     *
     * @param  $value int
     */
     
    function setHeight($height)
    {
       $this->_height = $height;
    }
    /*-----------------------------------------------------------------*/
    /**
     * Get the values
     */
    function getWidth()
    {
        return $this->_width;
    }

    /**
     * Set the value
     *
     * @param  $width int
     */

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
        $this->_btnArr[] = array('libelle'=>$libelle,'name'=>$name,'type'=>$inputType,'action'=>$action);
	}
     
    function setWidth($width)
    {
       $this->_width = $width;
    }
    /**********************************************************************/
    
    /**
     * Prepare HTML for output
     *
     * @return string HTML
     */
    function render_list()
    {
    global $xoTheme;
    // $url =  XOOPS_URL . self::_EDITLIST_FOLDER;
    $path = str_replace('\\','/',dirname(__file__));
    $url = str_replace(XOOPS_ROOT_PATH, XOOPS_URL, $path) . '/editlist';
   // echo $path.'<br>'.$url.'<hr>'.XOOPS_ROOT_PATH;
//const _EDITLIST_FOLDER = '/class/xoopsform/editlist/editlist';

    
     $tHtml = array();
     $values = $this->getValue();
     $value  = $values[0];     
     $name = $this->getName();
    
     //$tHtml[] = "<div>"; //  style='position:absolute'
     $xoTheme->addStylesheet($url . '/editlist.css');
     $xoTheme->addScript($url . '/editlist.js');

     $tOptions = $this->getOptions();
     $options = implode (';', $tOptions);
     $style = ($this->_width == 0) ?''
               :"STYLE='width:{$this->_width}px;'";
     $tHtml[] = "<input type='text' name='{$name}' value='{$value}' selectBoxOptions='{$options}' idOption='0'  {$style}"
              . "size='" . $this->getSize() ."' " 
              . $this->getExtra() 
              . " onClick=\"selectBox_select_all('{$name}');\" >";

     $tHtml[] = "<script type='text/javascript'>";
     $tHtml[] = "var editListUrl = '{$url}/';";
     $tHtml[] = "params=new Array();";
     $tHtml[] = "params['bgColor']='{$this->_background}';";
     $tHtml[] = "params['height']='{$this->_height}';";
     $tHtml[] = "createEditableSelectByName('{$name}',params);";
     $tHtml[] = "</script>";

     //$tHtml[] = "</div>";
    
   //--------------------------------------------------------  
   $html =  implode("\n", $tHtml);
   //-------------------------------------------
    return $html;
    }
    
    /**********************************************************************/
    
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
            $html = '<div style=\'white-space: nowrap;\'>' . $this->render_list();
            foreach($this->_btnArr AS $key=>$btn){
                $html .= "<input type='{$btn['type']}' id='{$btn['name']}'  name='{$btn['name']}'value='{$btn['libelle']}' class='xform' onclick='{$btn['action']}'>";
            }
            $html .= '</div>';
            return $html;
        }else{
            return $this->render_list();
        }
        
    }


/*-----------------------------------------------*/
/*---          fin de la classe               ---*/
/*-----------------------------------------------*/


}

?>
