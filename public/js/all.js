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


function MoveUp(button) {
    var row = button.parentNode.parentNode;
    var prevRow = row.previousElementSibling;
    if (prevRow) {
        swap_row(row, prevRow);
       
        var index1 = Array.from(row.parentNode.children).indexOf(row);
        var index2 = Array.from(row.parentNode.children).indexOf(prevRow);
        var temp = rowInfoArray[index1];
        rowInfoArray[index1] = rowInfoArray[index2];
        rowInfoArray[index2] = temp;

        console.log(rowInfoArray);
        sendRowInfoArray();
    }
}

function MoveDown(button) {
    var row = button.parentNode.parentNode;
    var nextRow = row.nextElementSibling;
    if (nextRow) {
        swap_row(nextRow, row);
     
        var index1 = Array.from(row.parentNode.children).indexOf(row);
        var index2 = Array.from(row.parentNode.children).indexOf(nextRow);
        var temp = rowInfoArray[index1];
        rowInfoArray[index1] = rowInfoArray[index2];
        rowInfoArray[index2] = temp;

        console.log(rowInfoArray);
        sendRowInfoArray();
    }
}


function swap_row(row1, row2) {
    var parent = row1.parentNode;
    var nextSibling = row2.nextSibling;
    parent.insertBefore(row2, row1);
    parent.insertBefore(row1, nextSibling);
}



function removeElements(elementIds) {
    elementIds.forEach(function(id) {
        var element = document.getElementById(id);
        if (element) {
            element.parentNode.removeChild(element);
        }
    });
}