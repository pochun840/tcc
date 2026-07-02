let currentBarcodeOriginalJob = '';

function normalizeBarcodeScannerValue(value) {
    // QRCode 掃描時統一處理：
    // 1. 單引號強制轉成雙引號
    // 2. 取消英文大小寫互換，英文字母維持原樣
    return String(value || '')
        .replace(/['\u2018\u2019\u201B\uFF07]/g, '"');
}

function barcodeEditableTextToRaw(value) {
    let text = String(value || '');

    // 讓不可見控制字元也可以用可讀 token 輸入：
    // \t / <TAB> / [TAB]  -> TAB
    // \r / <CR>  / [CR]   -> CR
    // \n / <LF>  / [LF]   -> LF
    // \x1D / <GS> / [GS]  -> ASCII 29 (GS / FNC1 常見分隔符)
    const replacements = [
        [/\\r\\n/g, '\r\n'],
        [/<CRLF>/gi, '\r\n'],
        [/\[CRLF\]/gi, '\r\n'],

        [/\\t/g, '\t'],
        [/<TAB>/gi, '\t'],
        [/\[TAB\]/gi, '\t'],

        [/\\r/g, '\r'],
        [/<CR>/gi, '\r'],
        [/\[CR\]/gi, '\r'],

        [/\\n/g, '\n'],
        [/<LF>/gi, '\n'],
        [/\[LF\]/gi, '\n'],

        [/\\x1d/gi, String.fromCharCode(29)],
        [/<GS>/gi, String.fromCharCode(29)],
        [/\[GS\]/gi, String.fromCharCode(29)],
        [/<FNC1>/gi, String.fromCharCode(29)],
        [/\[FNC1\]/gi, String.fromCharCode(29)],

        [/\\x02/gi, String.fromCharCode(2)],
        [/<STX>/gi, String.fromCharCode(2)],
        [/\[STX\]/gi, String.fromCharCode(2)],

        [/\\x03/gi, String.fromCharCode(3)],
        [/<ETX>/gi, String.fromCharCode(3)],
        [/\[ETX\]/gi, String.fromCharCode(3)],

        [/\\x1b/gi, String.fromCharCode(27)],
        [/<ESC>/gi, String.fromCharCode(27)],
        [/\[ESC\]/gi, String.fromCharCode(27)]
    ];

    replacements.forEach(function (pair) {
        text = text.replace(pair[0], pair[1]);
    });

    return normalizeBarcodeScannerValue(text);
}

function barcodeRawToEditableText(value) {
    return normalizeBarcodeScannerValue(value)
        .replace(/\r\n/g, '<CRLF>')
        .replace(/\r/g, '<CR>')
        .replace(/\n/g, '<LF>')
        .replace(/\t/g, '<TAB>')
        .replace(new RegExp(String.fromCharCode(29), 'g'), '<GS>')
        .replace(new RegExp(String.fromCharCode(2), 'g'), '<STX>')
        .replace(new RegExp(String.fromCharCode(3), 'g'), '<ETX>')
        .replace(new RegExp(String.fromCharCode(27), 'g'), '<ESC>');
}

function barcodeRawLength(value) {
    return Array.from(String(value || '')).length;
}

function refreshBarcodeCount() {
    const barcodeInput = document.getElementById('barcode_content');
    const barcodeCount = document.getElementById('barcode_mask_count');
    if (!barcodeInput || !barcodeCount) return;

    const normalizedValue = normalizeBarcodeScannerValue(barcodeInput.value);
    if (barcodeInput.value !== normalizedValue) {
        barcodeInput.value = normalizedValue;
    }

    barcodeCount.value = barcodeRawLength(barcodeEditableTextToRaw(barcodeInput.value));
}



function cc_save(){


    var control_id = document.getElementById('control_id').value;
    if (isNaN(control_id) || control_id < 1 || control_id > 250) {
        return false;
    }

    var control_name = document.getElementById('control_name').value;
    var selectElement = document.getElementById('select_language');
    var selectedValue = selectElement.value;
    var batch_val = document.querySelector('input[name="batch-mode-option"]:checked').value;
    var buzzer_val = document.querySelector('input[name="buzzer-option"]:checked').value;
    var selectElement1 = document.getElementById('select_torque_unit');
    var torque_unit = selectElement1.value;

    if(control_id){
        document.querySelector(".main-content").classList.add("overlay-active");
        document.getElementById('spinner').style.display = 'block';

        $.ajax({
            url: "?url=Settings/control_setting",
            method: "POST",
            data:{ 
                control_id: control_id,
                control_name: control_name,
                lang_val: selectedValue,
                batch_val:batch_val,
                buzzer_val:buzzer_val,
                torque_unit:torque_unit

            },
            success: function(response) {
                var responseData = JSON.parse(response);
                alertify.alert(responseData.res_type, responseData.res_msg);
                
                setTimeout(function() {
                    alertify.closeAll(); 
                    document.getElementById('spinner').style.display = 'none';
                    document.querySelector(".main-content").classList.remove("overlay-active"); 
                }, 3000); 
            },
            
            error: function(xhr, status, error) {
                
            }
        });   
    }
}




function Firmware_Update() {
    var bb_file = document.getElementById("firmware-file-uploader").files[0];
    if (bb_file == undefined) {
        return;
    }

    var form = new FormData();
    form.append("file", bb_file);
    var url = '?url=Settings/FirmwareUpdate';

    $.ajax({
        type: "POST",
        processData: false,
        cache: false,
        contentType: false,
        data: form,
        dataType: "json",
        url: url,
        beforeSend: function() {
            $('#overlay').removeClass('hidden');
        },
    }).done(function(result) {
        $('#overlay').addClass('hidden');
        document.getElementById("firmware-file-uploader").value = '';
    });
}


function getCookie(name) 
{
    var nameEQ = name + "=";
    //alert(document.cookie);
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1);
        if (c.indexOf(nameEQ) != -1) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function set_agent_type(argument) {
    var  agent_type = document.querySelector('input[name="agent_type"]:checked').value;
    if(agent_type ){
        $.ajax({
            url: "?url=Admins/SetAgentType",
            method: "POST",
            data:{ 
                agent_type: agent_type
            },
            success: function(response) {
                console.log(response);
                alert(response);
                //history.go(0);
            },
            error: function(xhr, status, error) {
                
            }
        });   
    }
 
}


function update_barcode(){
    var barcode_content_text = document.getElementById("barcode_content").value;
    var barcode_content    = barcodeEditableTextToRaw(barcode_content_text);
    var barcode_mask_from  = document.getElementById("barcode_mask_from").value;
    var barcode_mask_count = document.getElementById("barcode_mask_count").value;
    var barcode_selected_job  = document.querySelector("select[name='barcode_job']").value;
    var barcode_enable  = document.querySelector("select[name='barcode_enable']").value;
    var barcode_selected_seq =  document.querySelector("select[name='barcode_seq']").value;

    // 驗證輸入
    let check = input_check_savebarcode();

    if(check){ 
        //document.querySelector(".main-content").classList.add("overlay-active");

        //新增判斷 Job
        if (barcode_selected_job === "-1") {
            return;
        }

        document.getElementById('spinner').style.display = 'block';

        $.ajax({
            url: "?url=Settings/Update_Barcode",
            method: "POST",
            data:{ 
                barcode_content : barcode_content,
                barcode_content_b64: encodeUtf8Base64Url(barcode_content),
                barcode_mask_from: barcode_mask_from,
                barcode_mask_count: barcode_mask_count,
                barcode_selected_job: barcode_selected_job,
                barcode_original_job: currentBarcodeOriginalJob || barcode_selected_job,
                barcode_enable:barcode_enable,
                barcode_selected_seq : barcode_selected_seq
            },
            success: function(response) {
                var responseData = JSON.parse(response);  // 解析返回的 JSON 資料
                
                // 延遲 1000 毫秒後隱藏加載動畫，並在隱藏後顯示 alertify 彈跳視窗
                setTimeout(function() {
                    // 隱藏 'copyjob' 和 'spinner' 加載動畫
                    document.querySelector(".main-content").classList.remove("overlay-active");
                    document.getElementById('spinner').style.display = 'none';  

                    // 顯示 alertify 彈跳視窗
                    alertify.alert(responseData.res_type, responseData.res_msg, function() {
                        // 彈跳視窗關閉後刷新頁面
                        // 存儲頁面顯示狀態到 sessionStorage
                        sessionStorage.setItem('Barcode_Setting', 'block');
                        sessionStorage.setItem('Controller_Setting', 'none');
                        
                        history.go(0);  // 重新加載頁面
                    });

                    // 在 3 秒後自動關閉 alertify 彈跳視窗，並執行 AJAX 請求來刷新條形碼列表
                    setTimeout(function() {
                        alertify.closeAll();  // 關閉所有開啟的 alertify 彈跳視窗
                        
                        // 刷新條形碼列表
                        $.ajax({
                            url: "?url=Settings/show_Barcodes",
                            method: "GET",
                            success: function(html) {
                                $('#total_barcodes').html(html);  
                            },
                            error: function(xhr, status, error) {
                                console.error("獲取條形碼時出錯:", error);
                            }
                        });
                    }, 3000); // 延遲 3 秒
                }, 1000); // 延遲 1000 毫秒
            },
            error: function(xhr, status, error) {
                //console.error("更新條形碼時出錯:", error);
            }
        });   
    }

    //document.querySelector(".main-content").classList.remove("overlay-active");


}



function delete_barcode() {
    var del_barcode_id = [];
    var checkboxes = document.querySelectorAll('input[name="barcode_check"]:checked');
    
    checkboxes.forEach(function (checkbox) {
        del_barcode_id.push(checkbox.value);
    });
    
    if(del_barcode_id){

        document.getElementById('spinner').style.display = 'block';

        
        $.ajax({
            url: "?url=Settings/delete_barcodes",
            method: "POST",
            data:{ 
                del_barcode_id: del_barcode_id

            },
            success: function(response) {
                var responseData = JSON.parse(response);  // 解析返回的 JSON 資料
                
                // 延遲 1000 毫秒後隱藏加載動畫，並在隱藏後顯示 alertify 彈跳視窗
                setTimeout(function() {
                    // 隱藏 'copyjob' 和 'spinner' 加載動畫
                    document.getElementById('spinner').style.display = 'none';  

                    // 顯示 alertify 彈跳視窗
                    alertify.alert(responseData.res_type, responseData.res_msg, function() {
                        // 彈跳視窗關閉後刷新頁面
                        // 存儲頁面顯示狀態到 sessionStorage
                        sessionStorage.setItem('Barcode_Setting', 'block');
                        sessionStorage.setItem('Controller_Setting', 'none');
                        
                        history.go(0);  // 重新加載頁面
                    });

                    // 在 3 秒後自動關閉 alertify 彈跳視窗，並執行 AJAX 請求來刷新條形碼列表
                    setTimeout(function() {
                        alertify.closeAll();  // 關閉所有開啟的 alertify 彈跳視窗
                        
                        // 刷新條形碼列表
                        $.ajax({
                            url: "?url=Settings/show_Barcodes",
                            method: "GET",
                            success: function(html) {
                                $('#total_barcodes').html(html);  
                            },
                            error: function(xhr, status, error) {
                                console.error("獲取條形碼時出錯:", error);
                            }
                        });
                    }, 3000); // 延遲 3 秒
                }, 1000); // 延遲 1000 毫秒
            },
            error: function(xhr, status, error) {
                
            }
        });   
    }
    
}




// ================================
// idas 更新
// ================================

function idas_update() {
    var import_file = document.getElementById("file-uploader").files[0];
    var form = new FormData();
    form.append("file", import_file);
    var url = '?url=Settings/iDas_Update';

    // 語言設定
    var language = getCookie('language') || 'en-us';
    var title, confirm_text, empty_file_text;

    if (language === "zh-cn" || language === "zh-tw") {
        title = 'IDAS 更新';
        confirm_text = '您確定要導入 IDAS 更新包嗎？';
        empty_file_text = '請先選擇要上傳的更新檔。';
    } else {
        title = 'IDAS UPDATE';
        confirm_text = 'Are you sure you want to import the IDAS update package?';
        empty_file_text = 'Please select a file to upload.';
    }

    // 未選擇檔案
    if (!import_file) {
        alertify.alert(title, empty_file_text);
        return;
    }

    alertify.confirm(confirm_text, function (result) {
        if (result) {
            document.getElementById('spinner').style.display = 'block'; // 顯示加載動畫

            $.ajax({
                url: url,
                method: "POST",
                data: form,
                processData: false,
                contentType: false,
                dataType: 'json', // ✅ jQuery 自動解析為物件
                success: function (responseData) {
                    // ✅ responseData 已是物件，無需 JSON.parse()
                    setTimeout(function () {
                        document.getElementById('spinner').style.display = 'none';
                        alertify.alert(responseData.res_type, responseData.res_msg, function () {
                            history.go(0); // 重新整理頁面
                        });

                        setTimeout(function () {
                            alertify.closeAll();
                        }, 3000);
                    }, 1000);
                },
                error: function (xhr, status, error) {
                    document.getElementById('spinner').style.display = 'none';
                    alertify.alert('Error', 'An error occurred while uploading the file.');
                    console.error("上傳錯誤：", status, error);
                }
            });
        }
    });
}




function encodeUtf8Base64Url(value) {
    const text = String(value || '');
    if (!text) return '';

    try {
        let binary = '';

        if (window.TextEncoder) {
            const bytes = new TextEncoder().encode(text);
            bytes.forEach(function (byte) {
                binary += String.fromCharCode(byte);
            });
        } else {
            binary = unescape(encodeURIComponent(text));
        }

        return btoa(binary)
            .replace(/\+/g, '-')
            .replace(/\//g, '_')
            .replace(/=+$/g, '');
    } catch (e) {
        console.warn('encodeUtf8Base64Url failed', e);
        return '';
    }
}

function decodeUtf8Base64(value) {
    let b64 = String(value || '');
    if (!b64) return '';

    try {
        // 支援一般 Base64，也支援網址列安全的 Base64URL（- / _，可省略 =）。
        b64 = b64.replace(/-/g, '+').replace(/_/g, '/');
        const pad = b64.length % 4;
        if (pad) b64 += '='.repeat(4 - pad);

        const binary = atob(b64);

        if (window.TextDecoder) {
            const bytes = Uint8Array.from(binary, function (ch) {
                return ch.charCodeAt(0);
            });
            return new TextDecoder('utf-8').decode(bytes);
        }

        let encoded = '';
        for (let i = 0; i < binary.length; i++) {
            encoded += '%' + ('00' + binary.charCodeAt(i).toString(16)).slice(-2);
        }
        return decodeURIComponent(encoded);
    } catch (e) {
        console.warn('decodeUtf8Base64 failed', e);
        return '';
    }
}

function getBarcodeValueFromCheckbox(cb) {
    if (!cb) return '';

    const b64 = cb.dataset ? (cb.dataset.barcodeB64 || '') : '';
    if (b64) {
        return decodeUtf8Base64(b64);
    }

    return (cb.dataset && cb.dataset.barcode) ? cb.dataset.barcode : '';
}


document.addEventListener('DOMContentLoaded', function() {
    const barcodeInput = document.getElementById('barcode_content');
    const barcodeCount = document.getElementById('barcode_mask_count');

    if (!barcodeInput || !barcodeCount) return;

    barcodeInput.addEventListener('input', function() {
        refreshBarcodeCount();
    });
});


document.addEventListener('change', function (e) {

    if (!e.target.matches('input[name="barcode_check"]')) return;

    const cb = e.target;

    // ✅ 先清掉所有列的高亮
    document.querySelectorAll('#job_table tbody tr').forEach(tr => {
        tr.classList.remove('barcode-checked');
    });

    // ✅ 只允許單選（其他自動取消）
    document.querySelectorAll('input[name="barcode_check"]').forEach(el => {
        if (el !== cb) el.checked = false;
    });

    // ❌ 取消勾選 → 清空表單
    if (!cb.checked) {
        clearBarcodeForm();
        return;
    }

    // ✅ 勾選 → 高亮該列，並記住原始 Job ID
    currentBarcodeOriginalJob = cb.dataset.job || cb.value || '';

    const tr = cb.closest('tr');
    if (tr) tr.classList.add('barcode-checked');

    // ✅ 帶入資料
    const rawBarcodeValue = getBarcodeValueFromCheckbox(cb);
    document.getElementById('barcode_content').value =
        barcodeRawToEditableText(rawBarcodeValue);

    document.getElementById('barcode_mask_from').value =
        cb.dataset.from || 1;

    document.getElementById('barcode_mask_count').value =
        cb.dataset.count || barcodeRawLength(rawBarcodeValue);

    document.getElementById('barcode_enable').value =
        cb.dataset.enable ?? -1;

    document.getElementById('barcode_job').value =
        cb.dataset.job ?? -1;

    // 顯示 seq 區塊（依 mode）；帶入 checkbox 時由下方 fetchSeqList(callback) 負責載入，避免重複打 API。
    toggleBarcodeSeq(false);

    const selectedSeq = cb.dataset.seq ?? -1;
    const barcodeModeValue = String(document.getElementById('barcode_enable').value);

    // Switch Seq / Switch Job + Seq 都需要載入 SEQ 清單。
    // 原本只判斷 mode === '2'，若系統的 Switch Job / Seq 是其他 key（例如 3），
    // 下拉選單會只剩「請選擇工序」，看不到 SEQ-1。
    if (barcodeModeValue !== '-1' && barcodeModeValue !== '0' && barcodeModeValue !== '1') {
        // 觸發 job → seq 載入，完成後再帶回原本選擇的 SEQ。
        fetchSeqList(() => {
            document.getElementById('barcode_seq').value = selectedSeq;
        });
    } else {
        document.getElementById('barcode_seq').value = selectedSeq;
    }
});




function clearBarcodeForm() {
    currentBarcodeOriginalJob = '';
    document.getElementById('barcode_content').value = '';
    document.getElementById('barcode_mask_from').value = 1;
    document.getElementById('barcode_mask_count').value = '';
    document.getElementById('barcode_enable').value = -1;
    document.getElementById('barcode_job').value = -1;
    document.getElementById('barcode_seq').value = -1;

    document.getElementById('barcode_select_seq').style.display = 'none';
}


