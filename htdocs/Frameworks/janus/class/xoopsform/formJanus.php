<?php
/**
 * XOOPS table form
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
 * @version         $Id: tableform.php 12537 2014-05-19 14:19:33Z beckmi $
 */

defined('XOOPS_ROOT_PATH') || die('Restricted access');

xoops_load('XoopsForm');

/**
 * Form that will output formatted as a HTML table
 *
 * No styles and no JavaScript to check for required fields.
 */
class XoopsFormJanus extends XoopsThemeForm
{
var $_shortcutsArr = null;
var $_descColor = 'blue';
var $_descFontStyle = 'italic';
var $_descStyle = 'color:blue;font-style:italic;font-size:0.9em;';

/* *************************************

**************************************** */
function addXtrayElement($formElement, $required = false, $style = null, $classicMode = false){
    if(is_null($style)) $style = $this->_descStyle;
    
    $description = $formElement->getDescription();
    if($description == '' || $classicMode){
        $this->addElement($formElement, $required);
    }else{
        $formElement->setDescription('');
        $description = "<span style='{$style}'>{$description}</span>";
        $inpDesc = new XoopsFormLabel('', $description);
        
        $caption = $formElement->getCaption();
        $formElement->setCaption('');
        
        $xTray = new XoopsFormElementTray($caption, "<br>");
        $xTray->addElement($formElement, $required);
        $xTray->addElement($inpDesc);
        
        $this->addElement($xTray);
    }

}

/* *************************************

**************************************** */
function addHidden($name, $value){
    $this->addElement(new \XoopsFormHidden($name, $value));
}

/* *************************************

**************************************** */
function insertBreakJanus($exp, $bgColor='black', $color = null){
    $color = $this->getColor($bgColor, $color);

    $style="background:{$bgColor};color:{$color};text-align:center;";
    $html = "<div style={$style}>{$exp}</div>";
    $this->insertBreak($html);
}

/* *************************************

**************************************** */
function getColor($bgColor ='black', $color = null){
    if(is_null($color)){
        switch(strtolower($bgColor)){
            case 'black'    : $color = 'yellow' ; break;
            case 'blue'     : $color = 'white'  ; break;
            case 'magenta'  : $color = 'white'  ; break;
            case 'green'    : $color = 'lime'   ; break;
            case 'cyan'     : $color = 'blue'   ; break;
            case 'red'      : $color = 'white'  ; break;
            case 'yellow'   : $color = 'blue'   ; break;
            //case '': $color = '' : break;
            default         : $color = 'yellow' ; break;
        }
    }
    return $color;
}

/* *************************************

**************************************** */
function setDescStyle($descStyle){
    $this->_descStyle = $descStyle;
}
/* *************************************

**************************************** */
function getDescStyle(){
    return $this->_descStyle;
}

/* *************************************

**************************************** */
function addhortcuts($shortCut){
    $this->_shortcutsArr[] = $shortCut;
}

/* *************************************
* integrate void : permet d'intégrer un xoopsForm avec ou sans la colonne de titre
* @$element xoopsForm : element à intégrer
* @$modeIntegration int : Mode d'intégration dans le formulaire question
*                         0 : integration dans le formulaire sans la colonne caption
*                         1 : integration dans le formulaire avec la colonne caption; 
**************************************** */
function integrate($element, $modeIntegration){

 //$this->insertBreakJanus("<hr>modeIntegration = {$modeIntegration}<hr>", 'red');
 
    if(is_null($element) || $element == false){
        return;
    }
    //insertion de optionsForm propre à chaque plugin
    if ($modeIntegration == 1){
        $this->addElement($element);
    }else{
        $this->insertBreak($element->render());
    }
}

/* *************************************
* insertShorcuts void : insert un lineBreak avec des liens sur les différenes partie du formulaire
* @caption string : titre à afficher
* @shortcutsArr array string : liste de nom qui serve à créer les liens locaux a defini au moins a la premiere utilisation
* @bgColor string color : couleur de fond du titre
* @color string color : couleur de la police du titre
* *************************************** */
function insertBlankLines($nbLines = 1){
    $this->addElement(new XoopsFormLabel('',str_repeat('<br>', $nbLines)));
}
function insertLines($nbLines = 1){
    $this->addElement(new XoopsFormLabel('',str_repeat('<br>', $nbLines)));
}


/* *************************************
* insertShorcuts void : insert un lineBreak avec des liens sur les différenes partie du formulaire
* @caption string : titre à afficher
* @shortcutsArr array string : liste de nom qui serve à créer les liens locaux a defini au moins a la premiere utilisation
* @bgColor string color : couleur de fond du titre
* @color string color : couleur de la police du titre
* *************************************** */
function insertShorcuts($caption, $shortcutsArr = null, $bgColor='black', $color = null, $addBlankLineBefore = true){
    $color = $this->getColor($bgColor, $color);
    $htmlShortcut = [];
    
    if(is_null($shortcutsArr) && is_null($this->_shortcutsArr) ){
        $this->insertBreakJanus($caption, $bgColor, $color);
        return;
    }
        
    if (is_null($shortcutsArr)) {
        $shortcutsArr = $this->_shortcutsArr;
    }else{
        $this->_shortcutsArr = $shortcutsArr;
    }
    
    $tpl = "<a href='#%s' onclick='quizmaker_scrollWin(-80);'  style='color:{$color};'>[%s]</a>";
    $html[] = "<div >";
  
   
    $htmlShortcut = [];
    for($h = 0; $h < count($shortcutsArr); $h++){
        //ça marche aussi avec les espace, mais c'est plus propre de le enlever
        $name = str_replace(" ", "-", $shortcutsArr[$h]);
        //$name =  $shortcutsArr[$h];
        
        
        if($caption == $shortcutsArr[$h]){
            $html[] = "<b>{$caption}</b> ===> ";
            $html[] = "<a href='' name='{$name}'></a>";
        }else{
            $htmlShortcut[] = sprintf($tpl, $name, $shortcutsArr[$h]);
        }
    }
 
    
    $html[] = implode(' - ', $htmlShortcut);

    $html[] = "</div>";
    $innerHtml =  implode("", $html);  
    
    if($addBlankLineBefore) $this->insertBlankLines();
   $this->insertBreakJanus($innerHtml, $bgColor, $color);
}

} // ================= FIN DE LA CLASSE ===================
