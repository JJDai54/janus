function XoopsFormTextPlus_clear(event, xfId){
    document.getElementById(xfId).value = '';
    document.getElementById(xfId).focus();
}

function XoopsFormTextPlus_setValue(event, xfId, exp){
//console.log(`XoopsFormTextPlus_setValue : exp = ${exp}`);
    document.getElementById(xfId).value = exp;
    document.getElementById(xfId).focus();
}

function XoopsFormTextPlus_setValueTxt(ev, xfId){
//console.log(`XoopsFormTextPlus_setValueTxt : exp = ${ev.currentTarget.value}`);
    document.getElementById(xfId).value = ev.currentTarget.value;
    document.getElementById(xfId).focus();
    ev.currentTarget.value = '';
}
