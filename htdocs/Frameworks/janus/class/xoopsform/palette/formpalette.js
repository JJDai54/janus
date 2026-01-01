
/* ******************************************* */
/*                                             */
/* ******************************************* */

var idPicker = 'xoopsFormPicker';

function palette_showPicker(e, palette){
    var obDivSource =  e.currentTarget;
    var xformId = obDivSource.getAttribute('xformId');
    
    var obPicker = palette_getHtmlPicker(xformId, palette);
    document.body.appendChild(obPicker);
    var position = palette_getAbsolutePosition(obDivSource);   
//alert('palette_showPicker : obDivSource = ' + obDivSource.id);
//alert('palette_showPicker : idSource = ' + obDivSource.id);
         
        obPicker.style.display='block';
        obPicker.style.left = position.x + "px";
        obPicker.style.top =  position.y + "px";
        obPicker.setAttribute('idSource', e.currentTarget.id)
//     if (etat == 1){
//     }else{
//         obPicker.style.display='none';
//     }
}  


/* ******************************************* */
/*     ColorPicker                             */
/* ******************************************* */
function palette_getHtmlPicker(xformId, paletteId='16c'){
 //palette='FFFFFF,C0C0C0,808080,000000,FF0000,800000,FFFF00,808000,00FF00,008000,00FFFF,008080,0000FF,000080,FF00FF,800080';
 //alert('palette_getHtmlPicker : paletteId = ' + paletteId)
    if(!paletteId) paletteId = 'full';

    var paletteArr = palette_getPalette(xformId, paletteId);

//alert(palette);
    var obPicker = document.createElement('div');
    obPicker.id = idPicker;
    obPicker.setAttribute('picker','0');
    obPicker.classList.add('formPalette_picker');
    obPicker.style.zIndex = allAtt.zindex + 20;
    obPicker.addEventListener('mouseleave', function (event){palette_pickerColor_close(event);});
    
    var onClick = `palette_pickerSelectColor(event,"${idPicker}", "${xformId}");`;
    
    //var divColorSize = 30;
       
    var divColor = `<div id='{idDivColor}' gridColor='{gridColor}'`
                 + ` class='formPalette_gridColor'`
                 + ` style='background:{color};width:${paletteArr.divColorSize}px;height:${paletteArr.divColorSize}px;'`
                 + ` onclick='${onClick}'></div>`;

    var html = '';
    var colorArr = paletteArr.palette.split(',');
      for(k = 0; k < colorArr.length; k++ ){
         html += divColor.replaceAll('{color}' , colorArr[k])
                         .replace('{gridColor}', colorArr[k])
                         .replace('{idDivColor}', 'idPicker_' + k);
        
            if(k != 0 && ((k+1) % paletteArr.nbColonnes) == 0 ){
                html += '<br>';
            }
                 
     }
    
    obPicker.innerHTML = html;
    //document.body.appendChild(obPicker);
    return obPicker;
}


/* ***************************************
algorithme qui calcul la position absolue d'un div sur une page html 
**************************************** */
function palette_getAbsolutePosition(element) {
  let x = 0;
  let y = 0;
  let currentElement = element;

  while (currentElement && currentElement !== document.body) {
    x += currentElement.offsetLeft;
    y += currentElement.offsetTop;
    currentElement = currentElement.offsetParent;
  }

  return { 'x': x, 'y': y };
}


/* *********************************
* converti une couleur au foramt RGB (r,g,b) en couleur hexa
* */
function palette_rgbToHex(rgb) {
    if(rgb[0] == '#'){ return rgb;}
    if(rgb.indexOf('rgb') < 0){return rgb;}
    
    var rgbRegex = /^rgb\(\s*(-?\d+)(%?)\s*,\s*(-?\d+)(%?)\s*,\s*(-?\d+)(%?)\s*\)$/;
    var result, r, g, b, hex = "";
    if ( (result = rgbRegex.exec(rgb)) ) {
        r = palette_componentFromStr(result[1], result[2]);
        g = palette_componentFromStr(result[3], result[4]);
        b = palette_componentFromStr(result[5], result[6]);

        hex = "#" + (0x1000000 + (r << 16) + (g << 8) + b).toString(16).slice(1);
    }
    return hex;
}
/* *********************************
* utilisé par palette_rgbToHex
* */
function palette_componentFromStr(numStr, percent) {
    var num = Math.max(0, parseInt(numStr, 10));
    return percent ?
        Math.floor(255 * Math.min(100, num) / 100) : Math.min(255, num);
}

/* ******************************************************************** */
/* ============ evenements ============================================ */
/* ******************************************************************** */


/* ******************************************************************** 
 *
 * ******************************************************************** */
function palette_pickerColor_close(ev){
      ev.currentTarget.style.display='none';
}

/* ******************************************************************** 
 *
 * ******************************************************************** */
function palette_pickerSelectColor(e, idPicker, xformId){
    var obPicker = document.getElementById(idPicker);
    var obDivColor =  e.currentTarget;
    
    var idSource =  obPicker.getAttribute('idSource');
    var obSource = document.getElementById(idSource);
    //var xformId = obSource.getAttribute('xformId');
//alert(xformId);    
    obSource.style.background = obDivColor.style.background;
    obSource.value = palette_rgbToHex(obDivColor.style.background);
    document.getElementById(xformId).value = obSource.value ;
    document.getElementById(xformId).click() ;
    //document.getElementById(xformId).parentNode.change();
    
    obPicker.style.display='none';
    document.body.removeChild(document.getElementById(idPicker));
    //e.stopPropagation();    
//alert('palette_pickerSelectColor : xformId = ' + xformId + '===>' + document.getElementById(xformId).value );
}

/* ******************************************************************** 
 *
 * ******************************************************************** */
function palette_getPalette(xformId, paletteId){
var palette = '';
var nbColonnes = 8;
var divColorSize = 16;
//    var obPicker = document.getElementById(idPicker);
//alert(obPicker.getAttribute('xformId'));
    if(document.getElementById(xformId + '-userPalette')){
    if(document.getElementById(xformId + '-userPalette').value !=''){
        return {'palette' :      document.getElementById(xformId + '-userPalette').value , 
                'nbColonnes' :   document.getElementById(xformId + '-nbColonnes').value,
                'divColorSize' : document.getElementById(xformId + '-divColorSize').value};
    }}


//alert('palette_getPalette : ' + paletteId);
    switch (paletteId){
    case '16c':
            palette = "black,maroon,green,olive,navy,purple,teal,silver,gray,red,lime,yellow,blue,fuchsia,aqua,white";
            nbColonnes = 4;
            divColorSize = 32;
            break;
        
    case 'full':
    palette = + "black,#000033,#000066,#000099,#0000CC,blue,#003300,#003333,#003366,#003399,#0033CC,#0033FF"
              + ",#330000,#330033,#330066,#330099,#3300CC,#3300FF,#333300,#333333,#333366,#333399,#3333CC,#3333FF"
              + ",#660000,#660033,#660066,#660099,#6600CC,#6600FF,#663300,#663333,#663366,#663399,#6633CC,#6633FF"
              + ",#990000,#990033,#990066,#990099,#9900CC,#9900FF,#993300,#993333,#993366,#993399,#9933CC,#9933FF"
              + ",#CC0000,#CC0033,#CC0066,#CC0099,#CC00CC,#CC00FF,#CC3300,#CC3333,#CC3366,#CC3399,#CC33CC,#CC33FF"
              + ",red,#FF0033,#FF0066,#FF0099,#FF00CC,fuchsia,#FF3300,#FF3333,#FF3366,#FF3399,#FF33CC,#FF33FF"
              + ",#006600,#006633,#006666,#006699,#0066CC,#0066FF,#009900,#009933,#009966,#009999,#0099CC,#0099FF"
              + ",#336600,#336633,#336666,#336699,#3366CC,#3366FF,#339900,#339933,#339966,#339999,#3399CC,#3399FF"
              + ",#666600,#666633,#666666,#666699,#6666CC,#6666FF,#669900,#669933,#669966,#669999,#6699CC,#6699FF"
              + ",#996600,#996633,#996666,#996699,#9966CC,#9966FF,#999900,#999933,#999966,#999999,#9999CC,#9999FF"
              + ",#CC6600,#CC6633,#CC6666,#CC6699,#CC66CC,#CC66FF,#CC9900,#CC9933,#CC9966,#CC9999,#CC99CC,#CC99FF"
              + ",#FF6600,#FF6633,#FF6666,#FF6699,#FF66CC,#FF66FF,#FF9900,#FF9933,#FF9966,#FF9999,#FF99CC,#FF99FF"
              + ",#00CC00,#00CC33,#00CC66,#00CC99,#00CCCC,#00CCFF,lime,#00FF33,#00FF66,#00FF99,#00FFCC,aqua"
              + ",#33CC00,#33CC33,#33CC66,#33CC99,#33CCCC,#33CCFF,#33FF00,#33FF33,#33FF66,#33FF99,#33FFCC,#33FFFF"
              + ",#66CC00,#66CC33,#66CC66,#66CC99,#66CCCC,#66CCFF,#66FF00,#66FF33,#66FF66,#66FF99,#66FFCC,#66FFFF"
              + ",#99CC00,#99CC33,#99CC66,#99CC99,#99CCCC,#99CCFF,#99FF00,#99FF33,#99FF66,#99FF99,#99FFCC,#99FFFF"
              + ",#CCCC00,#CCCC33,#CCCC66,#CCCC99,#CCCCCC,#CCCCFF,#CCFF00,#CCFF33,#CCFF66,#CCFF99,#CCFFCC,#CCFFFF"
              + ",#FFCC00,#FFCC33,#FFCC66,#FFCC99,#FFCCCC,#FFCCFFyellow,#FFFF33,#FFFF66,#FFFF99,#FFFFCC,white";    
            nbColonnes = 12;
            divColorSize = 16;
            break;
            
    case 'rgb':
    palette = "red,#FF0400,#FF0900,#FF0D00,#FF1100,#FF1500,#FF1A00,#FF1E00,#FF2200,#FF2600,#FF2B00,#FF2F00,#FF3300,#FF3700,#FF3C00,"
            + "#FF4000,#FF4400,#FF4800,#FF4D00,#FF5100,#FF5500,#FF5900,#FF5E00,#FF6200,#FF6600,#FF6A00,#FF6F00,#FF7300,#FF7700,#FF7B00,"
            + "#FFBF00,#FFC400,#FFC800,#FFCC00,#FFD000,#FFD500,#FFD900,#FFDD00,#FFE100,#FFE600,#FFEA00,#FFEE00,#FFF200,#FFF700,#FFFB00,"
            + "yellow,#FBFF00,#F7FF00,#F2FF00,#EEFF00,#EAFF00,#E5FF00,#E1FF00,#DDFF00,#D9FF00,#D4FF00,#D0FF00,#CCFF00,#C8FF00,#C3FF00,"
            + "#BFFF00,#BBFF00,#B7FF00,#B3FF00,#AEFF00,#AAFF00,#A6FF00,#A1FF00,#9DFF00,#99FF00,#95FF00,#90FF00,#8CFF00,#88FF00,#84FF00,"
            + "#80FF00,#7BFF00,#77FF00,#73FF00,#6FFF00,#6AFF00,#66FF00,#62FF00,#5EFF00,#59FF00,#55FF00,#51FF00,#4CFF00,#48FF00,#44FF00,"
            + "#40FF00,#3CFF00,#37FF00,#33FF00,#2FFF00,#2BFF00,#26FF00,#22FF00,#1EFF00,#1AFF00,#15FF00,#11FF00,#0DFF00,#08FF00,#04FF00,"
            + "lime,#00FF04,#00FF08,#00FF0D,#00FF11,#00FF15,#00FF19,#00FF1E,#00FF22,#00FF26,#00FF2A,#00FF2F,#00FF33,#00FF37,#00FF3C,"
            + "#00FF40,#00FF44,#00FF48,#00FF4C,#00FF51,#00FF55,#00FF59,#00FF5D,#00FF62,#00FF66,#00FF6A,#00FF6F,#00FF73,#00FF77,#00FF7B,"
            + "#00FF80,#00FF84,#00FF88,#00FF8C,#00FF90,#00FF95,#00FF99,#00FF9D,#00FFA1,#00FFA6,#00FFAA,#00FFAE,#00FFB2,#00FFB7,#00FFBB,"
            + "aqua,#00FBFF,#00F7FF,#00F2FF,#00EEFF,#00EAFF,#00E6FF,#00E1FF,#00DDFF,#00D9FF,#00D4FF,#00D0FF,#00CCFF,#00C8FF,#00C4FF,"
            + "deepskyblue,#00BBFF,#00B7FF,#00B2FF,#00AEFF,#00AAFF,#00A6FF,#00A2FF,#009DFF,#0099FF,#0095FF,#0090FF,#008CFF,#0088FF,#0084FF,"
            + "#0080FF,#007BFF,#0077FF,#0073FF,#006FFF,#006AFF,#0066FF,#0062FF,#005EFF,#0059FF,#0055FF,#0051FF,#004CFF,#0048FF,#0044FF,"
            + "#0040FF,#003CFF,#0037FF,#0033FF,#002FFF,#002AFF,#0026FF,#0022FF,#001EFF,#001AFF,#0015FF,#0011FF,#000DFF,#0008FF,#0004FF";
            nbColonnes = 15;
            divColorSize = 16;
            break;
            
    case 'classic':
    default:
     palette =  '#000000,#FFFFFF,#880015,#ED1C24,#FF7F27,#FFF200,#22B14C,#00A2E8,#3F48CC,#A349A4'
             + ',#808080,#F2F2F2,#E7B9C0,#FBCFD0,#FFE5D4,#FFFCCC,#C8EFD4,#C8EBFA,#D3D5F5,#EDD3ED'
             + ',#595959,#D9D9D9,#CF7C89,#CF7C89,#FFB27D,#FFF766,#6BD089,#60C5F1,#8389E0,#C785C8'
             + ',#262626,#A6A6A6,#660010,#B21016,#BF5B16,#BFB500,#138535,#007AAE,#232B99,#7A297B'
             + ',#0D0D0D,#808080,#44000A,#77070B,#803A0A,#807900,#085820,#005174,#101566,#511252'
             + ',#C00000,#FF0000,#FFC000,#FFFF00,#92D050,#00B050,#00B0F0,#0070C0,#002060,#7030A0';
            nbColonnes = 10;
            divColorSize = 24;
    
    }
    return {'palette' : palette, 'nbColonnes' : nbColonnes, 'divColorSize' : divColorSize};
}
