function hideElementById(elementId) {
    var element = document.getElementById(elementId);
    if (element){
        element.style.display = 'none';
    }
}

function readFromLocalStorage(key) {
    return localStorage.getItem(key);
}