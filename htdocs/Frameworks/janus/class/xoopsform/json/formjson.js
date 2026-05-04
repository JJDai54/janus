//alert(`xFormJason`);

class clsJsonForm {
    inputArr = null;
    idSource = '';
    obSource = null;
    currentTarget = null;
    version = "1.00 beta 1";
    zIndex = 20000;
    
    constructor(idSource){
        this.idSource = idSource;
        this.obSource = document.getElementById(idSource);
        //alert(idSource + "\n-------------\n" + this.obSource.value);
        this.inputArr = JSON.parse(this.obSource.value);  
        this.zIndex = this.obSource.zIndex + 10;
     }      
}

var jsonFormArr = new Array();
var lastIdSource = '';

/* ******************************************* */
/*                           */
/* ******************************************* */
function json_getJsonForm(idSource){
    if(!jsonFormArr[idSource]){
        jsonFormArr[idSource] = new clsJsonForm(idSource);
    }
    return jsonFormArr[idSource];
}
/* ******************************************* */
/*                           */
/* ******************************************* */
function json_getForm(ev, idSource, divStyle=''){
//alert(`json_getForm : idSource = ${idSource}`);
    var jsonForm = json_getJsonForm(idSource);
    jsonForm.currentTarget = ev.currentTarget;
 /*
 */
    jsonForm.obSource.parentNode.appendChild(json_getMask(jsonForm));
    var obForm = json_getFormContent(jsonForm, true, divStyle);
    jsonForm.obSource.parentNode.appendChild(obForm);

    obForm.innerHTML = json_buildForm(idSource,true);
    lastIdSource = idSource;
//alert("formStyleCss : " + obSource.id);
//return obForm.innerHTML ;
document.onkeydown = json_applyKey;

}

/* ******************************************* */
/*                           */
/* ******************************************* */
function json_showInSitu(idSource, divInsitu, divStyle=''){
//     if(!jsonFormArr[idSource]){
//         jsonFormArr[idSource] = new clsJsonForm(idSource);
//     }
//     var jsonForm = jsonFormArr[idSource];
    var jsonForm = json_getJsonForm(idSource);


    var obForm = json_getFormContent(jsonForm, false, divStyle);
    obForm.innerHTML = json_buildForm(idSource,false);
    //obSource.parentNode.appendChild(obForm);
    //obSource.parentNode.appendChild(json_getHtmlPicker());

    var obInSitu = document.getElementById(divInsitu);
    obInSitu.appendChild(obForm);
    //obSource.innerHTML = json_buildForm();
    //alert(obForm.innerHTML);
    //obInSitu.innerHTML = "zzz" + obForm.innerHTML;
    json_update(idSource);

}

/* ******************************************* */
/*     creation du mask et du div principal    */
/* ******************************************* */



function json_getFormContent(jsonForm, isForm = false, divStyle){
    var obDiv = document.createElement('div');
    var styleArr =  JSON.parse(divStyle);
     
    if(isForm){
    obDiv.id = jsonForm.idSource + '-main';
        //position = json_getAbsolutePosition(allAtt.idSource + '-btn');
        position = json_getAbsolutePosition(jsonForm.currentTarget);
            
        obDiv.style.position = 'absolute';
        obDiv.classList.add('formJson_form');

        obDiv.style.left = position.x + "px";
        obDiv.style.top =  position.y + "px";

    //     obDiv.style.left = (position.x + 100) + "px";
    //     obDiv.style.top =  (position.y + allAtt.obSource.offsetHeight) + "px";
        //obDiv.style.zIndex = jsonForm.zIndex + 100;
        obDiv.style.zIndex = document.getElementById(jsonForm.idSource + '-mask').style.zIndex + 10;
        var zIndex = document.getElementById(jsonForm.idSource + '-mask').style.zIndex;
        //alert(`zIndex : ${zIndex} - ${obDiv.style.zIndex }`);
    }else{
        obDiv.classList.add('formJson_form');
        //obDiv.style.width = width  + "px";
        //obDiv.style.background = styleArr['background'];
        //obDiv.style.borderColor = '#00CC00';
        //obDiv.style['borderColor'] = 'blue';
        for(var att in styleArr){
            obDiv.style[att] = styleArr[att];
        }
                
        addEventListener("change", function(){ json_update(jsonForm.obSource.id); }); 
    //    addEventListener("change", function(){ json_test(currentTarget.id, "Hello World!"); }); 
    //     obDiv.style.left = (position.x + 100) + "px";
    //     obDiv.style.top =  (position.y + allAtt.obSource.offsetHeight) + "px";

    }
    
    

    return obDiv;

}
function json_test(idSource, exp){
    //alert(exp);
    json_update(idSource);
}

function json_getMask(jsonForm){
    var obDiv = document.createElement('div');
    obDiv.id = jsonForm.idSource + '-mask';
    obDiv.classList.add('formJson_mask');
    obDiv.style.zIndex = jsonForm.zIndex;
    
    let scrollHeight = Math.max(
      document.body.scrollHeight, document.documentElement.scrollHeight,
      document.body.offsetHeight, document.documentElement.offsetHeight,
      document.body.clientHeight, document.documentElement.clientHeight
    );
        obDiv.style.height =  scrollHeight + 'px';     
        
    let scrollWidth = Math.max(
      document.body.scrollWidth, document.documentElement.scrollWidth,
      document.body.offsetWidth, document.documentElement.offsetWidth,
      document.body.clientWidth, document.documentElement.clientWidth
    );
    obDiv.style.width =  scrollWidth + 'px';     
    
    return obDiv;
}

/*********************************************
 *   
 *********************************************/ 
function json_applyKey (_event_){
	// --- Retrieve event object from current web explorer
	//var winObj = quizmaker_checkEventObj(_event_);
	
	var intKeyCode = _event_.keyCode;
	var intAltKey = _event_.altKey;
	var intCtrlKey = _event_.ctrlKey;
// alert("key : " + intKeyCode);	
// alert("intCtrlKey : " + intCtrlKey);	
  
  if(intKeyCode == 27){
    json_close(_event_, lastIdSource);
  }
}

function json_buildForm(idSource, isForm = true){
    var jsonForm = jsonFormArr[idSource];
    var prefix = jsonForm.idSource;
// alert(`json_buildForm :\n idSource = ${idSource} \n prefix  = ${prefix}`);   
    var obInp = null;
    var htmlArr = [];
    var hiddenArr = [];
    htmlArr.push("<table>");
    
    for(var attKey in jsonForm.inputArr)
    {
      var attribut = jsonForm.inputArr[attKey];
      switch(attribut.type){
        case 'number':   obInp = json_getInpNumber(prefix, attribut);    break;
        case 'color':    obInp = json_getInpColor(prefix, attribut);     break;
        case 'palette':  obInp = json_getInpPalette(prefix, attribut);   break;
        case 'listbox':  attribut.type='list';
        case 'list':     obInp = json_getInpList(prefix, attribut);      break;
        case 'radio':    obInp = json_getInpRadio(prefix, attribut);     break;
        case 'checkbox': obInp = json_getInpCheckbox(prefix, attribut);  break;
        case 'hidden':   obInp = json_getInpHidden(prefix, attribut);    break;
        default:
        case 'textbox': obInp = json_getInpTextbox(prefix, attribut);   break;
      }
   
      if (attribut.type == 'hidden'){
        //si ce sont des balises hidden on le met apres le tableau pour eviter de générer des lignes de table vide
        hiddenArr.push(obInp);
      }else{
        //var caption = (attribut._caption_) ? attribut._caption_ : attribut.name;
        var caption = (attribut.caption) ? attribut.caption : attribut.name;
        htmlArr.push(`<tr><td  style='text-align:right;padding-right:8px;'>${caption} :</td><td style='text-align:left;'>${obInp}</td></tr>`);
      }   
      
    }
    htmlArr.push('</table>');
    
    //ajout des balise hidden si il y en a
    if(hiddenArr.length > 0){
        htmlArr.push ("\n" + hiddenArr.join("\n"));
    }
    
    
    if(isForm){
        htmlArr.push(json_getBtnSubmit(idSource));
    }
    return htmlArr.join("\n");
}

function json_getOptionAtt(exp, currentValue, inputType){
        //si il y a le signe egal, recuperer la valeur sinon utiliser options[h]
        var label = '';
        var value = '';
        var itemArr = exp.split('=');
        
        if (itemArr. length > 1){
            label = itemArr[0];
            value = itemArr[1];
        }else{
            label = exp;
            value = exp;
        }
        var selectAtt = (inputType == 'list') ? 'selected' : 'checked' ;
        var itemSelected = (value == currentValue) ? selectAtt : '';
//alert(`json_getOptionAtt : |${value}| => |${currentValue}| => ${inputType} => ${itemSelected}`);
        return {'label':label, 'value': value, 'selected': itemSelected};
}

function json_getOptionAtt2(exp){
        //si il y a le signe egal, recuperer la valeur sinon utiliser options[h]
        var label = '';
        var value = '';
        var itemArr = exp.split('=');
        
        if (itemArr. length > 1){
            label = itemArr[0];
            value = itemArr[1];
        }else{
            label = exp;
            value = exp;
        }
        return {'label':label, 'value': value};
}

function json_toString(inputArr){
  
    var value = null;
    var htmlArr = [];
    
    for(var attKey in inputArr)
    {
      var attribut = inputArr[attKey];
      htmlArr.push(`${attribut.name} : ${attribut.value}`);
    }
    return htmlArr.join("\n");
}

/* ******************************************* */
/*     Events                     */
/* ******************************************* */
function json_submit(ev, idSource){
    json_update(idSource);
    
    //destruction du form
    json_close(ev, idSource);
    //alert('json_submit');
}

/* ******************************************* */
/*     Events                     */
/* ******************************************* */
function json_update(idSource){
    var jsonForm = jsonFormArr[idSource];
console.log(`json_update : idSource = ${idSource}`)  ;  
    for(var attKey in jsonForm.inputArr)
    {
      var attribut = jsonForm.inputArr[attKey];
      var inpId = jsonForm.idSource + '-' + attribut.name;
    
      switch(attribut.type){
        case 'radio':   obInp = json_getInpRadio(jsonForm.idSource, attribut);     
    //alert(`json_update : attKey = ${attKey}\n type = ${attribut.type} \n value = ${attribut.value}`);
            var selector = `input[name=${idSource}-${attribut.name}-radio]:checked`;
            var obRadioChecked = document.querySelectorAll(selector);
    // alert(`selector = ${selector}\n nb radio = ${obRadioChecked.length}`) ;
            //alert(obRadioChecked.length);
            if (obRadioChecked){
                attribut.value = obRadioChecked[0].value;
            }else{
                attribut.value = '';
            }
            //alert(obRadioChecked[0].value);
            break;
        
        case 'checkbox':   obInp = json_getInpRadio(jsonForm.idSource, attribut); 
            var selector = `input[name=${idSource}-${attribut.name}-checkbox]:checked`;
            var obChecked = document.querySelectorAll(selector); 
            if (obChecked){
                var itemsChecked = [];
                for(var h=0; h < obChecked.length; h++){
                    itemsChecked.push(obChecked[h].value);
                }
                attribut.value = itemsChecked.join(',');
            }else{
                attribut.value = '';
            }
            //alert(obChecked.length);
            //alert(obChecked[0].value);
            break;
        
        case 'palette': 
        case 'number':     
        case 'color':     
        case 'list':     
        default:
        case 'textbox':  
        console.log(`inpId = ${inpId}`);
          obInp = document.getElementById(inpId);
          attribut.value = obInp.value;
      }
    
    
    
    }
console.log(jsonForm.obSource.value );
    jsonForm.obSource.value = JSON.stringify(jsonForm.inputArr);
    //alert(jsonForm.obSource.value);
    if(document.getElementById(jsonForm.idSource + '-preview')){
        document.getElementById(jsonForm.idSource + '-preview').value = json_toString(jsonForm.inputArr); 
    }
    json_showPreview(jsonForm.idSource);
}

/* ******************************************* */
/*     Events                     */
/* ******************************************* */
function json_showPreview(idSource){
    previewId = idSource + '-preview';
    var obPreview = document.getElementById(idSource + '-preview');
    if(!obPreview) return false;
    
    var obSource = document.getElementById(idSource);

    var inputArr = JSON.parse(obSource.value);    
//alert('json_setPreview : ' + previewId + "\n" + obSource.value);
//alert('json_setPreview : ' + previewId + "\n" + inputArr['height']['value']);

    document.getElementById(previewId).value = json_toString(inputArr); 
}

/* ******************************************* */
/*     Events                     */
/* ******************************************* */
function json_close(ev, idSource){
    //destruction du form
    jsonForm = jsonFormArr[idSource];
    
    jsonForm.obSource.parentNode.removeChild(document.getElementById(jsonForm.idSource + '-mask'));
    jsonForm.obSource.parentNode.removeChild(document.getElementById(jsonForm.idSource + '-main'));
    document.onkeydown = '';
    //alert('json_submit');
}

/* ******************************************* */
/*     functions diverses                      */
/* ******************************************* */
function json_getId(exp){
      return allAtt.idSource + '-' + exp;
}
/* ***************************************** */
function json_arrToString(jsonArr){
 
    var htmlArr = [];
    for(var attKey in allAtt.inputArr)
    {
      var attribut = allAtt.inputArr[attKey];
      htmlArr.push(attKey + " = " + attribut['value']);

        for(var attParam in attribut)
        {
          var p = attribut[attParam];
          htmlArr.push('===>' + attParam + " = " + p);

        }
    }
    return htmlArr.join('<br>');
}
/* ***************************************
algorithme qui calcul la position absolue d'un div sur une page html 
**************************************** */
function json_getAbsolutePosition(element) {
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


