/*
formDuration
 */
 

function formDuration_updateDuration(ev, name){
   
    var seconds = 0;
    var updateCompteurs = false; // mise à jour des compteurs en cas de débordement des max
    
    var obInpD = document.getElementById(name + '-d');
    if (obInpD) {
        seconds += (obInpD.value * 1) * (60 * 60 * 24);
        //if(obInpD.value == 30 ||  obInpD.value == 0) updateCompteurs = true;
        if(obInpD.value == 30) updateCompteurs = true;
    }
    
    var obInpH = document.getElementById(name + '-h');
    if (obInpH) {
        seconds += (obInpH.value * 1) * (60 * 60);
        //if(obInpH.value == 24 ||  obInpH.value == 0) updateCompteurs = true;
        if(obInpH.value == 24) updateCompteurs = true;
    }
    
    var obInpM = document.getElementById(name + '-m');
    if (obInpM) {
        seconds += (obInpM.value * 1) * 60;
        //if(obInpM.value == 60 ||  obInpM.value == 0) updateCompteurs = true;
        if(obInpM.value == 60) updateCompteurs = true;
    }
    
    var obInpS = document.getElementById(name + '-s');
    if (obInpS) {
        seconds += (obInpS.value * 1);
        if(obInpS.value == 60) updateCompteurs = true;
    }
    
    
    if(updateCompteurs){
    console.log('===>setDuration : recalcul des compteurs');
        formDuration_updateCompteurs(ev, name, seconds);
//         if (obInpS) obInpS.value = seconds % 60;
//         if (obInpM) obInpM.value = Math.floor((seconds / 60) % 60);
//         if (obInpH) obInpH.value = Math.floor((seconds / 3600) % 24);
//         if (obInpD) obInpD.value = Math.floor(seconds / (3600*24));
        

    }else{
        document.getElementById(name).value = seconds;
    }
//    console.log('===>setDuration : resultat : seconds = ' +   document.getElementById(name).value);
    ev.stopPropagation();
}

function formDuration_updateCompteurs(ev, name, seconds=0){
    console.log('===>formDuration_updateCompteurs : recalcul des compteurs' + '-name=' + name);
    
    var obInpD = document.getElementById(name + '-d');
    if (obInpD) obInpD.value = Math.floor(seconds / (3600*24));

    var obInpH = document.getElementById(name + '-h');
    if (obInpH) obInpH.value = Math.floor((seconds / 3600) % 24);

    var obInpM = document.getElementById(name + '-m');
    if (obInpM) obInpM.value = Math.floor((seconds / 60) % 60);

    var obInpS = document.getElementById(name + '-s');
    if (obInpS) obInpS.value = seconds % 60;

    document.getElementById(name).value = seconds;
    ev.stopPropagation();

}

