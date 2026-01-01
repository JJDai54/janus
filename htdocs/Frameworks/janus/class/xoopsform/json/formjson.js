
const allAtt = {
    inputArr : null,
    idSource : '',
    obSource : null,
    version : "1.00 beta 1",
    zindex : 20000
}   
 
function json_getForm(ev, idSource){
//alert("formStyleCss : ");
    allAtt.idSource = idSource;
    var obSource = document.getElementById(idSource);
    allAtt.obSource = obSource;
    allAtt.inputArr = JSON.parse(obSource.value);    
    
    
    obSource.parentNode.appendChild(json_getMask());
    var obForm = json_getFormContent(ev.currentTarget);
    obSource.parentNode.appendChild(obForm);
    //obSource.parentNode.appendChild(json_getHtmlPicker());

     
    //obForm.innerHTML = "formStyleCss : " + obSource.id + "<br>===>" + obSource.value;
    
    //obForm.innerHTML = "formStyleCss : " + obSource.id + "<br>===>" + obSource.value + "<br>" +  json_arrToString(obSource.value);
    //obForm.innerHTML = "formStyleCss : " + obSource.id + "<br>===>" + obSource.value + "<br>" +  json_buildForm();
    obForm.innerHTML = json_buildForm();

//alert("formStyleCss : " + obSource.id);
//return obForm.innerHTML ;
document.onkeydown = json_applyKey;

}

/* ******************************************* */
/*     Events                     */
/* ******************************************* */
function json_showInSitu(idSource, width=300){
//alert("json_showInSitu : " + idSource);
    allAtt.idSource = idSource;
    var obSource = document.getElementById(idSource);
//alert(obSource.value);
    allAtt.obSource = obSource;
    allAtt.inputArr = JSON.parse(obSource.value);    
    var obForm = json_getFormContent(obSource, false, width);
    obForm.innerHTML = json_buildForm(false);
    //obSource.parentNode.appendChild(obForm);
    //obSource.parentNode.appendChild(json_getHtmlPicker());

     

    var obInSitu = document.getElementById('formInSitu');
    obInSitu.appendChild(obForm);
    //obSource.innerHTML = json_buildForm();
    //alert(obForm.innerHTML);
    //obInSitu.innerHTML = "zzz" + obForm.innerHTML;
    json_update();

}

/* ******************************************* */
/*     creation du mask et du div principal    */
/* ******************************************* */



function json_getFormContent(currentTarget, isForm = true, width=300){
//alert("formStyleCss : ");
    var obDiv = document.createElement('div');
    
    if(isForm){
    obDiv.id = allAtt.idSource + '-main';
        //position = json_getAbsolutePosition(allAtt.idSource + '-btn');
        position = json_getAbsolutePosition(currentTarget);
            
        obDiv.style.position = 'absolute';
        obDiv.classList.add('formJson_form');

        obDiv.style.left = position.x + "px";
        obDiv.style.top =  position.y + "px";

    //     obDiv.style.left = (position.x + 100) + "px";
    //     obDiv.style.top =  (position.y + allAtt.obSource.offsetHeight) + "px";
        obDiv.style.zIndex = allAtt.zindex + 10;
    }else{
        obDiv.classList.add('formJson_form');
        obDiv.style.width = width  + "px";
        addEventListener("change", function(){ test("Hello World!"); }); 
    //     obDiv.style.left = (position.x + 100) + "px";
    //     obDiv.style.top =  (position.y + allAtt.obSource.offsetHeight) + "px";

    }
    
    

    return obDiv;

}
function test(exp = 'test'){
    //alert(exp);
    json_update();
}

function json_getMask(){
    var obDiv = document.createElement('div');
    obDiv.id = allAtt.idSource + '-mask';
    obDiv.classList.add('formJson_mask');
    obDiv.style.zIndex = allAtt.zindex;
    
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
    json_close(_event_);
  }
}

function json_buildForm(isForm = true){
  
    var obInp = null;
    var htmlArr = [];
    htmlArr.push("<table>");
    
    for(var attKey in allAtt.inputArr)
    {
      var attribut = allAtt.inputArr[attKey];
      switch(attribut.type){
        case 'number':   obInp = json_getInpNumber(attribut);    break;
        case 'color':    obInp = json_getInpColor(attribut);     break;
        case 'palette':  obInp = json_getInpPalette(attribut);   break;
        case 'list':     obInp = json_getInpList(attribut);      break;
        case 'radio':    obInp = json_getInpRadio(attribut);     break;
        case 'checkbox': obInp = json_getInpCheckbox(attribut);  break;
        default:
        case 'textbox': obInp = json_getInpTextbox(attribut);   break;
      }
      //$caption = (attribut['_caption_']) ? attribut['_caption_'] : attribut.name;      
      var caption = (attribut._caption_) ? attribut._caption_ : attribut.name;
      htmlArr.push(`<tr><td  style='text-align:right;padding-right:8px;'>${caption} :</td><td style='text-align:left;'>${obInp}</td></tr>`);
      
    }
    htmlArr.push('</table>');
    if(isForm){
        htmlArr.push(json_getBtnSubmit());
    }
    return htmlArr.join("\n");
}

/* ******************************************* */
/*     creation des inputs                     */
/* ******************************************* */

function json_getBtnSubmit(){
    var name = '';
    var id   = allAtt.idSource + '-submit';
    var submitcaption = document.getElementById(allAtt.idSource + '-submitCaption').value;
    var CancelCaption = document.getElementById(allAtt.idSource + '-cancelCaption').value;
    var onclickSubmit = "onclick='json_submit(event)'";
    var onclickCancel = "onclick='json_close(event)'";
    var html = `<center>`
             + `<input type="button" name="${name}"  title="" value="${CancelCaption}" ${onclickCancel}>`
             + `<input type="button" name="${name}"  title="" value="${submitcaption}" ${onclickSubmit}></center>`;
    return html;
}


function json_getInpTextbox(attribut, preview = false){
    if(preview){
    }else{
    }
    var name = allAtt.idSource + '-all[]';
    var id   = allAtt.idSource + '-' + attribut.name;
    var html = `<input type="text" name="${name}"  id="${id}" title="" size="50" maxlength="50" value="${attribut.value}">`;
    return html;
}

function json_getInpNumber(attribut, preview = false){
    if(preview){
    }else{
    }
    var name = allAtt.idSource + '-all[]';
    var step = (attribut.unit == 'em') ? 0.1 : 1;
    var id   = allAtt.idSource + '-' + attribut.name;
    if(attribut.value < attribut.min){
            attribut.value = attribut.min;
    }else if(attribut.value > attribut.max){
            attribut.value = attribut.max;
    }
    var html = `<input type="number" name="${name}" id="${id}"`
             + ` title="" size="${attribut.size}" maxlength="${attribut.size}" value="${attribut.value}"`
             + ` step="${step}" min="${attribut.min}" max="${attribut.max}" style="text-align:right;background:#FFCC99;">`
             + ` ${attribut.unit}`;
    return html;
    
    //<input type="number" name="quest_timer" title="" id="quest_timer" size="8" maxlength="8" value="0"">
}
/*
    if(preview){
    }else{
    }
*/
function json_getInpColor(attribut, preview = false){
    var style='pading:0px;margin:0px;width:32px;';
    
    if(preview){
        var html = `<input type="button"`
                 + ` value="${attribut.value}" style="${style}">`;
    }else{
    var name = allAtt.idSource + '-all[]';
        var id   = allAtt.idSource + '-' + attribut.name;
        var html = `<input type="color" name="${name}" id="${id}"`
                 + ` value="${attribut.value}" style="{style}">`;
    }
    return html;

}
function json_getInpPalette(attribut, preview = false){
    if(preview){
    }else{
    }
    var name = allAtt.idSource + '-all[]';
    var id   = allAtt.idSource + '-' + attribut.name;
        var btnName  = id + '-button';
    
    var style=`pading:0px;margin:0px;background:${attribut.value};width:80px;height:24px;`;
    //var onClick = `json_showPicker(event,'${json_getId('picker')}',1)`;
    var palette= (attribut.palette) ? attribut.palette : '';
    var onClick = `palette_showPicker(event, '${attribut.palette}')`;



    
    var html = `<input type="button" name="${btnName}" id="${btnName}" xformId="${id}"`
             + ` value="${attribut.value}" style="${style}" onclick="${onClick}">`;

    html += `<input type='hidden' name='${id}' id='${id}' value='${attribut.value}' onclick='json_update()' >`;

             
    return html;

}

function json_getInpList(attribut, preview = false){
    if(preview){
    }else{
    }
    var name = allAtt.idSource + '-all[]';
    var id   = allAtt.idSource + '-' + attribut.name;
    var itemSelected = '';
    //$style='pading:0px;margin:0px;width:32px';
    var options = attribut.options.split(',');
    //alert(attribut.options);
    var html = `<select name="${name}" id="${id}">`;
    for (var h=0; h < options.length; h++){
        itemSelected = (options[h] == attribut.value) ? 'selected' : '';
        html += `<option value="${options[h]}" ${itemSelected}>${options[h]}</option>`;
    }

    html += `</select>`;
    return html
   
}
function json_getInpRadio(attribut, preview = false){
    if(preview){
    }else{
    }
    var name = attribut.name + '-radio';
    var itemSelected = '';
    //$style='pading:0px;margin:0px;width:32px';
    var options = attribut.options.split(',');
    var html = ``;
    
    for (var h=0; h < options.length; h++){
        itemSelected = (options[h] == attribut.value) ? 'checked' : '';
        var id   = attribut.name + '-' + h;
        html += `<input type="radio" id="${id}" name="${name}" value="${options[h]}" ${itemSelected} />`;
        html += `<label for="${id}">${options[h]}</label>`
    }

    return html
   
}
function json_getInpCheckbox(attribut, preview = false){
    var name = attribut.name + '-checkbox';
    var itemSelected = '';
    //$style='pading:0px;margin:0px;width:32px';
    var options = attribut.options.split(',');
    
    var html = ``;
    var itemsSelected = attribut.value.split(',');
    
    
    for (var h=0; h < options.length; h++){
        itemSelected = (itemsSelected.indexOf(options[h]) >= 0) ? 'checked' : '';
        var id   = attribut.name + '-' + h;
        html += `<input type="checkbox" id="${id}" name="${name}" value="${options[h]}" ${itemSelected} />`;
        html += `<label for="${id}">${options[h]}</label>`
    }

    return html
   
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
function json_submit(ev){
    json_update();
    
    //destruction du form
    json_close(ev);
    //alert('json_submit');
}

/* ******************************************* */
/*     Events                     */
// document.querySelectorAll(".checkbox"); 
//     var selector = `${balise}[name=${name}]`;
/* ******************************************* */
function json_update(){

    for(var attKey in allAtt.inputArr)
    {
      var attribut = allAtt.inputArr[attKey];
      var inpId = allAtt.idSource + '-' + attribut.name;
    
      switch(attribut.type){
        case 'radio':   obInp = json_getInpRadio(attribut);     
        var obRadioChecked = document.querySelectorAll(`input[name=${attribut.name}-radio]:checked`); 
        //alert(obRadioChecked.length);
        if (obRadioChecked){
            attribut.value = obRadioChecked[0].value;
        }else{
            attribut.value = '';
        }
        //alert(obRadioChecked[0].value);
        break;
        
        case 'checkbox':   obInp = json_getInpRadio(attribut);     
        var obChecked = document.querySelectorAll(`input[name=${attribut.name}-checkbox]:checked`); 
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
      obInp = document.getElementById(inpId);
      attribut.value = obInp.value;
      }
    
    
    
    }

    allAtt.obSource.value = JSON.stringify(allAtt.inputArr);
    //alert(allAtt.obSource.value);
    if(document.getElementById(allAtt.idSource + '-preview')){
        document.getElementById(allAtt.idSource + '-preview').value = json_toString(allAtt.inputArr); 
    }
    json_showPreview(allAtt.idSource);
}

/* ******************************************* */
/*     Events                     */
/* ******************************************* */
function json_update2(){

    for(var attKey in allAtt.inputArr)
    {

      var attribut = allAtt.inputArr[attKey];
      var inpId = allAtt.idSource + '-' + attribut.name;
      obInp = document.getElementById(inpId);
      attribut.value = obInp.value;
    }

    allAtt.obSource.value = JSON.stringify(allAtt.inputArr);
    //alert(allAtt.obSource.value);
    if(document.getElementById(allAtt.idSource + '-preview')){
        document.getElementById(allAtt.idSource + '-preview').value = json_toString(allAtt.inputArr); 
    }
    json_showPreview(allAtt.idSource);
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
function json_close(ev){
    //destruction du form
    allAtt.obSource.parentNode.removeChild(document.getElementById(allAtt.idSource + '-mask'));
    allAtt.obSource.parentNode.removeChild(document.getElementById(allAtt.idSource + '-main'));
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


