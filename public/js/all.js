function hideElementById(elementId) {
    var element = document.getElementById(elementId);
    if (element){
        element.style.display = 'none';
    }
}

//讀取localstorge
function readFromLocalStorage(key) {
    return localStorage.getItem(key);
}

function setRadioButtonValue(radioButtons, value) {
    for (var i = 0; i < radioButtons.length; i++) {
        if (radioButtons[i].value === value) {
            radioButtons[i].checked = true;
            break;
        }
    }
}
