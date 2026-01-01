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
class XoopsFormJson extends XoopsFormTextArea
{
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
    public $_attributs = array();
    public $_captionArr = ['edit' => 'Edit', 'submit' => 'Submit', 'cancel' => 'Cancel'];
    public $_textBoxVisible = false;
    public $_previewVisible = false;
    public $_openAsForm = true;
    public $_width = 350;
    public $isNew = false;
    
    
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
    //public function __construct($caption, $name, $value = '', $rows = 5, $cols = 50)
    public function __construct($caption, $name, $value = '', $openAsForm = false, $size = 650, $maxlength = 1024)
    {
        $rows = 8; $cols = 50;
        $this->_rows = (int)$rows;
        $this->_cols = (int)$cols;

        $this->isNew = (!$value);
        //echo "{$value}<br>isNew = " . (($this->isNew) ? 'true': 'false');
        $this->setCaption($caption);
        $this->setName($name);
        $this->setOpenAsForm($openAsForm);
        //$this->setCaptions($editCaption, $submitCaption, $cancelCaption);
        
        $this->_size      = $size;
        $this->_maxlength = $maxlength;
        $this->setValue($value);
        $this->parseAttributes($this->getValue());
        //$this->parseAttributes($value);
        //echoArray($this->_attributs, '__construct');
        //$this->parseAttributes($value);
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

    /**
     * parse value in array
     *
     * @param string $value
     */
    public function parseAttributes($value)
    {
      
        if(!$value){
            $this->_attributs = array();
            return false;
        }
        
        $value = html_entity_decode($value);
        if(false){
            //$jsonBolOk = json_validate($value);
        }else{
            $decodedJson = json_decode($value, true);
            $jsonBolOk = (json_last_error() === JSON_ERROR_NONE);
        }
        
        if($jsonBolOk){
            $this->_attributs = json_decode($value, true);
        }else if(is_array($value)){

            foreach ($value as $key=>$v){
                //echo "key = {$key} => ${$v}<br>";
                $this->_attributs[$key]['name'] = $key;
                $this->_attributs[$key]['value'] = $v;
                if(strpos($key, 'color') !== false){
                    $this->_attributs[$key]['type'] = 'color';
                }else{
                    $this->_attributs[$key]['type'] = 'textbox';
                }
            }
        }else{
            $arr = explode(';', $value);
            foreach ($arr as $index=>$v){
                $t = explode(':', $v);
                $key = $t[0];
                $this->_attributs[$key]['name'] = $t[0];
                $this->_attributs[$key]['value'] = $t[1];
                $this->_attributs[$key]['type'] = 'textbox';
                if(strpos($key, 'color') !== false){
                    $this->_attributs[$key]['type'] = 'color';
                }else{
                    $this->_attributs[$key]['type'] = 'textbox';
                }
            }
        }
    }

    /**
     * add attribute to array
     *
     * @param string $value
     */
    public function optionExists($name)
    {//echoArray($this->_attributs);
        return array_key_exists($name, $this->_attributs);
    }

    /**
     * add attribute to array
     *
     * @param string $value
     */
    public function addOption($name, $value, $type, $arr = null) 
    {
          $this->_attributs[$name]['name'] = $name;
          $this->_attributs[$name]['value'] = $value;
          $this->_attributs[$name]['type'] = $type;
          
          if(!is_array($arr)) return true;
          foreach($arr as $key=>$value){
            $this->_attributs[$name][$key] = $value;
          }
    }
    
    /**
     * add attribute to array
     *
     * @param string $value
     */
    public function removeOption($name) 
    {
        if(array_key_exists($name, $this->_attributs)) {
            unset($this->_attributs[$name]);
        };
    }
    /**
     * add attribute to array
     *
     * @param string $value
     */
    public function addNewOption($name, $value, $type, $arr = null) 
    {
        //si la clé existe déjà elle n'est pas modifiée
        //pour forcer de nouvelles valeur utiliser "addOption"
        if(array_key_exists($name, $this->_attributs)) return false;
        $this->addOption($name, $value, $type, $arr);
    }
    
    
    public function addOptionArr($attArr, $force = true)
    {
        if(array_key_exists($name, $this->_attributs) && !$force) return false;
          $key = $attArr['name'];  
          $this->_attributs[$key] = $attArr;
    }
    
    /**
     * Set initial text value
     *
     * @param string $value
     */
    public function updateOptions($name, $arr)
    {
          foreach($arr as $key=>$value){
            $this->_attributs[$name][$key] = $value;
          }
    }

    /**
     * Set initial button caption
     *
     * @param string $value
     */
    public function setCaptions($editCaption, $submitCaption='', $cancelCaption='')
    {
        if($editCaption)   $this->setCaptionArr('edit',   $editCaption);
        if($submitCaption) $this->setCaptionArr('submit', $submitCaption);
        if($cancelCaption) $this->setCaptionArr('cancel', $cancelCaption);
    }
    
    /**
     * Set initial button caption
     *
     * @param string $value
     */
    public function setCaptionArr($key, $caption)
    {
        $this->_captionArr[$key] = $caption;
    }
    
    /**
     * get initial button caption
     *
     * @param string $value
     */
    public function getCaptionArr($key)
    {
        return $this->_captionArr[$key];
    }
    
////////////////////////////////
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
    
////////////////////////////////
    /**
     * Set initial button caption
     *
     * @param string $value
     */
    public function setOpenAsForm($value)
    {
        $this->_openAsForm = $value;
    }
    
    /**
     * get initial button caption
     *
     * @param string $value
     */
    public function getOpenAsForm()
    {
        return $this->_openAsForm;
    }
    
////////////////////////////////
    /**
     * Set initial button caption
     *
     * @param string $value
     */
    public function setTextBoxVisible($value)
    {
        $this->_textBoxVisible = $value;
    }

    /**
     * get initial button caption
     *
     * @param string $value
     */
    public function getTextBoxVisible()
    {
        return $this->_textBoxVisible;
    }
    
    /**
     * Set initial button caption
     *
     * @param string $value
     */
    public function setPreviewVisible($value)
    {
        $this->_previewVisible = $value;
    }

    /**
     * get initial button caption
     *
     * @param string $value
     */
    public function getPreviewVisible()
    {
        return $this->_previewVisible;
    }
    
    /**
     * get initial button caption
     *
     * @param string $value
     */
    function toString(){
        $att = array();
        foreach($this->_attributs as $key=>$arr){
            $att[] = "{$key} : {$arr['value']};"; 
        }
        return implode("\n", $att);
    }

    function render_preview(){
        $tplRow = "<tr><td>%s</td><td>%s</td><td>%s</td></tr>";
        $html= array();
        $htmlArr[] = ("<table>");
        foreach($this->_attributs as $key=>$arr){
            $htmlArr[] = sprintf($tplRow, $key, ' : ', $arr['value']);
        }
        $htmlArr[] = '</table>';
        return implode("\n", $htmlArr);

//        foreach(var attKey in allAtt.inputArr)
//         {
//           var attribut = allAtt.inputArr[attKey];
//           switch(attribut.type){
//             case 'number':  obInp = json_getInpNumber(attribut);     break;
//             case 'color':   obInp = json_getInpColor(attribut);     break;
//             case 'color2':  obInp = json_getInpColor2(attribut);    break;
//             case 'list':    obInp = json_getInpList(attribut);      break;
//             default:
//             case 'textbox': obInp = json_getInpTextbox(attribut);    break;
//           }      
//           htmlArr.push(`<tr><td  style='text-align:right;padding-right:8px;'>${attribut.name} :</td><td style='text-align:left;'>${obInp}</td></tr>`);
//           
//         }
//         htmlArr.push('</table>');
//         htmlArr.push(json_getBtnSubmit());
//         return htmlArr.join("\n");
    }

    /**
     * Prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
    {   
    
        $mainId = $this->getName();
        $html = '';
        
        $this->setValue(json_encode($this->_attributs));
        if($this->_textBoxVisible){
            $this->setExtra("readonly style='background:#DFDFDF;width:{$this->_size }px;'");
        }else{
            $this->setExtra("style='visibility:hidden;display:none;position:relative;width:{$this->_size }px;'");
        }
        //echo "<hr>{$this->getValue()}<hr>";exit;
        $html .= parent::render() ;
        //----------------------------------------------
        if($this->getOpenAsForm()){
            $inpBtn = new \XoopsFormButton('', $mainId . '-editBtn', $this->getCaptionArr('edit')); 
            $inpBtn->setExtra("onclick='json_getForm(event, \"{$mainId}\")'");
          
            $inpSubmitCaption = new \XoopsFormHidden($mainId . '-submitCaption', $this->getCaptionArr('submit')); 
            $inpCancelSubmitCaption = new \XoopsFormHidden($mainId . '-cancelCaption', $this->getCaptionArr('cancel')); 
            $html .= $inpBtn->render() . $inpSubmitCaption->render() . $inpCancelSubmitCaption->render();
        }else{
            $html .= "<div id='formInSitu' style='display:flex;'></div>";
            $html .= "<script>json_showInSitu('{$mainId}',{$this->getWidth()});</script>";        
        }
        
        
        
        if($this->_previewVisible){
            $previewId = $mainId . '-preview';
            $title = "";//$this->toString();
            //echo "<hr>{$title}<hr>";
            //$inpBtn->setDescription($title);
            //public function __construct($caption, $name, $value = '', $rows = 5, $cols = 50)
            $visu = new \XoopsFormTextArea('Preview', $previewId, $title, count($this->_attributs),20); 
            $visu->setExtra("style='width:300px';");
            $visu->setExtra("readonly");
            $html .= $visu->render();
            $html .= "<script>json_showPreview('{$mainId}');</script>";        
        }
        //----------------------------------------------------------------      
        // Form Text quiz_set
        
        //$html .= "<script>document.getElementById({$mainId} + '-preview').value = json_toString();</script>";        
        return $html;
    }
    
 } // ============ FIN DE LA CLASSE =================
