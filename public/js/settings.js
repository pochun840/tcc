
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
    var barcode_selected_job  = document.querySelector("select[name='barcode_selected_job']").value;
    var barcode_enable  = document.querySelector("select[name='barcode_enable']").value;
    var barcode_selected_seq = '';

    // 驗證輸入
    let check = input_check_savebarcode();

    if(check){ 
        //document.querySelector(".main-content").classList.add("overlay-active");
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
    const fileInput = document.getElementById("file-uploader");
    const import_file = fileInput?.files[0];
    const url = '?url=Settings/iDas_Update';
    const form = new FormData();
    form.append("file", import_file);

    // 語言設定
    const language = getCookie('language') || 'en-us';
    const i18n = {
        "zh-tw": {
            title: "IDAS 更新",
            confirm: "您確定要導入 IDAS 更新包嗎？",
            empty: "請先選擇要上傳的更新檔。",
            error: "檔案上傳失敗，請稍後再試。"
        },
        "zh-cn": {
            title: "IDAS 更新",
            confirm: "您確定要導入 IDAS 更新包嗎？",
            empty: "請先選擇要上傳的更新檔。",
            error: "文件上传失败，请稍后再试。"
        },
        "en-us": {
            title: "IDAS UPDATE",
            confirm: "Are you sure you want to import the IDAS update package?",
            empty: "Please select a file to upload.",
            error: "File upload failed. Please try again later."
        }
    };
    const t = i18n[language.toLowerCase()] || i18n["en-us"];

    // 檔案不存在
    if (!import_file) {
        alertify.alert(t.title, t.empty);
        return;
    }

    alertify.confirm(t.title, t.confirm, function (result) {
        if (result) {
            document.getElementById('spinner').style.display = 'block';

            $.ajax({
                url,
                method: "POST",
                data: form,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (responseData) {
                    setTimeout(() => {
                        document.getElementById('spinner').style.display = 'none';
                        alertify.alert(responseData.res_type, responseData.res_msg, () => {
                            history.go(0);
                        });
                        setTimeout(() => alertify.closeAll(), 3000);
                    }, 1000);
                },
                error: function (xhr, status, error) {
                    console.error("上傳錯誤：", status, error);
                    document.getElementById('spinner').style.display = 'none';
                    alertify.alert('Error', t.error);
                }
            });
        }
    });
}



