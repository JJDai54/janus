function XoopsFormTextPlus_clear(event, xfId){
    document.getElementById(xfId).value = '';
    document.getElementById(xfId).focus();
}

function XoopsFormTextPlus_setValue(event, xfId, exp){
    document.getElementById(xfId).value = exp;
    document.getElementById(xfId).focus();
}

function XoopsFormTextPlus_setValueTxt(ev, xfId){
//alert(ev.currentTarget.id + '===>' + ev.currentTarget.value);
    document.getElementById(xfId).value = ev.currentTarget.value;
    document.getElementById(xfId).focus();
    ev.currentTarget.value = '';
}
