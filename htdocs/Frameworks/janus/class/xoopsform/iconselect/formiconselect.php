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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         kernel
 * @subpackage      form
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id: formselectmatchoption.php 8066 2011-11-06 05:09:33Z beckmi $
 */

//defined('XOOPS_ROOT_PATH') or die('Restricted access');



/**
 * A selection box with options for matching files.
 * obsolete because coquille dans le nom icon en english
 */
class XoopsFormIconeSelect extends XoopsFormIconSelect
{}

/**
 * A selection box with options for matching files.
 */
class XoopsFormIconSelect extends XoopsFormElement
{
var $_selectedIconWidth     = 48;
var $_selectedIconHeight    = 48;
var $_selectedBoxPadding    =  5;
var $_iconsWidth            = 48;
var $_iconsHeight           = 48;
var $_boxIconSpace          =  3;
var $_vectoralIconNumber    =  0;
var $_horizontalIconNumber  =  0;
var $_value  =  1;

var $_fldImg  =  null;
var $_imgArr = array();
  
    /**
     * Constructor
     *
     * @param string $caption
     * @param string $name
     * @param mixed $value Pre-selected value (or array of them).
     * 							Legal values are {@link XOOPS_MATCH_START}, {@link XOOPS_MATCH_END},
     * 							{@link XOOPS_MATCH_EQUAL}, and {@link XOOPS_MATCH_CONTAIN}
     * @param int $size Number of rows. "1" makes a drop-down-list
                 iconSelect = new IconSelect("my-icon-select", 
     */
    function __construct($caption, $name, $value = null, $fldImg = null)
    {
        //parent::__construct($caption, $name, $value);
        $this->setCaption($caption);
        $this->setName($name);
        $this->setValue($value);
        $this->setFldImg($fldImg);
        $this->addImagesFromFolder($fldImg);
         
    }
    /**
     * Get the initial value
     *
     * @param  bool $encode To sanitizer the text?
     * @return string
     */
    public function getValue($encode = false)
    {
        return $encode ? htmlspecialchars($this->_value, ENT_QUOTES) : $this->_value;
    }

    /**
     * Set the initial value
     *
     * @param $value
     *
     * @return string
     */
    public function setValue($value)
    {
        $this->_value = $value;
    }
    
//------------------------------------------------    
    /**
     * Get the initial fldImg
     *
     * @param  void
     * @return string
     */
    public function getFldImg()
    {
        return $this->_fldImg;
    }

    /**
     * Set the initial fldImg
     *
     * @param string $fldImg
     *
     * @return string
     */
    public function setFldImg($fldImg)
    {
        $this->_fldImg = $fldImg;
    }
    
//------------------------------------------------    
function addImages($imgPath){
    $this->_imgArr[] = $imgPath;
}
//------------------------------------------------    
function addImagesFromFolder($imgPath){
$url = str_replace(XOOPS_ROOT_PATH, XOOPS_URL, $imgPath);
    $imgList = XoopsLists::getFileListByExtension($imgPath,  array('jpg','png','gif'), '');
    foreach($imgList as $k => $img)
        $this->_imgArr[$img] = $url . '/' . $img;
}
//------------------------------------------------    
function getSelectedIconWidth (){
    return $this->_selectedIconWidth;
}    

//------------------------------------------------    
function setSelectedIconSize ($newWidth, $newHeight){
    $this->_selectedIconWidth =  $newWidth;
    $this->_selectedIconHeight = $newHeight;
}    
//------------------------------------------------    
function setSelectedIconWidth ($newValue){
    $this->_selectedIconWidth = $newValue;
}    
//------------------------------------------------    
function getSelectedIconHeight (){
    return $this->_selectedIconHeight;
}    
function setSelectedIconHeight ($newValue){
    $this->_selectedIconHeight = $newValue;
}    
//------------------------------------------------    
function getSelectedBoxPadding (){
    return $this->_selectedBoxPadding;
}    
function setSelectedBoxPadding ($newValue){
    $this->_selectedBoxPadding = $newValue;
}    

//------------------------------------------------    
function setIconSize ($newWidth, $newHeight){
    $this->_iconsWidth =  $newWidth;
    $this->_iconsHeight = $newHeight;
}    
function getIconsWidth (){
    return $this->_iconsWidth;
}    
function setIconsWidth ($newValue){
    $this->_iconsWidth = $newValue;
}    
//------------------------------------------------    
function getIconsHeight (){
    return $this->_iconsHeight;
}    
function setIconsHeight ($newValue){
    $this->_iconsHeight = $newValue;
}    
//------------------------------------------------    
function getBoxIconSpace (){
    return $this->_boxIconSpace;
}    
function setBoxIconSpace ($newValue){
    $this->_boxIconSpace = $newValue;
}    
//------------------------------------------------    
function getVectoralIconNumber (){
    return $this->_vectoralIconNumber;
}    
function setVectoralIconNumber ($newValue){
    $this->_vectoralIconNumber = $newValue;
}    
//------------------------------------------------    
function getHorizontalIconNumber (){
    return $this->_horizontalIconNumber;
}    
function setHorizontalIconNumber ($newValue){
    $this->_horizontalIconNumber = $newValue;
}    
    
//------------------------------------------------    
function setGridIconNumber ($newHorizontalIcons, $newVectoralIcons = 0){
    $this->_horizontalIconNumber = $newHorizontalIcons;
    $this->_vectoralIconNumber   =  $newVectoralIcons;
}    
//------------------------------------------------    
    
function render(){
//echo "<hr>icnSelect<hr>";
    global $xoTheme;
    $html = array();
    //$html[] = "<hr>icnSelect<hr>";
    $url = JANUS_URL_XFORM . "/iconselect/lib";
    $xoTheme->addStylesheet("{$url}/iconselect.css");
    $xoTheme->addScript("{$url}/iconselect.js");
    $xoTheme->addScript("{$url}/iscroll.js");

//----------------------------------------

  //echo "{$this->imgPath}<br>";
    $imgs = array();
    $indexImg = 0;
    foreach ($this->_imgArr as $k => $img){
      $h = strrpos($k, ".");
      $indexKey = substr($k,0 , $h);
      $imgs[] = "icons.push({'iconFilePath':'{$img}', 'iconValue':'{$indexKey}'});";  
      if ($indexKey == $this->getValue()) $indexImg = count($imgs)-1;
      //echo "{$img}<br>";
    }
    //echo "<hr>index : {$indexImg} - {$this->getValue()}<hr>";

    //par defaut le nombre d'icones en largeur egal le nombre de fichier trouves
    if($this->_horizontalIconNumber == 0 ) $this->_horizontalIconNumber = count($imgs);
    if($this->_vectoralIconNumber == 0 ) { 
        $this->_vectoralIconNumber = intval(count($this->_imgArr) / $this->_horizontalIconNumber);
        $maxRow = 3;
        if ($this->_vectoralIconNumber > $maxRow) $this->_vectoralIconNumber = $maxRow;
    }

    $imgList = implode("\n", $imgs);
    $extra = $this->getExtra() ;

//-------------------------------------------------------
$html[] = <<<__script02__
\n<script type="text/javascript">


window.addEventListener("load",function(){
var iconSelect;


    iconSelect = new IconSelect("{$this->getName()}-contenair", "{$this->getName()}",  
        {'selectedIconWidth':{$this->_selectedIconWidth},
        'selectedIconHeight':{$this->_selectedIconHeight},
        'selectedBoxPadding':{$this->_selectedBoxPadding},
        'iconsWidth':{$this->_iconsWidth},
        'iconsHeight':{$this->_iconsHeight},
        'boxIconSpace':{$this->_boxIconSpace},
        'vectoralIconNumber':{$this->_vectoralIconNumber},
        'horizontalIconNumber':{$this->_horizontalIconNumber},
        'iconFilePath':"{$url}/arrow.png",
        'indexImg':{$indexImg}
        });

var icons = [];
{$imgList}            
iconSelect.refresh(icons);


},false);


    


</script>
<input type="hidden" name="{$this->getName()}" id="{$this->getName()}" value="{$this->getValue()}" />
<div id="{$this->getName()}-contenair" {$extra}></div>
__script02__;
//----------------------------------------

return implode("\n", $html);
}    
    

    
    
    
    



///////////////////////////////////////////////
} // fin de la classe 
///////////////////////////////////////////////



?>
