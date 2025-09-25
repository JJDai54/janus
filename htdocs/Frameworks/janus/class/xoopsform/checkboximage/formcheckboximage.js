function formcheckboximage_onclick(ev, imgPrefix, switchImage, img0 = 0, img1 = 1){
    //alert(ev.currentTarget.id);
    //alert("img : " + img0 + '|' + img1);
    var obSource = ev.currentTarget;
    var sourceId = obSource.id;
    var h = sourceId.indexOf('-');
    var idXF = sourceId.substring(h+1);
    var obCheckbox = document.getElementById(idXF);
    console.log("===>idXF = " + idXF);
    
    obCheckbox.checked = !obCheckbox.checked ;
    if (obCheckbox.checked){
        var imgIndex = ((switchImage) ? img0 : img1);
    }else{
        var imgIndex = ((switchImage) ? img1 : img0);
    }
    
   
    var obImg = document.getElementById('chkimg-' + idXF);
    var src = obImg.getAttribute('src');
    console.log("===>src = " + src);
    var h = src.lastIndexOf('/');
    var newSrc = src.substring(0,h+1) + imgPrefix + imgIndex + '.png';
    obImg.setAttribute('src', newSrc);

    //alert(`formcheckboximage : ${imgId}\n nextValue = ${obValue.value}\nnexImg = ${nextImg}\nnewSource = ${newSrc}`);
}
