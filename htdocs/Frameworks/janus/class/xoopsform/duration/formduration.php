<?php
/**
 * XOOPS form element table
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
 * @version         $Id: formelementtray.php 9339 2012-04-16 00:15:04Z beckmi $
 */
 
defined('XOOPS_ROOT_PATH') or die('Restricted access');




class XoopsFormDuration extends  XoopsFormElement
{
var $units=['d' => 'jours', 'h' => 'Heures', 'm' => 'minutes', 's' => 'secondes'];
var $compteurs ='dhms'; // une letre pour chaque unité : Jours, heures, minutes, secondes
var $value = 0;

    /**
     * Constructor
     *
     * @param string $caption   Caption
     * @param string $name      "name" attribute
     * @param string $value     Initial seconds
     */
    public function __construct($caption, $name, $value = 0)
    {
        global $xoTheme;
        $xoTheme->addScript(JANUS_URL_XFORM . '/duration/formduration.js');
        
        $this->setCaption($caption);
        $this->setName($name);
        $this->setValue($value);
        
                
    }

    /**
     * Get initial content
     *
     * @param  bool $encode To sanitizer the text? Default value should be "true"; however we have to set "false" for backward compatibility
     * @return string
     */
    public function setUnits($j,$h,$m,$s)
    {
     $this->units = ['j' => $j, 
                     'h' => $h, 
                     'm' => $m, 
                     's' => $s];
    }


    /********************************************************
     * Get initial content
     *
     * @param  bool $encode To sanitizer the text? Default value should be "true"; however we have to set "false" for backward compatibility
     * @return string
     */
    public function getValue($encode = false)
    {
        return $encode ? htmlspecialchars($this->value, ENT_QUOTES) : $this->value;
    }

    /**
     * Set initial text value
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**********************************************************
     * Get initial content
     *
     * @param  bool $encode To sanitizer the text? Default value should be "true"; however we have to set "false" for backward compatibility
     * @return string
     */
    public function getCompteurs($encode = false)
    {
        return $encode ? htmlspecialchars($this->compteurs, ENT_QUOTES) : $this->compteurs;
    }

    /**
     * Set initial text value
     *
     * @param string $value
     */
    public function setCompteurs($compteurs)
    {
        $this->compteurs = strtolower($compteurs);
    }

/**********************************************************************
*
* **********************************************************************/
public function setArr($arr){
}

/**********************************************************************
*
* **********************************************************************/
public static function explodeSecondes ($seconds){
//$seconds = 43675;
    $arr = ['d' => floor($seconds / (3600*24)),
            'h' => floor(($seconds / 3600) % 24),
            'm' => floor(($seconds / 60) % 60),
            's' => $seconds % 60];
    return $arr;
}


/**********************************************************************
*
* **********************************************************************/
public static function implodeHMS ($arr){
//function joinHMS ($arr){
    $secondes = 0;
    if(isset($arr['d'])) $secondes += $arr['d'] * (60 * 60 * 24); 
    if(isset($arr['h'])) $secondes += $arr['h'] * (60 * 60); 
    if(isset($arr['m'])) $secondes += $arr['m'] * 60; 
    if(isset($arr['s'])) $secondes += $arr['s'] ; 

    return $secondes; 
}


/**********************************************************************
*
* **********************************************************************/
public function getInputNumber ($arr, $unit){
    $size = 5;
    $min = 0;
    switch($unit){
        case 'd': $max = 30; break;
        case 'h': $max = 24; break;
        case 'm': $max = 60; break;
        case 's': $max = 60; break;
        default : return '';
    }
    $name = $this->getName();
    
    
    
    $ev = "onchange='formDuration_updateDuration(event, \"{$name}\")'";
    $inp = "<input class='form-control' type='number' name='{$name}'"
         . " id='{$name}-{$unit}' title='' "
         . " size='{$size}' maxlength='{$size}'"
         . " value='{$arr[$unit]}'" 
         . " min='{$min}'" 
         . " max='{$max}'"
         . " style='text-align:right;{$this->extra}'  {$ev}/> {$this->units[$unit]} ";
    return $inp;
}

/**
 * prepare HTML to output this group
 *
 * @return string HTML output
 */
function render() {
  
  $arr = self::explodeSecondes ($this->value);
  $sep = ' et ';
  $name = $this->getName();
  
  $inpArr = [];
  for ($h = 0; $h < strlen($this->compteurs); $h++){
      $unit =  substr($this->compteurs, $h, 1);
      $inpArr[] = $this->getInputNumber($arr, $unit);
  }

  $html = "<div>" . implode($sep, $inpArr)
//        . " [Total secondes = {$this->value}]"
        . "<input type='hidden' name='{$name}' id ='{$name}' value='{$this->value}'>"
        . " <input type='button'  value='...' onclick='formDuration_updateCompteurs(event, \"{$name}\", 0);'></div>";
    
    return $html;
 
}


} // fin de la classe



?>
