function formcheckboximage_onclick(ev, imgPrefix, switchImage){
    //alert(ev.currentTarget.id);
    var obSource = ev.currentTarget;
    var sourceId = obSource.id;
    var h = sourceId.indexOf('-');
    var idXF = sourceId.substring(h+1);
    var obCheckbox = document.getElementById(idXF);
    console.log("===>idXF = " + idXF);
    
    obCheckbox.checked = !obCheckbox.checked ;
    if (obCheckbox.checked){
        var imgIndex = ((switchImage) ? 0 : 1);
    }else{
        var imgIndex = ((switchImage) ? 1 : 0);
    }
    
   
    var obImg = document.getElementById('chkimg-' + idXF);
    var src = obImg.getAttribute('src');
    console.log("===>src = " + src);
    var h = src.lastIndexOf('/');
    var newSrc = src.substring(0,h+1) + imgPrefix + imgIndex + '.png';
    obImg.setAttribute('src', newSrc);

    //alert(`formcheckboximage : ${imgId}\n nextValue = ${obValue.value}\nnexImg = ${nextImg}\nnewSource = ${newSrc}`);
}

function formcheckboximage_onclick_old(ev, imgPrefix){
    var modulo = 2; //en prevision d'un troisieme etat neutre
    
    var obImg = ev.currentTarget;
    
    var imgId = obImg.id;
    var h = imgId.indexOf('-');
    var idXF = imgId.substring(h+1);
    var obValue = document.getElementById(idXF);
    
    obValue.value = (obValue.value*1 + 1) % modulo;
    //alert(`formcheckboximage : \nvalue ${obValue.value}\nnextValue =   ${nextValue}`);
    
    var src = obImg.getAttribute('src');
    var h = src.lastIndexOf('/');
    var newSrc = src.substring(0,h+1) + imgPrefix + obValue.value + '.png';
    obImg.setAttribute('src', newSrc);

    //alert(`formcheckboximage : ${imgId}\n nextValue = ${obValue.value}\nnexImg = ${nextImg}\nnewSource = ${newSrc}`);
}