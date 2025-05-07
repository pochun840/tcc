function getSelectedSeqRowData() {
    const row = document.querySelector('#seq_table .selected');
    if (!row) return null;
    return {
        id: row.cells[0]?.innerText,
        name: row.cells[1]?.innerText
    };
}

function getSelectedValue(name, defaultValue = null) {
    const selectedOption = document.querySelector(`input[name="${name}"]:checked`);
    return selectedOption ? selectedOption.value : defaultValue;
}


function cound_job(action) {
    const rowData = getSelectedSeqRowData();

    if (action !== 'new' && (!rowData || !rowData.id)) {
        console.warn("No sequence selected.");
        return;
    }

    if (rowData) {
        window.seqid = rowData.id;
        window.seqname = rowData.name;
    }

    document.querySelector(".main-content").classList.add("overlay-active");

    if (action === 'new') return create_seq();
    if (action === 'edit') return edit_seq(seqid);
    if (action === 'del') return delete_seqid(seqid);
    if (action === 'copy') return copy_seq(seqid);

    console.warn("Unknown action:", action);
}

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

function validateInput(element, pattern, min, max) {
    const value = element.value.trim();
    const feedback = element.nextElementSibling;
    const lang = getCookie('language') || 'en-us';

    const messages = {
        empty: {
            "zh-tw": "此欄位不可空白",
            "zh-cn": "此字段不能为空",
            "en-us": "This field is required"
        },
        format: {
            "zh-tw": "格式錯誤",
            "zh-cn": "格式错误",
            "en-us": "Invalid format"
        },
        range: {
            "zh-tw": `範圍：${min} ~ ${max}`,
            "zh-cn": `范围：${min} ~ ${max}`,
            "en-us": `Range: ${min} ~ ${max}`
        }
    };

    let isValid = true;

    if (value === "") {
        isValid = false;
        if (feedback) feedback.innerHTML = messages.empty[lang];
    } else if (!pattern.test(value)) {
        isValid = false;
        if (feedback) feedback.innerHTML = messages.format[lang];
    } else if (min !== null && parseFloat(value) < min || max !== null && parseFloat(value) > max) {
        isValid = false;
        if (feedback) feedback.innerHTML = messages.range[lang];
    }

    element.classList.toggle("is-invalid", !isValid);
    return isValid;
}

function copy_seq(seqid){
    
    document.getElementById('copyseq').style.display = 'block';   
    document.getElementById('from_seq_id').value =seqid;
    document.getElementById('from_seq_name').value =seqname;
}


function goBackAndReload() {
    const currentUrl = window.location.href;
    window.history.replaceState({}, '', currentUrl);
    window.history.back();
    setTimeout(() => {
        window.location.href = document.referrer + (document.referrer.includes('?') ? '&' : '?') + 'refresh=' + new Date().getTime();
    }, 100);
}


function updateValue(element) {
    const jobid = document.getElementById('current_job_id').value;
    const seq_en = element.checked ? 1 : 0;
    const seqid = element.getAttribute('data-sequence-id');

    if (seqid) {
        $.ajax({
            url: "?url=Sequences/check_seq_enable", 
            method: "POST",
            data: { 
                jobid: jobid,
                seqid: seqid,
                seq_en: seq_en
            },
            success: function(response) {
                console.log(response);
                history.go(0);
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error); 
            }
        });
    }
}


function delete_seqid(seqid) {
    const jobid = document.getElementById('current_job_id').value;

    if (!jobid) return;

    const language = getCookie('language');
    const confirmTexts = {
        "zh-cn": { title: "Copy Job", message: "你确定吗？" },
        "zh-tw": { title: "Copy Job", message: "你確定嗎 ?" },
        "default": { title: "Copy Job", message: "Are you sure ?" }
    };
    const text = confirmTexts[language] || confirmTexts["default"];

    alertify.confirm(text.message, function (result) {
        if (!result) return;

        document.getElementById('spinner').style.display = 'block';

        $.ajax({
            url: "?url=Sequences/delete_seq",
            method: "POST",
            data: {
                jobid: jobid,
                seqid: seqid
            },
            success: function (response) {
                const responseData = JSON.parse(response);

                setTimeout(() => {
                    document.getElementById('spinner').style.display = 'none';

                    alertify.alert(responseData.res_type, responseData.res_msg, () => {
                        history.go(0);
                    });

                    setTimeout(() => {
                        alertify.closeAll();
                        history.go(0);
                    }, 3000);
                }, 1000);
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    });
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
