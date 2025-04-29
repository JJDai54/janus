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
//class XoopsFormCheckBox extends XoopsFormElement
class XoopsFormCheckBoxImage extends XoopsFormCheckBox
{
    /**
     * Availlable options
     *
     * @var array
     * @access private
     */
    public $_skin = 'default';
    public $_height = 30;
    public $_showCaption = true;
    public $_switchImage = false;


    public function __construct($caption, $name, $value = null, $delimeter = '&nbsp;')
    {
        $this->setCaption($caption);
        $this->setName($name);
        if (isset($value)) {
            $this->setValue($value);
        }
        $this->_delimeter = $delimeter;
        $this->setFormType('checkbox');
    }


    /**
     * Get the "skin"
     *
     * @param  bool $encode To sanitizer the text?
     * @return array
     */
    public function getSkin()
    {
        return $this->_skin;
    }

    /**
     * Set the "skin"
     *
     * @param array $value
     *
     */
    public function setSkin($skin)
    {
        //todo : ahiouter un controle du dossier image si il existe
        $this->_skin = $skin;
    }

    /**
     * Get the "height"
     *
     * @param  bool $encode To sanitizer the text?
     * @return array
     */
    public function getHeight()
    {
        return $this->_theight;
    }

    /**
     * Set the "height"
     *
     * @param array $value
     *
     */
    public function setHeight($height)
    {
        //todo : ahiouter un controle de la hauteur
        $this->_height = $height;
    }
    /**
     * Get the "showCaption"
     *
     * @param  bool $encode To sanitizer the text?
     * @return array
     */
    public function getShowCaption()
    {
        return $this->_showCaption;
    }

    /**
     * Set the "showCaption"
     *
     * @param array $value
     *
     */
    public function setShowCaption($showCaption)
    {
        //todo : ahiouter un controle de la hauteur
        $this->_showCaption = $showCaption;
    }
    public function showCaption($showCaption)
    {
        //todo : ahiouter un controle de la hauteur
        $this->_showCaption = $showCaption;
    }
    /**
     * Get the "switchImage"
     *
     * @param  bool $encode To sanitizer the text?
     * @return array
     */
    public function getSwitchImage()
    {
        return $this->_switchImage;
    }

    /**
     * Set the "switchImage"
     *
     * @param array $value
     *
     */
    public function setSwitchImage($switchImage)
    {
        //todo : ahiouter un controle de la hauteur
        $this->_switchImage = $switchImage;
    }
    public function switchImage($switchImage)
    {
        //todo : ahiouter un controle de la hauteur
        $this->_switchImage = $switchImage;
    }
    //------------------------------------------------------------------
    /**
     * prepare HTML for output
     *
     * @return string
     */
    public function render()
    {
        $url = XOOPS_URL . '/Frameworks/janus/class/xoopsform/checkboximage' . "/Skins/" . $this->_skin;
        
        $element = $this;
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
        if (!empty($element->columns)) {
            $ret .= '<table><tr>';
        }
        $i      = 0;
        $id_ele = 0;
        foreach ($ele_options as $value => $name) {
            ++$id_ele;
            
            $id = $ele_id . $id_ele;
            $checked = (count($ele_value) > 0 && in_array($value, $ele_value)) ? ' checked ' : '';
            if($this->_switchImage){
                $currentImg = 'coche-0' . (($checked) ? '0' : '1');
            }else{
                $currentImg = 'coche-0' . (($checked) ? '1' : '0');
            }
            //$imgStyle = 'style="offset-position: 0% 50%;"';
            $imgStyle = 'style="margin:0px;padding:0px;offset-position:0% -20px"';
            $onclick = "formcheckboximage_onclick(event, 'coche-0', {$this->_switchImage});";
            $htmlImg = "<img src='{$url}/{$currentImg}.png' title='' id='chkimg-{$id}' alt='' height='{$this->_height}px' onclick=\"{$onclick}\" {$imgStyle}>";
            $visibility = " style=\"visibility: hidden;\"";            
            

            if (!empty($element->columns)) {
                if ($i % $element->columns == 0) {
                    $ret .= '<tr>';
                }
                $ret .= '<td>';
            }
            // $name may be a link, should we use $name in the title tag?
            $checkbox = '<input type="checkbox" name="' . $ele_name . '" id="' . $ele_id .$id_ele . '" '
                      . ' title="' . $ele_title . '" value="' . htmlspecialchars($value, ENT_QUOTES) . '"'
                      . $ele_extra . $checked . $visibility.' />'; 
            
             $ret .= $checkbox . $htmlImg; 
            
            if ($this->_showCaption){
                $ret .= "<label name='xolb_{$ele_name}' id='chklab-{$id}' for='chkimg-{$id}'"
                     . " onclick=\"{$onclick}\">{$name}</label>";            
            }
            $ret .= $ele_delimiter;            


//             $ret .= $checkbox . $htmlImg .'<label name="xolb_' . $ele_name . '" for="chkimg-'
//                  . $ele_id . $id_ele . '" ' . $onclick . '>' . $name . '</label>' . $ele_delimiter;

            if (!empty($element->columns)) {
                $ret .= '</td>';
                if (++$i % $element->columns == 0) {
                    $ret .= '</tr>';
                }
            }
        }
        if (!empty($element->columns)) {
            if ($span = $i % $element->columns) {
                $ret .= '<td colspan="' . ($element->columns - $span) . '"></td></tr>';
            }
            $ret .= '</table>';
        }

        return $ret;
    }

    /**
     * Render custom javascript validation code
     *
     * @seealso XoopsForm::renderValidationJS
     */
    public function renderValidationJS()
    {
        // render custom validation code if any
        if (!empty($this->customValidationCode)) {
            return implode(NWLINE, $this->customValidationCode);
            // generate validation code if required
        } elseif ($this->isRequired()) {
            $eltname    = $this->getName();
            $eltcaption = $this->getCaption();
            $eltmsg     = empty($eltcaption) ? sprintf(_FORM_ENTER, $eltname) : sprintf(_FORM_ENTER, $eltcaption);
            $eltmsg     = str_replace('"', '\"', stripslashes($eltmsg));

            return NWLINE . "var hasChecked = false; var checkBox = myform.elements['{$eltname}']; if (checkBox.length) {for (var i = 0; i < checkBox.length; i++) {if (checkBox[i].checked == true) {hasChecked = true; break;}}} else {if (checkBox.checked == true) {hasChecked = true;}}if (!hasChecked) {window.alert(\"{$eltmsg}\");if (checkBox.length) {checkBox[0].focus();} else {checkBox.focus();}return false;}";
        }

        return '';
    }
}
