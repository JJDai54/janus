/* ******************************************* */
/*     creation des inputs                     */
/* ******************************************* */

function json_getBtnSubmit(prefix){
    var name = '';
    var id   = prefix + '-submit';
    var submitcaption = document.getElementById(prefix + '-submitCaption').value;
    var CancelCaption = document.getElementById(prefix + '-cancelCaption').value;
    var onclickSubmit = `onclick='json_submit(event, \"${prefix}\")'`;
    var onclickCancel = `onclick='json_close(event, \"${prefix}\")'`;
    var html = `<center>`
             + `<input type="button" name="${name}"  title="" value="${CancelCaption}" ${onclickCancel}>`
             + `<input type="button" name="${name}"  title="" value="${submitcaption}" ${onclickSubmit}></center>`;
    return html;
}


function json_getInpTextbox(prefix, attribut, preview = false){
    if(preview){
    }else{
    }
    var name = prefix + '-all[]';
    var id   = prefix + '-' + attribut.name;
    var html = `<input type="text" name="${name}"  id="${id}" title="" size="50" maxlength="50" value="${attribut.value}">`;
    return html;
}

function json_getInpNumber(prefix, attribut, preview = false){
    if(preview){
    }else{
    }
    var name = prefix + '-all[]';
    var step = (attribut.unit == 'em') ? 0.1 : 1;
    var id   = prefix + '-' + attribut.name;
console.log(`json_getInpNumber : id = ${id}`);
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
function json_getInpColor(prefix, attribut, preview = false){
    var style='pading:0px;margin:0px;width:32px;';
    
    if(preview){
        var html = `<input type="button"`
                 + ` value="${attribut.value}" style="${style}">`;
    }else{
        var name = prefix + '-all[]';
        var id   = prefix + '-' + attribut.name;
        var html = `<input type="color" name="${name}" id="${id}"`
                 + ` value="${attribut.value}" style="{style}">`;
    }
    return html;

}

function json_getInpPalette(prefix, attribut, preview = false){
    if(preview){
    }else{
    }
    var name = prefix + '-all[]';
    var id   = prefix + '-' + attribut.name;
        var btnName  = id + '-button';
    
    var style=`pading:0px;margin:0px;background:${attribut.value};width:80px;height:24px;`;
    //var onClick = `json_showPicker(event,'${json_getId('picker')}',1)`;
    var palette= (attribut.palette) ? attribut.palette : '';
    var onClick = `palette_showPicker(event, '${attribut.palette}')`;



    
    var html = `<input type="button" name="${btnName}" id="${btnName}" xformId="${id}"`
             + ` value="${attribut.value}" style="${style}" onclick="${onClick}">`;

    html += `<input type='hidden' name='${id}' id='${id}' value='${attribut.value}' onclick='json_update(\"${prefix}\")' >`;

             
    return html;

}

function json_getInpList(prefix, attribut, preview = false){
    if(preview){
    }else{
    }
    var name = prefix + '-all[]';
    var id   = prefix + '-' + attribut.name;
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
function json_getInpRadio(prefix, attribut, preview = false){
    //alert(`json_getInpRadio : attribut = ${attribut.options}`);
    if(preview){
    }else{
    }
    var name = prefix + '-' + attribut.name + '-radio';
    var itemSelected = '';
    var label = '';
    var value= '';
    
    //$style='pading:0px;margin:0px;width:32px';
    var options = attribut.options.split(',');
    var html = ``;
    
    for (var h=0; h < options.length; h++){
        //si il y a le signe egal, recuperer la valeur sinon utiliser options[h]
        var itemArr = json_getOptionAtt(options[h],attribut.value, attribut.type);
        //alert(`{itemArr.value} === ${attribut.value}`)
        var id   = prefix + '-' + attribut.name + '-' + h;
        html += `<input type="radio" id="${id}" name="${name}" value="${itemArr.value}" ${itemArr.selected} />`;
        html += `<label for="${id}">${itemArr.label}</label>`
    }

    return html
   
}


function json_getInpCheckbox(prefix, attribut, preview = false){
    var name = prefix + '-' + attribut.name + '-checkbox';
    var itemSelected = '';
    //$style='pading:0px;margin:0px;width:32px';
    var options = attribut.options.split(',');
    
    var html = ``;
    var itemsSelected = attribut.value.split(',');
    
    
    for (var h=0; h < options.length; h++){
        var itemArr = json_getOptionAtt(options[h],attribut.value, attribut.type);
        var id   = prefix + '-' + attribut.name + '-' + h;
        html += `<input type="checkbox" id="${id}" name="${name}" value="${itemArr.value}" ${itemArr.selected} />`;
        html += `<label for="${id}">${itemArr.label}</label>`
    }

    return html
   
}

function json_getInpHidden(prefix, attribut, preview = false){
    if(preview){
    }else{
    }
    var name = prefix + '-all[]';
    var id   = prefix + '-' + attribut.name;
    var html = `<input type="hidden" name="${name}"  id="${id}" value="${attribut.value}">`;
    return html;
}

/////////////////////////////////////////////////////////////////