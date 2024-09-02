<?php
// $tr = print_r($tHtml, true);
// echo "<pre>{$tr}</pre>";

class CloneModule    
{
var $dirName='';
var $patKeys   = null;
var $patValues = null;
var $clone = '';
var $logocreated = false;

	/**
	 * Constructor 
	 *
	 * @param null
	 */
	public function __construct()
	{
    }
    
    
/**
 * @param $dirname
 *
 * @return bool
 */
function getForm($dirName){
    require_once XOOPS_ROOT_PATH . "/class/template.php";
    require_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
//   $xoopsTpl = new XoopsTpl();
//   $xoopsTpl->display($this->path . "/cloneModule.tpl");  
  
    $url =  XOOPS_URL . '/modules/' .  $dirName . '/admin/clone.php';
    $form  = new \XoopsThemeForm(\sprintf("Cloner le module %s", $dirName), 'clone', $url, 'post', true);
    $clone = new \XoopsFormText(_CO_JANUS_CLONE_NAME, 'clone', 20, 20, '');
    $clone->setDescription(_CO_JANUS_CLONE_NAME_DSC);
    $form->addElement($clone, true);

    $form->addElement(new \XoopsFormHidden('op', 'submit'));
    $form->addElement(new \XoopsFormButton('', '', \_SUBMIT, 'submit'));
    //$GLOBALS['xoopsTpl']->assign('form', $form->render());
    return $form->render();

}

function checkName($clone){

  //check if name is valid
  $url =  XOOPS_URL . '/modules/' . $clone . '/admin/clone.php';
  if (empty($clone) || \preg_match('/[^a-zA-Z0-9\_\-]/', $clone)) {
      \redirect_header($url, 3, \sprintf(\_CO_JANUS_CLONE_INVALID_NAME, $clone));
  }


}

/**
 * @param $dirname
 *
 * @return bool
 */
function clone($module, $clone){

    $dirname   = $module->getInfo('dirname');
    $imagePath = $module->getInfo('image');
    //---------------------------------------------------------

    $this->clone = $clone;
    $url =  XOOPS_URL . '/modules/' .  $dirName . '/admin/clone.php';
    
    //check if name is valid
    if (empty($clone) || \preg_match('/[^a-zA-Z0-9\_\-]/', $clone)) {
        \redirect_header($url, 3, \sprintf(_CO_JANUS_CLONE_INVALID_NAME, $clone));
    }

        // Check wether the cloned module exists or not
        $pathModule = XOOPS_ROOT_PATH . '/modules/' . $dirName; 
        $pathClone  = XOOPS_ROOT_PATH . '/modules/' . $clone;
        
        if ($clone && \is_dir($pathClone)) {
            \redirect_header($url, 3, \sprintf(_CO_JANUS_CLONE_EXISTS, $clone));
        }

        $patterns = [
            \mb_strtolower($dirName)           => \mb_strtolower($clone),
            \mb_strtoupper($dirName)           => \mb_strtoupper($clone),
            \ucfirst(\mb_strtolower($dirName)) => \ucfirst(\mb_strtolower($clone)),
        ];
        if(!isset($patterns[$dirName])) $patterns[$dirName] = $clone;

        $this->patKeys   = \array_keys($patterns);
        $this->patValues = \array_values($patterns);

        $this->cloneFolder($pathModule, $clone);
        $this->logocreated = $this->createLogo(\mb_strtolower($clone,$imagePath));

        //change module name in modinfo.php
        // file, read it
//         $content = \file_get_contents($pathClone . '/language/english/modinfo.php');
//         $content = \str_replace('QUIZMAKER', \mb_strtolower($clone), $content);
//         file_put_contents($dirClone . '/language/english/modinfo.php', $content);

}


/**
 * @param $dirname
 *
 * @return bool
 */
function cloneFolder($path, $clone)
{

    //remove \XOOPS_ROOT_PATH and add after replace, otherwise there can be a bug if \XOOPS_ROOT_PATH contains same pattern
    $newPath = \XOOPS_ROOT_PATH . \str_replace($this->patKeys[0], $this->patValues[0], \substr($path, \strlen(\XOOPS_ROOT_PATH)));

    if (\is_dir($path)) {
        // create new dir
        if (!\mkdir($newPath) && !\is_dir($newPath)) {
            throw new \RuntimeException(\sprintf('Directory "%s" was not created', $newPath));
        }

        // check all files in dir, and process it
        $handle = \opendir($path);
        if ($handle) {
            while (false !== ($file = \readdir($handle))) {
                if (0 !== \mb_strpos($file, '.')) {
                    $this->cloneFolder("{$path}/{$file}", $clone);
                }
            }
            \closedir($handle);
        }
    } else {
        $noChangeExtensions = ['jpeg', 'jpg', 'gif', 'png', 'zip', 'ttf'];
        if (\in_array(\mb_strtolower(\pathinfo($path, \PATHINFO_EXTENSION)), $noChangeExtensions, true)) {
            // image
            \copy($path, $newPath);
        } else {
            // file, read it
            $content = \file_get_contents($path);
            $content = \str_replace($this->patKeys, $this->patValues, $content);
            file_put_contents($newPath, $content);
        }
    }
    $this->result($clone);
}
/**
 * @param $dirname
 *
 * @return bool
 */
function result($clone){
        $msg = '';
        if (\is_dir(XOOPS_ROOT_PATH . '/modules/' . \mb_strtolower($clone))) {
            $msg .= \sprintf(_CO_JANUS_CLONE_CONGRAT, "<a href='" . \XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin&op=installlist'>" . \ucfirst(\mb_strtolower($clone)) . '</a>') . "<br>\n";
            if (!$this->logocreated) {
                $msg .= _CO_JANUS_CLONE_IMAGEFAIL;
            }
        } else {
            $msg .= _CO_JANUS_CLONE_FAIL;
        }
        $GLOBALS['xoopsTpl']->assign('result', $msg);
}


/**
 * @param $dirname
 *
 * @return bool
 */
function createLogo($dirname, $imagePath)
{
    if (!\extension_loaded('gd')) {
        return false;
    }
    $requiredFunctions = [
        'imagecreatefrompng',
        'imagecolorallocate',
        'imagefilledrectangle',
        'imagepng',
        'imagedestroy',
        'imagefttext',
        'imagealphablending',
        'imagesavealpha',
    ];
    foreach ($requiredFunctions as $func) {
        if (!\function_exists($func)) {
            return false;
        }
    }
    //            unset($func);
    
    $fldRessources = XOOPS_ROOT_PATH . '/Frameworks/janus/ressources';
    if (!\file_exists($imageBase = $fldRessources . '/logoModuleVierge.png')
        || !\file_exists($font   = $fldRessources . '/VeraBd.ttf')){
        return false;
    }

    $imageModule = \imagecreatefrompng($imageBase);
    // save existing alpha channel
    \imagealphablending($imageModule, false);
    \imagesavealpha($imageModule, true);

    //Erase old text
    $greyColor = \imagecolorallocate($imageModule, 237, 237, 237);
    \imagefilledrectangle($imageModule, 5, 35, 85, 46, $greyColor);

    // Write text
    $textColor     = \imagecolorallocate($imageModule, 0, 0, 0);
    $spaceToBorder = (int)((80 - \mb_strlen($dirname) * 6.5) / 2);
    \imagefttext($imageModule, 8.5, 0, $spaceToBorder, 45, $textColor, $font, \ucfirst($dirname), []);

    // Set transparency color
    //$white = imagecolorallocatealpha($imageModule, 255, 255, 255, 127);
    //imagefill($imageModule, 0, 0, $white);
    //imagecolortransparent($imageModule, $white);

    \imagepng($imageModule, XOOPS_ROOT_PATH . '/modules/' . $dirname . '/' . $imagePath);
    \imagedestroy($imageModule);

    return true;
}

} // ---------------- FIN DE LA CLASSE -----------------------------  

                              
    
?>
