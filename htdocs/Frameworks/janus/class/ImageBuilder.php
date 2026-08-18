<?php
/**
 * mediatheque module
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright	The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license             http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package	mediatheque
 * @since		2.5.0
 * @author 		JJDai <http://xoops.kiolo.com> - Kris <http://www.xoofoo.org>
 * @version		$Id$
**/
defined('XOOPS_ROOT_PATH') or die('Restricted access');
if(defined('JANUS_DEBUG'))
if (JANUS_DEBUG) echo "<hr>========= " . __FILE__. " =================<hr>";

class ImageBuilder
{
//var $imgInfo = null;


/**
 * Redimensionne une image en conservant le ratio original.
 * * @param string $source Path de l'image originale
 * @param string $destination Path de sauvegarde
 * @param int $width Nouvelle largeur (0 pour calculer automatiquement)
 * @param int $height Nouvelle hauteur (0 pour calculer automatiquement)
 * @return bool
 */
static function redimensionnerAvecRatio($source, $destination, $width = 0, $height = 0, $resizeIfSmaller = false) {
    // 1. Récupérer les dimensions et le type
            chmod($source, 0777);
    $info = getimagesize($source);
    if (!$info) return false;
    list($origWidth, $origHeight) = $info;
    $mime = $info['mime'];

    // 2. Calculer les dimensions manquantes pour garder le ratio
    if ($width == 0 && $height == 0) return false;
    
    if ($width == 0) {
        $width = (int)(($height / $origHeight) * $origWidth);
    } elseif ($height == 0) {
        $height = (int)(($width / $origWidth) * $origHeight);
    }
    if ($origWidth < $width && $origHeight < $height && !$resizeIfSmaller) return;

    // 3. Créer la ressource source
    switch ($mime) {
        case 'image/jpeg': $image = imagecreatefromjpeg($source); break;
        case 'image/png':  $image = imagecreatefrompng($source);  break;
        case 'image/gif':  $image = imagecreatefromgif($source);  break;
        default: return false;
    }

    // 4. Créer la ressource destination
    $newImage = imagecreatetruecolor($width, $height);

    // 5. Gérer la transparence (important pour PNG/GIF)
    if ($mime == 'image/png' || $mime == 'image/gif') {
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
        $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
        imagefilledrectangle($newImage, 0, 0, $width, $height, $transparent);
    }

    // 6. Redimensionnement haute qualité
    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);

    // 7. Sauvegarde
    $success = false;
    switch ($mime) {
        case 'image/jpeg': $success = imagejpeg($newImage, $destination, 90); break;
        case 'image/png':  $success = imagepng($newImage, $destination, 6); break;
        case 'image/gif':  $success = imagegif($newImage, $destination); break;
    }

    // 8. Libération mémoire
    imagedestroy($image);
    imagedestroy($newImage);

    return $success;
}


 /*
 * Redimensionne une image et écrase le fichier source.
 */
static function redimensionnerEtRemplacer($file, $width = 0, $height = 0, $resizeIfSmaller = false) {
    if (!file_exists($file)) return false;

    // 1. Créer un chemin temporaire pour la sécurité
    $tempFile = $file . '.tmp';

    // 2. Utiliser la fonction précédente pour créer l'image redimensionnée
    // On l'enregistre temporairement pour éviter la corruption en cas d'erreur
    if (self::redimensionnerAvecRatio($file, $tempFile, $width, $height, $resizeIfSmaller)) {
        // 3. Si succès, on remplace l'original par le temporaire
        if (rename($tempFile, $file)) {
            return true;
        }
    }
    
    // En cas d'échec, on supprime le fichier temporaire s'il existe
    if (file_exists($tempFile)) unlink($tempFile);
    return false;
}

/****************************************************************************
 *
 ****************************************************************************/
static function pw_getExt($file){
  $t = explode('.', $file);
  return $t[count($t) - 1];
}

//-------------------------------------------------------
} //--------------- Fin de la Classe --------------------
//-------------------------------------------------------


                                   





  
  


?>
