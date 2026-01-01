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
 * @license             GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package             kernel
 * @subpackage          form
 * @since               2.0.0
 * @author              JJDai, http://jubile.fr
 */

defined('XOOPS_ROOT_PATH') || exit('Restricted access');
if (JANUS_DEBUG) echo "<hr>========= " . __FILE__. " =================<hr>";

/**
 * A simple text field
 */

//impossible d'utiliser xoopsFormHidden a cause des doubles quote de json
class XoopsFormPalette extends XoopsFormElement
{

    /**
     * Initial text
     *
     * @var string
     * @access private
     */
    public $_value;


    /**
     * Initial array of attribut
     * each attribut is an array with his params
     * [height]
     *      [value]
     *      [min]
     *      [max]
     * [width]
     *      [value]
     *      [min]
     *      [max]
     *  [color]
     *      [value]
     *
     * @var string
     * @access private
     */
    public $_palette = '$classic';
    public $_width = 350;
    public $_height = 350;
    public $_userPalette = '';
    public $_nbColonnes = 8;
    public $_divColorSize = 16;   
    /**
     * Constructor
     *
     * @param string $caption   Caption
     * @param string $name      "name" attribute
     * @param string $value     Initial text
     * 
     * $exemple with string : $value = "height:12px;width:15px;color:#FFFF00"
     * $exemple with array : $value = ['height' => '12px', 'width' => '15px', 'color' => '#FFFF00'] 
     */

    public function __construct($caption, $name, $value = '', $palette = '$classic', $width = 80, $height = 32)
    {

        $this->setCaption($caption);
        $this->setName($name);
        $this->setValue($value);
        $this->setPalette($palette);
        $this->_width = $width;
        $this->_height = $height;

    }


    /**
     * Get initial content
     *
     * @param  bool $encode To sanitizer the text? Default value should be "true"; however we have to set "false" for backward compatibility
     * @return string
     */
//     public function getValue()
//     {
//         return $this->_value;
//     }

////////////////////////////////
    /**
     * Get initial content
     *
     * @param  bool $encode To sanitizer the text? Default value should be "true"; however we have to set "false" for backward compatibility
     * @return string
     */
    public function getValue($encode = false)
    {
        return $encode ? htmlspecialchars($this->_value, ENT_QUOTES) : $this->_value;
    }

    /**
     * Set initial text value
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->_value = $value;
    }

    ////////////////////////////////////////////////////////////////
    /**
     * Get initial content
     *
     * @param  bool $encode To sanitizer the text? Default value should be "true"; however we have to set "false" for backward compatibility
     * @return string
     */
    public function getPalette()
    {
        return $this->_palette;
    }

    /**
     * Set initial text value
     *
     * @param string $value
     */
    public function setPalette($value)
    {
        $this->_palette = $value;
    }

    ////////////////////////////////////////////////////////////////
    /**
     * Set initial button caption
     *
     * @param string $value
     */
    public function setWidth($value)
    {
        $this->_width = $value;
    }
    
    /**
     * get initial button caption
     *
     * @param string $value
     */
    public function getWidth()
    {
        return $this->_width;
    }
    
    /**
     * Set initial button caption
     *
     * @param string $value
     */
    public function setHeight($value)
    {
        $this->_height = $value;
    }
    
    /**
     * get initial button caption
     *
     * @param string $value
     */
    public function getHeight()
    {
        return $this->_height;
    }
////////////////////////////////
function setUserPalette($userPalette, $nbColonnes = 8 , $divColorSize = 16)
    {
    $this->_userPalette = $userPalette;
    $this->_nbColonnes = $nbColonnes;
    $this->_divColorSize = $divColorSize;   

    }
////////////////////////////////

    /**
     * Prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
    {   
        $name  = $this->getName();
        $btnName  = $name . '-button';
        $value = $this->getValue();
        
    $style="pading:0px;margin:0px;background:{$value};width:{$this->_width}px;height:{$this->_height}px;";
    $onClick = "palette_showPicker(event, '{$this->getPalette()}')";
    
    $html = "<input type='button' name='{$btnName}' id='{$btnName}'"
             . " value='{$value}' style='{$style}' onclick=\"{$onClick}\" xformId='{$name}'>";

    $html .= "<input type='hidden' name='{$name}' id='{$name}' value='{$value}' >";

    $html .= "<input type='hidden' name='{$name}-userPalette'  id='{$name}-userPalette'  value='{$this->_userPalette}'  disabled >";
    $html .= "<input type='hidden' name='{$name}-nbColonnes'   id='{$name}-nbColonnes'   value='{$this->_nbColonnes}'   disabled >";    
    $html .= "<input type='hidden' name='{$name}-divColorSize' id='{$name}-divColorSize' value='{$this->_divColorSize}' disabled >";    
    
    return $html;
    
    }
    
 } // ============ FIN DE LA CLASSE =================
