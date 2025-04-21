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
 * @version         $Id: formtext.php 8066 2011-11-06 05:09:33Z beckmi $
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * A simple text field
 */
class XoopsFormShowHide extends XoopsFormElement
{
    /**
     * Maximum length of the text
     *
     * @var int
     * @access private
     */
//var $value='';
var $_libelle;
var $_backcolor = "#CCFFFF";
var $_libClose = "";
var $_libArr=['caption' => '', 'show' => 'show', 'heide'=>'Hide', 'close' => 'close'];
var $_isOpen = false;
var $_value = null;

    /**
     * Constructor
     *
     * @param string $caption Caption
     * @param int $col "cols" nombre de colonne de la table
     * @param int $row nombre de ligne de la table
     * @param string width largeur de la table
     */
    function __construct($caption, $name , $value, $isOpen = false)
    { 
        $this->setName($name);
        $this->setCaption($caption);
        $this->setValue($value);
        $this->setIsOpen($isOpen);
    }

    /*********************************************
     * Get the initial value
     * @param  bool $encode To sanitizer the text?
     * @return string
     */
    public function getValue($encode = false)
    {
        return $encode ? htmlspecialchars($this->_value, ENT_QUOTES) : $this->_value;
    }

    /**
     * Set the initial value
     * @param $value
     * @return string
     */
    public function setValue($value)
    {
        $this->_value = $value;
    }
    
    /*********************************************
     * Get the initial _backcolor
     * @param  bool $encode To sanitizer the text?
     * @return string
     */
    public function getBackcolor()
    {
        return $this->_backcolor;
    }

    /**
     * Set the initial _backcolor
     * @param $value
     * @return string
     */
    public function setBackcolor($value)
    {
        $this->_backcolor = $value;
    }
    
    /*********************************************
     * Get the initial libelle
     * @param  bool $encode To sanitizer the text?
     * @return string
     */
    public function getLibelle($key, $encode = false)
    {
        return $encode ? htmlspecialchars($this->_libArr[$key], ENT_QUOTES) : $this->_libArr[$key];
    }

    /**
     * Set the initial libelle
     * @param $value
     * @return string
     */
    public function setLibelle($key, $value)
    {
        $this->_libArr[$key] = $value;
    }
    /*********************************************
     * Get the initial _isOpen
     * @param  bool $encode To sanitizer the text?
     * @return string
     */
    public function getIsOpen()
    {
        return $this->_isOpen;
    }

    /**
     * Set the initial _isOpen
     * @param $value
     * @return string
     */
    public function setIsOpen($value)
    {
        $this->_isOpen = $value;
    }
        
    /**************************************************
     * Prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
	{
        $janusPathIco32 = JANUS_ICO32;

        global $xoTheme;
//         echo "<hr>" . XOOPS_URL . '/Frameworks/jquery/plugins/showHide.js' . "<hr>";
// 	   $xoTheme->addScript(XOOPS_URL . '/Frameworks/jquery/plugins/showHide.js');
        
        $fld = JANUS_URL_XFORM . "/" . basename(dirname(__FILE__));
	   $xoTheme->addScript(JANUS_URL . '/jquery/plugins/showHide.js');
        //echo "<hr>" . $fld . "<hr>";
        $xoTheme->addStylesheet($fld . '/formshowhide.css');
        //$xoTheme->addScript($fld . '/formshowhide.js');
        //------------------------------------
        $tHtml = array();
        $libelle = ($this->getLibelle('caption')) ? $this->getLibelle('caption', true) . ' : ' : '';
        $tHtml[] = <<<__showHide__
             {$libelle}<b><a href="#" name="{$this->getName()}" id="{$this->getName()}" class="show_hide" rel="#{$this->getName()}-content">{$this->getLibelle('show', true)}
               <img src="{$janusPathIco32}/plus.png" width="16px" height="16px" alt="" />
             </a></b><br>
             <div id="{$this->getName()}-content" name="{$this->getName()}-content" class="show_hide_div" style="background:{$this->getBackcolor()}">
            __showHide__;
        
        if ( is_string($this->getValue())){
            $tHtml[] = $this->getValue();
        }else{
            $tHtml[] = $this->getValue()->render();
        }
        if($this->getLibelle('close')){
            $tHtml[] = "<br><center><input type='button' onclick='document.getElementById(\"{$this->getName()}\").click();' value='{$this->getLibelle('close', true)}'></center>";
        }
        $tHtml[] = "</div>";
        $isOpen = ($this->getIsOpen()) ? 'true' : 'false';
// ================== ajout du JS =========================        
$tHtml[] = <<<__script02__
\n<script type="text/javascript">

$(document).ready(function(){
//alert("show_hide");
   $('.show_hide').showHide({
		speed: 500,  // speed you want the toggle to happen
		easing: '',  // the animation effect you want. Remove this line if you dont want an effect and if you haven't included jQuery UI
		changeText: 1, // if you dont want the button text to change, set this to 0
		showHideText: 1,

		showText: '{$this->getLibelle("show", true)}   <img src="{$janusPathIco32}/plus.png"   width="16px" height="16px" alt="" />',// the button text to show when a div is closed
		hideText: '{$this->getLibelle("hide", true)} <img src="{$janusPathIco32}/moins.png"  width="16px" height="16px" alt="" />' // the button text to show when a div is open
	});

}); 

if({$isOpen}){
    setTimeout(formShowHide_openDiv,500);
}
function formShowHide_openDiv(){
    document.getElementById("{$this->getName()}").click();
}
//alert("ici");
</script>
__script02__;        
// ================== /ajout du JS =========================        
        
        return implode("\n", $tHtml);
    }

} // fin de la classe

?>
