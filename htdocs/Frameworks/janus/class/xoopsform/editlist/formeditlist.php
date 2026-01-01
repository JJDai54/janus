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
var $_addBtnClear = '';
var $_help = '';


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
    var $_height = 22; //hauteur des lignes des optioins
    
    /**
     * width
     *
     * @var long
     * @access private
     */
    var $_width = 0;
    /**********************************************************************/

    /**
     *  addList : ajoute une liste d'items aux options, la cle sera l'index du tableau
     *  
     * @$list string : list d'options à ajouter séparer pa $sep
     * @$sep string : separateur d'exprion de $list
     */
    function addList($list, $sep=','){
        $arr = explode($sep, $list);
        foreach ($arr as $key=>$value){
            $this->addOption($value);
        }
    }
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
     * return string
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
    function setWidth($width)
    {
       $this->_width = $width;
    }
    
    function getHelp(){
        return $this->_help;
    }
    function setHelp($value){
        $this->_help = $value;
    }
    

	function getButtons() {
		return $this->_btnArr;
	}


    function addBtnClear($caption){
        $this->_addBtnClear = $caption;
    }
    
    function addBtn($value){
        $this->_btnArr[] = $value;
    }
    function addBtnArray($btnArr){
        foreach($btnArr as $value)
            $this->_btnArr[] = $value;
    }
    //----------------------------------------
    //pout compatibilite
    function addButtonClear($caption){
        $this->_addBtnClear = $caption;
    }
    function addButton($value){
        $this->_btnArr[] = $value;
    }
    function addButtonArray($btnArr){
        foreach($btnArr as $value)
            $this->_btnArr[] = $value;
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
    $url = str_replace(XOOPS_ROOT_PATH, XOOPS_URL, $path); // . '/editlist';
   // echo $path.'<br>'.$url.'<hr>'.XOOPS_ROOT_PATH;
//const _EDITLIST_FOLDER = '/class/xoopsform/editlist/editlist';

    
     $tHtml = array();
     $values = $this->getValue();
     $value  = $values[0];     
     $name = $this->getName();
    
     //$tHtml[] = "<div>"; //  style='position:absolute'
//      $xoTheme->addStylesheet($url . '/editlist.css');
//      $xoTheme->addScript($url . '/editlist.js');

     $tOptions = $this->getOptions();
     $options = implode (';', $tOptions);



     $styleArr = array();
     if($this->_width != 0) $styleArr[] = "width:{$this->_width}px;";
     //if($this->_height != 0) $styleArr[] = "height:{$this->_height}px;";
     $styleArr[] = "height:10px;";
     $styleArr[] = "margin-top:-2px;";
     if($this->_background != '') $styleArr[] = "background:{$this->_background};";
     $style = (count($styleArr)>0) ? "style='" . implode('', $styleArr) . "'" :  '';
     

     $tHtml[] = "<input type='text' id='{$name}' name='{$name}' value='{$value}' selectBoxOptions='{$options}' idOption='0'  {$style}"
              . "size='" . $this->getSize() ."' " 
              . $this->getExtra() 
              . " onClick=\"selectBox_select_all('{$name}');\" >";

     $tHtml[] = "<script type='text/javascript'>";
     $tHtml[] = "var editListUrl = '{$url}/';";
     $tHtml[] = "params=new Array();";
     $tHtml[] = "params['bgColor']='{$this->_background}';";
     //$tHtml[] = "params['height']='{$this->_height}';";
     $nbMaxOptions = (count($tOptions) > 12) ? 12 : count($tOptions);
     $tHtml[] = "params['height']=" . ($this->_height * $nbMaxOptions);     //'{$this->_height}';";
     $tHtml[] = "createEditableSelectByName('{$name}',params);";
     $tHtml[] = "</script>";

     //$tHtml[] = "</div>";
    
   //--------------------------------------------------------  
   $html =  implode("\n", $tHtml);
   //-------------------------------------------
    return $html;
    }


    /**
     * Prepare HTML for output
     *
     * @return string HTML
     */
    function render_textbox()
    {
        $values = $this->getValue();
        $value  = $values[0];     
        $name = $this->getName();
        $style='';
        $html = "<input type='text' id='{$name}' name='{$name}' value='{$value}' {$style}"
              . "size='" . $this->getSize() ."' " 
              . $this->getExtra() . " >";

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
        $fld = JANUS_URL_XFORM . "/" . basename(dirname(__FILE__));
        //echo "<hr>" . $fld . '/formtextplus.js' . "<hr>";
        $xoTheme->addScript($fld . '/formeditlist.js');
        $xoTheme->addStylesheet($fld . '/formeditlist.css');
        $html = array();
            $html[] = "<table><tr>";        
//$html[] = "<div style='white-space: nowrap;'>";        
        if(count($this->_options) > 0){
            $html[] = "<td style='width:5%'>" . $this->render_list() . "</td>"; 
        }else{
            $html[] = "<td style='width:5%'>" . $this->render_textbox() . "</td>"; 

            
        }
        //---------------------------------------------------
        $html[] = '<td>';        
        $styleBtn = "style='margin-top:8px'";
        if($this->_addBtnClear){
            $html[] = "<input type='button' {$styleBtn} value='X' onclick='XoopsFormeEditList_clear(event,\"{$this->getName()}\")'>";   

        }
        foreach ($this->_btnArr as $key=>$value){
            $html[] = "<input type='button' {$styleBtn} value='{$value}' onclick='XoopsFormeEditList_setValue(event,\"{$this->getName()}\", \"{$value}\")'>";   
        
        }
            $html[] = '</td>';        
            $html[] = '</tr></table>';        

        if($this->_help){
            $html[] = "<span class='XoopsFormEditList_help'>{$this->_help}</span>";
        }
        
            return implode("\n", $html); 
        
    }
/*-----------------------------------------------*/
/*---          fin de la classe               ---*/
/*-----------------------------------------------*/


}

?>
