<?php
/**
 * XOOPS form checkbox compo
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
 * @since               2.0
 * @author              Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @author              Skalpa Keo <skalpa@xoops.org>
 * @author              Taiwen Jiang <phppp@users.sourceforge.net>
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

xoops_load('XoopsFormElement');

/**
 * Class XoopsFormCheckBox
 */
class XoopsFormCheckBoxArr extends XoopsFormElement
{
    /**
     * Availlable options
     *
     * @var array
     * @access private
     */
    public $_xFormArr = array();

    /**
     * Constructor
     *
     * @param string $caption
     * @param string $name
     * @param mixed  $value Either one value as a string or an array of them.
     * @param string $delimeter
     */
    public function __construct($caption)
    {
        $this->setCaption($caption);
        $this->setFormType('checkboxarr');
    }

    /**
     * 
     */
    public function addElement($xfCheckBox)
    {
    //echo "{$xfCheckBox->_caption}<br>{$xfCheckBox->_name}<br>{$xfCheckBox->_value}<br>{$xfCheckBox->_delimeter}<hr>";
        $newXF = new \XoopsFormCheckBoxLocal($xfCheckBox->getCaption(),$xfCheckBox->getName(),$xfCheckBox->getValue(),$xfCheckBox->_delimeter);
        $newXF->_options = $xfCheckBox->_options;
        $newXF->_columns = $xfCheckBox->_columns;
        //$newXF = new XoopsFormCheckBoxLocal($xfCheckBox->_caption,$xfCheckBox->_name,$xfCheckBox->_value,$xfCheckBox->_delimeter);
        
        $this->_xFormArr[] = $newXF;
        //$this->_xFormArr[] = $xfCheckBox;
    }

    /**
     * 
     */
    public function addElement2($xfCheckBox)
    {
//        $newOptions = array();
//         foreach ($newXF->_options as $val => $name) {
//             $newOptions[] = $val;
//         }
//         $xfCheckBox->_options = $newOptions;
        $this->_xFormArr[] = $xfCheckBox;
    }

    /**
     * 
    public function addXoopsFormCheckBox($caption, $name, $value = null)
    {
        $newXF = new xoopsFormCheckBox('', $name, $value = null, '');
        echoArray();
        $newOptions = array();
        foreach ($newXF->_options as $val => $name) {
            $newOptions[] = $name;
        }
        $newXF->_options = $newOptions;
        $this->_xFormArr[] = $newXF;
        
    }
     */
    
    
    /**
     * 
     */
    function render2(){
        //$style="text-orientation: sideways;  transform: rotate(180deg);  white-space: nowrap;  writing-mode: vertical-rl;";
        $style="text-orientation: upright;  transform: rotate(180deg);  white-space: nowrap;  writing-mode: vertical-rl;";
        
        $tHtml = array();
        $tHtml[] = "<table class='formCheckboxArr'>";
    
        $h = 0;
        
        foreach($this->_xFormArr as $key=>$xForm){
            if ($key == 0) {
                $tHtml[] = $xForm->renderTitles();
            };
            
            $tHtml[] = $xForm->render($h++ % 2);
        }
        
        $tHtml[] = "</table>";
        return implode('', $tHtml);
    }

    function render(){
        //$style="text-orientation: sideways;  transform: rotate(180deg);  white-space: nowrap;  writing-mode: vertical-rl;";
        $style="text-orientation: upright;  transform: rotate(180deg);  white-space: nowrap;  writing-mode: vertical-rl;";
        
        $tHtml = array();
        $tHtml[] = "<div class='formCheckboxArr'>";
        $tHtml[] = "<table>";
    
        $h = 0;
        
        foreach($this->_xFormArr as $key=>$xForm){
            if ($key == 0) {
                $tHtml[] = $xForm->renderTitles();
            };
            
            $tHtml[] = $xForm->render($h++ % 2);
        }
        
        $tHtml[] = "</table></div>";
        return implode('', $tHtml);
    }
}

//////////////////////////////////////////////////////////////
/**
 * Class XoopsFormCheckBox
 */
class XoopsFormCheckBoxLocal extends XoopsFormCheckBox
{
//public $_maxWidth = 30;

    public function renderTitles()
    {$element = $this;
    
    
    
    
    
        $ele_name      = $element->getName();
        $ele_title     = $element->getTitle();
        $ele_id        = $ele_name;
        $ele_value     = $element->getValue();
        $ele_options   = $element->getOptions();
        $ele_extra     = $element->getExtra();
        $ele_delimiter = empty($element->columns) ? $element->getDelimeter() : '';


        //$style="text-orientation: sideways;  transform: rotate(180deg);display: flex;align-items: flex-end;  white-space: nowrap;  writing-mode: vertical-rl;";
//$style="text-orientation: sideways;height: 150px;display: flex;align-items: flex-end;justify-content: center;padding-bottom: 10px; ";
 /* Définissez une hauteur fixe pour laisser de la place au texte */
  
   /* Aligne le contenu vers le bas */
   /* Centre horizontalement si besoin */
         
        //$style="text-orientation: mixed;  transform: rotate(180deg);  white-space: nowrap;  writing-mode: vertical-rl;";

        $ret = '';
            $ret .= '<tr><th even style="width:25%;"></th>';
        


        $id_ele = 0;
        foreach ($ele_options as $value => $name) {
            $ret .= "<th even><span>" . $name . "</span></th>";
        }

        $ret .= '</tr>';

        return $ret;
    }
    
    
    public function render($p = 0)
    {$element = $this;
        $ele_name      = $element->getName();
        $ele_title     = $element->getTitle();
        $ele_id        = $ele_name;
        $ele_value     = $element->getValue();
        $ele_options   = $element->getOptions();
        $ele_extra     = $element->getExtra();
        $ele_delimiter = empty($element->columns) ? $element->getDelimeter() : '';

        if (count($ele_options) > 1 && substr($ele_name, -2, 2) !== '[]') {
            $ele_name .= '[]';
            $element->setName($ele_name);
        }
        $ret = '';
        
        /*<label class="checkbox-inline">
          <input type="checkbox" id="inlineCheckbox1" value="option1"> 1
        </label>*/
        $att = ($p == 0) ? 'odd' : 'even';
        $ret .= "<tr  {$att}><td  libelle>{$this->getCaption()}</td>";
        $i      = 0;
        $id_ele = 0;
        foreach ($ele_options as $value => $name) {
            ++$id_ele;

                $ret .= "<td  checkbox>";            
            
            
            
            // $name may be a link, should we use $name in the title tag?
            $ret .= '<input type="checkbox" name="' . $ele_name . '" id="' . $ele_id .$id_ele . '" '
                . ' title="' . $ele_title . '" value="' . htmlspecialchars($value, ENT_QUOTES) . '"';

            if (count($ele_value) > 0 && in_array($value, $ele_value)) {
                $ret .= ' checked';
            }
            $ret .= $ele_extra . ' />';
            $ret .= '</td>';
        }
        $ret .= '</tr>';

        
        return $ret;
    }

    function render_test(){
        //echo "{$this->_caption}<br>{$this->_name}<br>{$this->_value}<br>{$this->_delimeter}<hr>";
        return parent::render();
    }
}
