
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
    var barcode_content    = document.getElementById("barcode_content").value;
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
                barcode_mask_from: barcode_mask_from,
                barcode_mask_count: barcode_mask_count,
                barcode_selected_job:barcode_selected_job,
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


document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('barcode_content').addEventListener('input', function() {
        var length = this.value.length;
        document.getElementById('barcode_mask_count').value = length;
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

    // ✅ 勾選 → 高亮該列
    const tr = cb.closest('tr');
    if (tr) tr.classList.add('barcode-checked');

    // ✅ 帶入資料
    document.getElementById('barcode_content').value =
        cb.dataset.barcode || '';

    document.getElementById('barcode_mask_from').value =
        cb.dataset.from || 1;

    document.getElementById('barcode_mask_count').value =
        cb.dataset.count || '';

    document.getElementById('barcode_enable').value =
        cb.dataset.enable ?? -1;

    document.getElementById('barcode_job').value =
        cb.dataset.job ?? -1;

    // 觸發 job → seq 載入
    fetchSeqList(() => {
        document.getElementById('barcode_seq').value =
            cb.dataset.seq ?? -1;
    });

    // 顯示 seq 區塊（依 mode）
    toggleBarcodeSeq();
});




function clearBarcodeForm() {
    document.getElementById('barcode_content').value = '';
    document.getElementById('barcode_mask_from').value = 1;
    document.getElementById('barcode_mask_count').value = '';
    document.getElementById('barcode_enable').value = -1;
    document.getElementById('barcode_job').value = -1;
    document.getElementById('barcode_seq').value = -1;

    document.getElementById('barcode_select_seq').style.display = 'none';
}


