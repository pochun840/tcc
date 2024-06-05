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

function setLocalStorage(key, value) {
    localStorage.setItem(key, value);
}


function highlight_row(tableId)
{
    var table = document.getElementById(tableId);
    var rows = table.getElementsByTagName('tr');

    for (var i = 1; i < rows.length; i++) {
        rows[i].onclick = function () {
            for (var j = 1; j < rows.length; j++) {
                rows[j].classList.remove('selected');
            }
            this.classList.add('selected');
        }
    }
}