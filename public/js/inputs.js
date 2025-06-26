// ================================
// 語言處理區
// ================================

// 取得語系訊息（未被使用，可刪除或整合）
function getLanguageMessage(cookieName) {
   var value = "; " + document.cookie;
   var parts = value.split("; " + cookieName + "=");
   var language = (parts.length == 2) ? parts.pop().split(";").shift() : '';
   var message;
   if (language === 'en-us') {
       message = 'Please select the event to delete';
   } else if (language === 'zh-cn') {
       message = '请选择要删除的事件';
   } else if (language === 'zh-tw') {
       message = '請點選要刪除的事件';
   } else {
       message = 'Please select the event to delete';
   }
   // alertify.alert(message);
}

// ================================
// 事件切換顯示控制
// ================================

// 新增用事件切換
function handleEventChange(selectedValue) {
   var target = document.getElementById('work_goc');
   if (target) {
       target.style.display = (selectedValue == 109) ? 'block' : 'none';
   }
}

// 編輯用事件切換
function edit_handleEventChange(selectedValue) {
   var target = document.getElementById('edit_work_goc');
   if (target) {
       target.style.display = (selectedValue == 109) ? 'block' : 'none';
   }
}

// 等待 DOM 完全載入後才綁定 onchange
document.addEventListener("DOMContentLoaded", function () {
   var eventSelect = document.getElementById("Event_Option");
   if (eventSelect) {
       eventSelect.onchange = function () {
           handleEventChange(this.value);
       };
   }
});

// ================================
// 新增 Input 處理流程
// ================================

function create_input_id() {
   var input_event = document.getElementById("Event_Option").value;
   var allRadioButtons = document.querySelectorAll('input[name="pin_option"]');
   var input_pin = '';
   var input_wave = '';

   // 找出選中的 radio
   allRadioButtons.forEach(function (radio) {
       if (radio.checked) {
           input_pin = radio.id;
           input_wave = radio.value;
       }
   });

   // 若選的是事件 109，則需額外處理 gate confirm
   var input_gateconfirm = (input_event == 109) ?
       (document.querySelector('input[name="input_gateconfirm"]:checked')?.value || 0) : 0;

   var input_pagemode = 0;
   var input_seqid = 0;

   // 多語系錯誤訊息
   var language = getCookie('language') || 'en-us';
   var messages = {
       'en-us': "Please select a wave value.",
       'zh-tw': "請選擇波形值。",
       'zh-cn': "请选择波形值。"
   };

   // 若沒選擇波形，彈出提醒
   if (input_wave === '') {
       alertify.alert(messages[language]);
       setTimeout(() => alertify.closeAll(), 3000);
       return;
   }

   // 有 job_id 才可送出
   if (job_id) {
       document.getElementById('spinner').style.display = 'block';

       $.ajax({
           url: "?url=Inputs/create_input_event",
           method: "POST",
           data: {
               job_id: job_id,
               input_event: input_event,
               input_pin: input_pin,
               input_wave: input_wave,
               input_gateconfirm: input_gateconfirm,
               input_pagemode: input_pagemode,
               input_seqid: input_seqid
           },
           success: function (response) {
               document.getElementById('newinput').style.display = 'none';
               var responseData = JSON.parse(response);
               alertify.alert(responseData.res_type, responseData.res_msg);

               setTimeout(function () {
                   alertify.closeAll();
                   document.querySelector(".main-content").classList.remove("overlay-active");
                   document.getElementById('spinner').style.display = 'none';
                   get_input_by_job_id(job_id);
               }, 1000);
           },
           error: function (xhr, status, error) {
               // 錯誤處理可加上 console 或提示
           }
       });
   }
}

// ================================
//  畫面切換（表格/設定）
// ================================

function toggleDivs() {
   var tableInputSetting = document.getElementById('TableInputSetting');
   var tableDataInput = document.getElementById('TableDataInput');

   var isSettingVisible = (tableInputSetting.style.display === 'none');
   tableInputSetting.style.display = isSettingVisible ? 'block' : 'none';
   tableDataInput.style.display = isSettingVisible ? 'none' : 'block';
}

function showTableInputSetting() {
   document.getElementById('TableInputSetting').style.display = 'block';
   document.getElementById('TableDataInput').style.display = 'none';
   document.getElementById('input_menu').style.display = 'block';
}

// ================================
// Modal 點外部關閉
// ================================

var modal = document.getElementById('newinput');
window.onclick = function (event) {
   if (event.target == modal) {
       modal.style.display = "none";
   }
};


// ================================
// 選擇 Job 後的初始化函式
// ================================

function job_confirm() {
   var jobid = document.getElementById("JobNameSelect").value;

   // 儲存 job_id 至 localStorage，並設定為全域變數
   localStorage.setItem("jobid", jobid);
   job_id = jobid;
   all_job = jobid;

   if (jobid) {
       $.ajax({
           url: "?url=Inputs/get_input_by_job_id",
           method: "POST",
           data: { jobid: jobid },
           success: function(response) {
               var data = JSON.parse(response);
               var job_inputlist = data.job_inputlist;
               temp = data.temp;       // radio 相關控制
               tempA = data.tempA;     // option 相關控制

               // 更新 input list 表格內容
               document.getElementById("input_jobid_select").innerHTML = job_inputlist;

               // 隱藏 JobSelect 區塊，更新 job_id 輸入欄位
               document.getElementById("JobSelect").style.display = 'none';
               document.getElementById("job_id").value = jobid;

               // 控制 Copy 按鈕是否可用（如果沒有 input list 則 disable）
               var s3Button = document.getElementById('S3');
               if (!job_inputlist.trim()) {
                   s3Button.disabled = true;
               } else {
                   s3Button.disabled = false;
               }

               // 為每一列新增事件點擊時切換 input_event
               var rows = document.querySelectorAll('#input_jobid_select tr');
               rows.forEach(function(row) {
                   row.addEventListener('click', function() {
                       input_event = this.className;
                       old_input_event = this.className;
                   });
               });

               // 語系處理：根據語言設定更新事件名稱顯示
               var language = getCookie('language');

               const labels = {

                   '200': { 'zh-cn': '启用', 'zh-tw': '啟動' },
                   '201': { 'zh-cn': '拆螺丝', 'zh-tw': '拆螺絲' },
                   '202': { 'zh-cn': '禁用', 'zh-tw': '禁用' },
                   '203': { 'zh-cn': '启用', 'zh-tw': '啟用' },
                   '204': { 'zh-cn': '确认', 'zh-tw': '確認' },
                   '205': { 'zh-cn': '清除', 'zh-tw': '清除' },
                   '206': { 'zh-cn': '工序清除', 'zh-tw': '工序清除' },
                   '207': { 'zh-cn': '重启', 'zh-tw': '重啟' },
                   '208': { 'zh-cn': '自定义1', 'zh-tw': '自定義1' },
                   '209': { 'zh-cn': '自定义2', 'zh-tw': '自定義2' },
                   '210': { 'zh-cn': '一次感应', 'zh-tw': '一次感應' },
                  
               };

               // 根據語系更新 DOM 文字
               Object.keys(labels).forEach(id => {
                   var el = document.getElementById(id);
                   if (el && labels[id][language]) {
                       el.textContent = labels[id][language];
                   }
               });
           },
           error: function(xhr, status, error) {
               // 錯誤處理可補充提示
               console.error("Job confirm AJAX error:", error);
           }
       });
   }
}

// ================================
// 啟用 Job 選擇按鈕
// ================================

function enableButton() {
   var button = document.getElementById('Button_Select');
   if (button.disabled) {
       button.disabled = false;
   }
}

// ================================
// 重置 job_id 欄位背景顏色
// ================================

function resetBackgroundColor() {
   var jobInput = document.getElementById('job_id');
   if (jobInput.style.backgroundColor === 'yellow') {
       jobInput.style.backgroundColor = '';
   }
}

// ================================
// 刪除指定 job_id 與 input_event 的輸入項目
// ================================

function delete_input_id(job_id,input_event){

   var language = getCookie('language');
   var text_info, title;

   if (language === "zh-cn") {
       text_info = '你确定吗？';
       title = '刪除任務';
   } else if (language === "zh-tw") {
       text_info = '你確定嗎？';
       title = '刪除任務';
   } else {
       text_info = 'Are you sure?';
       title = 'Delete Job';
   }
   
   if (job_id) {
       alertify.confirm(
           title, // 標題
           text_info, // 提示文字
           function() {
               //使用者選擇「是」後執行刪除動作
               document.getElementById('spinner').style.display = 'block';

               $.ajax({
                   url: "?url=Inputs/delete_input",
                   method: "POST",
                   data: { 
                       job_id: job_id,
                       input_event: input_event
                   },
                   success: function(response) {
                       var responseData = JSON.parse(response);
                       alertify.alert(responseData.res_type, responseData.res_msg);

                       setTimeout(function() {
                           alertify.closeAll(); // 關閉所有 alertify 彈窗
                           updateEventSelectAndPins(responseData.old_input_pin); // 更新 pins
                           get_input_by_job_id(job_id); // 重新取得輸入資訊
                           document.getElementById('spinner').style.display = 'none'; 
                           document.querySelector(".main-content").classList.remove("overlay-active"); 
                       }, 1000); 
                   },
                   error: function(xhr, status, error) {
                       //console.error("AJAX request failed:", status, error);
                       alertify.error("刪除失敗，請稍後再試！");
                       document.querySelector(".main-content").classList.remove("overlay-active"); 
                       document.getElementById('spinner').style.display = 'none';
                   }
               });
            },
            function() {
                // 取消 callback 可選寫在這裡（目前略過）
                document.querySelector(".main-content").classList.remove("overlay-active");
            }
        ).set('labels', {ok:'YES', cancel:'NO'}); // 修改按鈕文字
    }
}


// ================================
// 複製 Job Input 設定
// ================================

function copy_input_id() {
   var language = getCookie('language') || 'en-us';

   var messages = {
       'zh-cn': '若设定已存在，将会取代原有设定',
       'zh-tw': '若設定已存在，將會取代原有設定',
       'en-us': 'If the job input already exists, it will replace the original setting'
   };

   var text_info = messages[language] || messages['en-us'];

   // 建立 confirm 視窗
   var confirmDialog = alertify.confirm(text_info, function (confirmed) {
       // 使用者點選後取消自動關閉計時器
       clearTimeout(autoCancelTimer);
       if (!confirmed) return;

       var to_job_id = document.getElementById("JobSelect1").value;
       if (!to_job_id) return;

       document.getElementById('spinner').style.display = 'block';

       $.ajax({
           url: "?url=Inputs/copy_input_event",
           method: "POST",
           data: {
               from_job_id: job_id,
               to_job_id: to_job_id
           },
           success: function (response) {
               document.getElementById('copyinput').style.display = 'none';

               try {
                   var responseData = JSON.parse(response);

                   alertify.alert(responseData.res_type, responseData.res_msg, function () {
                       document.getElementById('spinner').style.display = 'none';
                       document.querySelector(".main-content").classList.remove("overlay-active");
                       get_input_by_job_id(job_id);
                   });

               } catch (e) {
                   console.error('Invalid JSON:', e);
                   alertify.error("系統回應格式錯誤");
                   document.getElementById('spinner').style.display = 'none';
                   document.querySelector(".main-content").classList.remove("overlay-active");
               }
           },
           error: function () {
               alertify.error("複製失敗，請稍後再試！");
               document.getElementById('spinner').style.display = 'none';
               document.querySelector(".main-content").classList.remove("overlay-active");
           }
       });
   });

   // 自動取消邏輯：3 秒後自動關閉 confirm 視窗
   var autoCancelTimer = setTimeout(function () {
       alertify.closeAll(); // 關閉 alertify 視窗
       // 可選提示
       //alertify.message("自動取消複製操作");
   }, 3000);
}

// ================================
// 收集所有被選中的輸入元件
// ================================

function collectPinValues(selector) {
   var inputElements = document.querySelectorAll(selector); // 取得所有匹配的元素
   var selectedValues = [];

   inputElements.forEach(function(element) {
       if (element.checked) {
           selectedValues.push({
               id: element.id,
               value: element.value
           });
       }
   });

   return selectedValues;
}


// ================================
// 控制資料表格與選單的顯示與資料載入
// 目前支援 "show"
// ================================

function tablesubmit(keyno) {
   if (keyno === 'show') {
       document.getElementById('TableDataInput').style.display = 'block';
       document.getElementById('input_menu').style.display = 'none';
       get_input_by_job_id(job_id);
   }
}



// ================================
// 根據 Job ID 取得該 Job 對應的輸入設定資料
// ================================

function get_input_by_job_id(jobid) {
   $.ajax({
       url: "?url=Inputs/get_input_by_job_id",
       method: "POST",
       data: { jobid: jobid },
       success: function(response) {
           var data = JSON.parse(response);
           var job_inputlist = data.job_inputlist;
           temp = data.temp;
           tempA = data.tempA;

           // 更新輸入清單 HTML
           document.getElementById("input_jobid_select").innerHTML = job_inputlist;
           document.getElementById("JobSelect").style.display = 'none';
           document.getElementById("job_id").value = jobid;

           // 為每一列綁定 click 事件，設定 input_event
           document.querySelectorAll('#input_jobid_select tr').forEach(function(row) {
               row.addEventListener('click', function () {
                   input_event = this.className;
               });
           });

           // 多語系事件標籤更新
           updateEventLabelsByLanguage(getCookie('language'));
       },
       error: function(xhr, status, error) {
           console.error("AJAX request failed:", status, error);
       }
   });
}


// ================================
// 收根據語系更新事件 ID 對應的標籤
// @param {string} language - 語言代碼（如 zh-tw, zh-cn, en-us）
// ================================

function updateEventLabelsByLanguage(language) {
   var labels = {
        '200': { 'zh-cn': '启用', 'zh-tw': '啟動' },
        '201': { 'zh-cn': '拆螺丝', 'zh-tw': '拆螺絲' },
        '202': { 'zh-cn': '禁用', 'zh-tw': '禁用' },
        '203': { 'zh-cn': '启用', 'zh-tw': '啟用' },
        '204': { 'zh-cn': '确认', 'zh-tw': '確認' },
        '205': { 'zh-cn': '清除', 'zh-tw': '清除' },
        '206': { 'zh-cn': '工序清除', 'zh-tw': '工序清除' },
        '207': { 'zh-cn': '重启', 'zh-tw': '重啟' },
        '208': { 'zh-cn': '自定义1', 'zh-tw': '自定義1' },
        '209': { 'zh-cn': '自定义2', 'zh-tw': '自定義2' },
        '210': { 'zh-cn': '一次感应', 'zh-tw': '一次感應' },

   };

   Object.keys(labels).forEach(function(id) {
       var el = document.getElementById(id);
       if (el && labels[id][language]) {
           el.textContent = labels[id][language];
       }
   });
}


// ================================
// 將 job_id 提交至 server 做全域對齊處理
// 並控制按鈕可用狀態與背景色提示
// @param {string} job_id 
// ================================

function alignsubmit(job_id) {
   if (!job_id) return;

   $.ajax({
       url: "?url=Inputs/input_alljob",
       method: "POST",
       data: {
           job_id: job_id
       },
       success: function (response) {
           get_input_by_job_id(job_id);

           // 切換按鈕啟用狀態
           buttonDisabled = !buttonDisabled;
           document.getElementById('Button_Select').disabled = buttonDisabled;

           // 切換背景色提示狀態
           backgroundColorYellow = !backgroundColorYellow;
           document.getElementById('job_id').style.backgroundColor = backgroundColorYellow ? 'yellow' : '';
       },
       error: function (xhr, status, error) {
           console.error("alignsubmit AJAX failed:", status, error);
       }
   });
}


// ================================
// 重置所有 job 對齊狀態（job_id_new = 0）
// 並重新載入目前選定的 job 資料
// @param {string} job_id 
// ================================

function resetalignsubmit(job_id) {
   if (!job_id) return;

   $.ajax({
       url: "?url=Inputs/input_alljob",
       method: "POST",
       data: {
           job_id_new: 0
       },
       success: function (response) {
           get_input_by_job_id(job_id);
       },
       error: function (xhr, status, error) {
           console.error("resetalignsubmit AJAX failed:", status, error);
       }
   });
}


// ================================
// 據 job_id 與 input_event 從伺服器取得對應事件的輸入設定資訊
// ================================

function get_input_info() {
   if (!job_id) return;

   $.ajax({
       url: "?url=Inputs/check_job_event_conflict",
       method: "POST",
       data: {
           job_id: job_id,
           input_event: input_event
       },
       success: function(response) {
           // 如果無資料，跳出語系提示
           if (response === 'no_data') {
               getLanguageMessage('language');
               return;
           }

           // 清理 PHP 陣列文字格式
           var responseJSON = JSON.stringify(response);
           var cleanString = responseJSON.replace(/Array|\\n/g, '').substring(2, responseJSON.length - 2);

           // 從字串解析各個欄位（逐個用正則取值）
           var [, input_event_val] = cleanString.match(/\[input_event]\s*=>\s*([^ ]+)/) || [, null];
           var [, gateconfirm]     = cleanString.match(/\[input_gateconfirm]\s*=>\s*([^ ]+)/) || [, null];

           var input_wave = [];
           var foundPin = null;

           // 檢查 pin1~pin10 是否有被使用（值非 0 且非 null）
           for (var i = 1; i <= 10; i++) {
               var match = cleanString.match(new RegExp(`\\[input_pin${i}\\]\\s*=>\\s*([^ ]+)`));
               if (match && match[1] !== '0' && match[1] !== 'null') {
                   input_wave.push(match[1]);
                   foundPin = i;
               }
           }

           // 判斷波形（根據值為 1 則 high，否則 low）
           var wave = (input_wave[0] == 1) ? '_high' : '_low';
           var edit_input_pin = "edit_pin" + foundPin + wave;

           // 啟用正確的 radio 並選中
           var radioButton = document.getElementById(edit_input_pin);
           if (radioButton) {
               radioButton.removeAttribute('disabled');
               radioButton.checked = true;

               // 啟用對應的另一組（相對波形）
               var otherWave = (wave === '_high') ? '_low' : '_high';
               var pairedButton = document.getElementById("edit_pin" + foundPin + otherWave);
               if (pairedButton) {
                   pairedButton.disabled = false;
               }
           }

           old_input_event = input_event;

           // 若事件類型為 109，顯示 gateconfirm 區塊並設定值
           if (input_event == 109) {
               document.getElementById('edit_work_goc').style.display = 'block';
               if (gateconfirm == 0) {
                   document.getElementById('edit_gateconfirm_0').checked = true;
               } else {
                   document.getElementById('edit_gateconfirm_1').checked = true;
               }
           }

           // 設定事件下拉選單的選中值
           var eventSelect = document.querySelector("select[name='edit_Event_Option']");
           if (eventSelect) {
               eventSelect.value = input_event;
               // 綁定 onchange 行為（重新控制 UI）
               eventSelect.onchange = function () {
                   edit_handleEventChange(this.value);
               };
           }
       },
       error: function(xhr, status, error) {
           console.error("get_input_info AJAX failed:", status, error);
       }
   });
}


// ================================
// 更新事件選單為未選狀態，並根據先前使用的 pin 解除其 disabled
// @param {string} old_input_pin - 例如 "pin3_high" 或 "pin5_low"
// ================================

function updateEventSelectAndPins(old_input_pin) {
   // 取得事件選單 DOM 元素
   var eventSelect = document.getElementById('Event_Option');

   // 如果目前選單不是預設狀態 (-1)，則重設為 -1
   if (eventSelect && eventSelect.value !== '-1') {
       eventSelect.value = '-1';
   }

   // 如果有傳入先前使用過的 pin，開始處理
   if (old_input_pin) {
       // 從 ID 中擷取 pin 編號（數字部分）
       var pinNumberMatch = old_input_pin.match(/\d+/);
       if (!pinNumberMatch) return;

       var pinNumber = pinNumberMatch[0];

       // 對應的 radio ID 組合
       var pinHighId = 'pin' + pinNumber + '_high';
       var pinLowId  = 'pin' + pinNumber + '_low';

       // 將對應的 radio 啟用並取消選取
       var pinHighElement = document.getElementById(pinHighId);
       var pinLowElement  = document.getElementById(pinLowId);

       if (pinHighElement) {
           pinHighElement.disabled = false;
           pinHighElement.checked = false;
       }

       if (pinLowElement) {
           pinLowElement.disabled = false;
           pinLowElement.checked = false;
       }
   }
}


// ================================
// 編輯輸入設定，根據表單內容發送 AJAX 進行更新
// ================================

function edit_input_id() {
   // 取得選擇的事件
   var input_event = document.getElementById("edit_Event_Option").value;

   // 找出所有 edit pin 選項 (radio buttons)
   var allRadioButtons = document.querySelectorAll('input[name="edit_pin_option"]');

   var input_pin = '';
   var input_wave = '';

   // 遍歷 radio，找出已選中的項目
   allRadioButtons.forEach(function(radio) {
       if (radio.checked) {
           input_pin = radio.id;
           input_wave = radio.value;
       }
   });

   // 判斷是否為事件類型 109，取得 gate confirm 資訊
   var input_gateconfirm = 0;
   if (input_event == 109) {
       var selectedOption = document.querySelector('input[name="edit_input_gateconfirm"]:checked');
       input_gateconfirm = selectedOption ? selectedOption.value : 0;
   }

   // 固定值（如無特殊用途）
   var input_pagemode = 0;
   var input_seqid = 0;

   // 確認有選定 job_id 才執行
   if (job_id) {
       document.getElementById('spinner').style.display = 'block';

       $.ajax({
           url: "?url=Inputs/edit_input_event",
           method: "POST",
           data: {
               job_id: job_id,
               input_event: input_event,
               input_pin: input_pin,
               input_wave: input_wave,
               input_gateconfirm: input_gateconfirm,
               input_pagemode: input_pagemode,
               input_seqid: input_seqid
           },
           success: function(response) {
               document.getElementById('edit_input').style.display = 'none';

               try {
                   var responseData = JSON.parse(response);

                   // 顯示回應訊息
                   alertify.alert(responseData.res_type, responseData.res_msg);

                   setTimeout(function () {
                       alertify.closeAll();
                       document.querySelector(".main-content").classList.remove("overlay-active");
                       document.getElementById('spinner').style.display = 'none';
                       get_input_by_job_id(job_id); // 重新載入資料
                   }, 1000);

               } catch (e) {
                   alertify.error("資料格式錯誤，無法解析伺服器回應。");
                   console.error("JSON parse error:", e);
                   document.getElementById('spinner').style.display = 'none';
               }
           },
           error: function(xhr, status, error) {
               alertify.error("更新失敗，請稍後再試！");
               console.error("AJAX error:", status, error);
               document.getElementById('spinner').style.display = 'none';
           }
       });
   }
}
