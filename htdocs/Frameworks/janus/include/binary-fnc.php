<?php

/*              janus
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

if (JANUS_DEBUG) echo "<hr>========= " . __FILE__. " =================<hr>";

/***************************************************************************
Renvoie la valeur d'un bit précisé par un index dans la valeur binaire
****************************************************************************/
function isBitOk($bitIndex, $binValue){
  //$b = pow(2, $bitIndex);
  $b = ( 1 << $bitIndex);
  $v = (($binValue &&  $b) <> 0 ) ? 1 : 0;
  return $v;


}

/**
 * Returns an array of boolean
 * @$valueBin  int binaire
 * @return array
 */
function convert_bin_to_array($valueBin, $nbMaxBits = 32)  
{      
    $tBin = array();                                  
    for($h = 0; $h < $nbMaxBits; $h++) {
        //$tBin[$h] =     (($valueBin & pow(2,$h))  != 0);
        $tBin[$h] =     (($valueBin & ( 1 << $h))  != 0);
    }

//echo "<hr><pre>" .  print_r($tBin, true) . "</pre><hr>";        
     
    return $tBin;
}

/**
 * @param $val
 * @return float|int
 */
function returnBytes($val)
{
    switch (mb_substr($val, -1)) {
        case 'K':
        case 'k':
            return (int)$val * 1024;
        case 'M':
        case 'm':
            return (int)$val * 1048576;
        case 'G':
        case 'g':
            return (int)$val * 1073741824;
        default:
            return $val;
    }
}
          
?>
