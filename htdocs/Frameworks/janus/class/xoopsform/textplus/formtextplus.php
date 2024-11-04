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
class XoopsFormTextPlus extends XoopsFormText
{
var $addBtnClear = '';
var $btnArr = [];
var $optionsArr = [];
var $help = '';

    function addBtnClear($caption){
        $this->addBtnClear = $caption;
    }
    
    function addBtn($caption, $value){
        $this->btnArr[$caption] = $value;
    }
    function addOption($value){
        $this->optionsArr[] = $value;
    }
    function addOptionArr($arr){
        foreach ($arr as $key=>$value){
            $this->addOption($value);
        }
    }
    function addList($list, $sep=','){
        $arr = explode($sep, $list);
        foreach ($arr as $key=>$value){
            $this->addOption($value);
        }
    }
    
    function setHelp($value){
        $this->help = $value;
    }
    
    
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
        $xoTheme->addScript($fld . '/formtextplus.js');
        $xoTheme->addStylesheet($fld . '/formtextplus.css');

        $html[] = XoopsFormRenderer::getInstance()->get()->renderFormText($this);
        if($this->addBtnClear){
            $html[] = "<input type = button value='x' onclick='XoopsFormTextPlus_clear(event,\"{$this->getName()}\")'>";   

        }
        foreach ($this->btnArr as $key=>$value){
            $html[] = "<input type = button value='{$key}' onclick='XoopsFormTextPlus_setValue(event,\"{$this->getName()}\", \"{$value}\")'>";   
        
        }
        if(count($this->optionsArr) > 0){
            $html[] = "<select name='{$this->getName()}-select' id='{$this->getName()}-select' onchange='XoopsFormTextPlus_setValueTxt(event,\"{$this->getName()}\")'>";
            $value = '';
            $html[] = "<option value='{$value}'>{$value}</option>";   
            foreach ($this->optionsArr as $key=>$value){
                $html[] = "<option value='{$value}'>{$value}</option>";   
            
            }
            
            $html[] = "</select>";
        }

        
        if($this->help){
            $html[] = "<br><span class = 'XoopsFormTextPlus_help'>{$this->help}</span>";
        }
        
        return implode (' ',$html);

    }
}



