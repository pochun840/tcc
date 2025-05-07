
//Apply default values
function create_seq() {

    document.getElementById('newseq').style.display = 'block';
    document.getElementById('seq_tr').value = 1;
    document.getElementById('seq_ok').checked = true;
    document.getElementById('seq_k_val').value = 100;
    document.getElementById('seq_ok_stop_off').checked = true;
    document.getElementById('seq_ok').checked = true;
    document.getElementById('OPT_OFF').checked = true;
    document.getElementById('seq_ofs').value = 0;
    document.getElementById('seq_ns').selectedIndex = 0;
    
}

function copy_seq(seqid){
    
    document.getElementById('copyseq').style.display = 'block';   
    document.getElementById('from_seq_id').value =seqid;
    document.getElementById('from_seq_name').value =seqname;
}


function getSelectedValue(name, defaultValue = null) {
    var selectedOption = document.querySelector(`input[name="${name}"]:checked`);
    return selectedOption ? selectedOption.value : defaultValue;
}



function validateInput(element, pattern, min, max) {
    let value = element.value.trim();
    let isValid = true;

    // 验证空值
    if (value === "") {
        element.classList.add("is-invalid");
        isValid = false;
    }
    // 验证正则
    else if (!pattern.test(value)) {
        element.classList.add("is-invalid");
        isValid = false;
    }
    // 验证最小值
    else if (min !== null && parseFloat(value) < min) {
        element.classList.add("is-invalid");
        isValid = false;
    }
    // 验证最大值
    else if (max !== null && parseFloat(value) > max) {
        element.classList.add("is-invalid");
        isValid = false;
    }
    // 通过验证
    else {
        element.classList.remove("is-invalid");
    }

    return isValid;
}

function goBackAndReload() {
    const currentUrl = window.location.href;
    window.history.replaceState({}, '', currentUrl);
    window.history.back();
    setTimeout(() => {
        window.location.href = document.referrer + (document.referrer.includes('?') ? '&' : '?') + 'refresh=' + new Date().getTime();
    }, 100);
}


function setRadioButton_value(radioButtons, value) {
    radioButtons.forEach(function(button) {
        if (button.value === value.toString()) {
            button.checked = true;
        } else {
            button.checked = false;
        }
    });
}