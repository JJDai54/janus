var lib_show = "show";
var lib_hide = "hide";
var icoShow = "plus.png";
var icoHide = "moins.png";

$(document).ready(function(){
//alert("show_hide");
   $('.show_hide').showHide({
		speed: 500,  // speed you want the toggle to happen
		easing: '',  // the animation effect you want. Remove this line if you dont want an effect and if you haven't included jQuery UI
		changeText: 0, // if you dont want the button text to change, set this to 0

		showText:  `${lib_show} <img src="${icoShow}" width="16px" height="16px" alt="" />`,// the button text to show when a div is closed
		hideText:  `${lib_hide} <img src="${icoHide}"  width="16px" height="16px" alt="" />` // the button text to show when a div is open
		//showText: '<{$smarty.const._CC_MED_AFFICHER}> <img src="<{$smarty.const.JJD_ICO32}>plus.png" width="16px" height="16px" alt="" />',// the button text to show when a div is closed
		//hideText: '<{$smarty.const._CC_MED_CACHER}> <img src="<{$smarty.const.JJD_ICO32}>moins.png"  width="16px" height="16px" alt="" />' // the button text to show when a div is open
	});

}); 
