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
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * A simple text field
 */
class XoopsFormImage extends XoopsFormElement
{

    /**
     * Value
     *
     * @var string
     * @access private
     */
    public $_value;
    
    /**
     * Maximum size for an uploaded file
     *
     * @var int
     * @access private
     */
    public $_maxFileSize;

    var $_local_description;    
    var $_url='';    
    var $_file;    
    var $_height = 120;    
    /**
     * Constructor
     *
     * @param string $caption Caption
     * @param string $name "name" attribute
     * @param int $size Size
     * @param int $maxlength Maximum length of text
     * @param string $value Initial text
     */
     
    function __construct($caption , $name, $maxfilesize, $value, $url='')
    {
       
        $this->setValue($value);  
        $this->setCaption($caption);  
        $this->setName($name);
        $this->_maxFileSize = (int)$maxfilesize;
        $this->_url = $url;
        
    }
    

    /**
     * Get the "value" attribute
     *
     * @param  bool $encode To sanitizer the text?
     * @return string
     */
    public function getValue($encode = false)
    {
        return $encode ? htmlspecialchars($this->_value, ENT_QUOTES) : $this->_value;
    }

    /**
     * Sets the "value" attribute
     *
     * @patam $value    string
     * @param $value
     */
    public function setValue($value)
    {
        $this->_value = $value;
    }
    

    /**
     * Get the "height" attribute
     *
     * @param  integer 
     * @return integer
     */
    public function getHeight()
    {
        return $this->_height;
    }

    /**
     * Sets the "height" attribute
     *
     * @patam $value    integer
     * @param =void
     */
    public function setHeight($value)
    {
        $this->_height = $value;
    }
   
    
    /**
     * Prepare HTML for output
     *
     * @return string HTML
     */

    function render()
    {
        $imageUrl = $this->_url . '/' . $this->getValue();
        $file_tray = new XoopsFormElementTray($this->getCaption(), '<br>');        
        
        
        if (!empty($imageUrl)) {
          if (strcmp(substr($imageUrl,0,strlen(XOOPS_URL)), XOOPS_URL) == 0) {
            $file_tray->addElement(new XoopsFormLabel('', "<img src='" .  $imageUrl . "' name='image' id='image' height='{$this->getHeight()}' alt=''><br><br>"));
            $check_del_img = new XoopsFormCheckBox('', 'delimg1');
            $check_del_img->addOption(1, "del image");
            $file_tray->addElement($check_del_img);
          }
        } 
        
        

      $file_img = new XoopsFormFile('', $this->getName(), $this->_maxFileSize);
      $file_img->setExtra("size ='40'");
      $file_tray->addElement($file_img);
        
//         $msg        = sprintf("%s ko max - largeur et/ou hauteur: %s pixels max", (int)(400728 / 1000), 500, 500);        
//         $file_label = new XoopsFormLabel('', '<br>' . $msg);
//         $file_tray->addElement($file_label);
//         
        $file_tray->addElement(new XoopsFormHidden('file1', $this->getValue()));
        
        return $file_tray->render();    
    }

}
////////////////////////////////////////////////////////////
/* *************************************************

*************************************************** */
class XoopsFormSaveImage extends XoopsFormElement
{
var $uploaderErrors = '';

    function __construct()
    {
       
    }

public function save($formName, $path, $optionsArr, &$nameOrg = ''){
echoArray($optionsArr, 'options');
    if(!$_POST['xoops_upload_file']) return false;    
    if(!$_FILES[$formName]['name']) return '';
    include_once XOOPS_ROOT_PATH . '/class/uploader.php';    
    $prefix = (isset($optionsArr['prefix'])) ? $optionsArr['prefix'] : '';
    $renameImage = (isset($optionsArr['renameImage'])) ? $optionsArr['renameImage'] : false;

    $nameOrg = '';
    $keyFile = array_search($formName, $_POST['xoops_upload_file']);    
    $savedFilename = '';
    //$uploaderErrors = '';
    $uploader = new \XoopsMediaUploader($path , $optionsArr['mimetypes_image'], $optionsArr['maxsize_image'], null, null);


    if ($uploader->fetchMedia($_POST['xoops_upload_file'][$keyFile])) {

        $uploader->setPrefix($prefix);
        $uploader->fetchMedia($_POST['xoops_upload_file'][$keyFile]);
        if (!$uploader->upload()) {
            $this->uploaderErrors = $uploader->getErrors();
//     echo "<hr>save_images : uploaderErrors ===> {$this->uploaderErrors}<hr>";
//     exit('ici');
        } else {
            $savedFilename = $uploader->getSavedFileName();

            $this->nameOrg = $_FILES[$_POST['xoops_upload_file'][$keyFile]]['name'];       
            if($renameImage){
                //echo "===>savedFilename : {$savedFilename}<br>";  
                //modification du nom pour les repérer dans le dossier   
                $newName = $prefix . '-' . sanitiseFileName($this->nameOrg);
                rename($path.'/'. $savedFilename,  $path.'/' . $newName);
                $savedFilename = $newName;
            }
            //retire l'extension et remplace les _ par des espaces
            $h= strrpos($this->nameOrg,'.');
            $i=0;
            $this->nameOrg = str_replace('_', ' ', substr($this->nameOrg, $i, $h));

        }


    } else {

        $this->uploaderErrors = $uploader->getErrors();
        $savedFilename = '';
    }
    //exit ($savedFilename);
    //echo "<hr>save_images : uploaderErrors ===> {$this->uploaderErrors}<hr>";
    //exit;
    return $savedFilename;
}
public function isError(){
    return $this->uploaderErrors != '';
}

public function getError($style = "color:red;"){
    if($style){
        return "<span style='{$style}'>" .  $this->uploaderErrors . "<span>";
    }else{
        return $this->uploaderErrors;
    }
}

} // ============= fin de la classe ========================

?>
