
function getLanguageMessage(cookieName) {
    var value = "; " + document.cookie;
    var parts = value.split("; " + cookieName + "=");
    var language = (parts.length == 2) ? parts.pop().split(";").shift() : '';
    var message;
    if (language === 'en-us') {
       message =  'Please select the event to delete';
    } else if (language === 'zh-cn') {
       message =  '请选择要删除的事件';
    } else if (language === 'zh-tw') {
       message =  '請點選要刪除的事件';
    } else {
      message =  'Please select the event to delete';
    }

}


function disableTimeFields() {
   for (let i = 1; i <= 9; i++) {
       const timeField = document.getElementById(`time${i}`);
       if (timeField) {
           timeField.disabled = true;  // 禁用欄位
       }
   }
} 

// ================================
// 依照下拉選單的value 控制 id='time1~9'
// ================================

function toggleOnputTime(inputId, checked, option) {
   // 根據傳入的 inputId 取得對應的 input 元素
   var inputElement = document.getElementById(inputId);

   // 如果找不到該元素，輸出錯誤訊息並中止執行
   if (!inputElement) {
       console.error(`Element with ID '${inputId}' not found.`);
       return;
   }

   // 如果該元素是 checkbox 或 radio button
   if (inputElement.type === 'checkbox' || inputElement.type === 'radio') {
       // 檢查實際的 checked 狀態是否與傳入的參數不一致
       if (inputElement.checked !== checked) {
           console.warn(`The checked state of the element with ID '${inputId}' does not match the provided 'checked' value.`);
       }
   }

   // 根據選擇的 option 來控制對應的時間輸入欄位是否可編輯
   // 若不是 option 2（即 signal01 或 trigger），就禁用對應的時間欄位
   if (option != '3') {
       // 將 pin ID 轉換為對應的時間輸入欄位 ID（例如 pin3_1 => time3）
       var newId = inputId.replace(/^pin(\d+)_\d+$/, 'time$1');
       var element = document.getElementById(newId);
       if (element) {
           element.disabled = true; // 禁用時間欄位
       }

   } else {
       // 如果是 option 2（signal02），啟用對應的時間輸入欄位
       var newId = inputId.replace(/^pin(\d+)_\d+$/, 'time$1');
       var element = document.getElementById(newId);
       if (element) {
           element.disabled = false; // 啟用時間欄位
       }
   }
}

// ================================
// 依照下拉選單的value 控制 id='edit_time1~9'
// ================================
function toggleOnputTime_edit(inputId, checked, option) {
   // 根據 inputId 取得原本的 pin 勾選欄位
   var inputElement = document.getElementById(inputId);

   // 若元素不存在，記錄錯誤並停止執行
   if (!inputElement) {
       console.error(`Element with ID '${inputId}' not found.`);
       return;
   }

   // 若是 checkbox 或 radio，確認 checked 狀態與傳入值是否一致（可協助除錯）
   if (inputElement.type === 'checkbox' || inputElement.type === 'radio') {
       if (inputElement.checked !== checked) {
           console.warn(`The checked state of the element with ID '${inputId}' does not match the provided 'checked' value.`);
       }
   }

   // 轉換為對應的時間欄位 ID，例如 edit_pin3_1 → edit_time3
   var newId = inputId.replace(/^edit_pin(\d+)_\d+$/, 'edit_time$1');
   var element = document.getElementById(newId);

   if (element) {
       if (option !== '3') {
           // 非 option 2，代表要禁用時間欄位

           // 如果還沒儲存過原始值，則先儲存進 data-original-value
           if (!element.dataset.originalValue) {
               element.dataset.originalValue = element.value;
           }

           // 設為 disabled 並清空顯示為 0
           element.disabled = true;
           element.value = 0;
       } else {
           // option === '2'，啟用欄位

           element.disabled = false;

           // 如果有儲存原始值，還原它，並清除儲存
           if (element.dataset.originalValue) {
               element.value = element.dataset.originalValue;
               delete element.dataset.originalValue;
           }
       }
   } else {
       // 找不到對應時間欄位，記錄錯誤
       console.error(`Target time input with ID '${newId}' not found.`);
   }
}


// ================================
// 新增 output event
// ================================

function create_output_id() {
   // 取得選取的事件選項值
   var output_event = document.getElementById("Event_Option").value;

   
   const skipEvents = [7,8,9,10,11,12,13,14,15];


   // 收集使用者所勾選的輸出腳位（radio input），回傳包含 id 與 value 的陣列
   var pinval = collectPinValues('input[name="pin_option"]');

   // 檢查是否有勾選腳位
   if (pinval.length > 0) {
       var pin_old = pinval[0]['id'];   // 例如：pin3_1
       var wave = pinval[0]['value'];  // 取得腳位模式（如 1、2、3）

       // 從 ID 中提取數字（腳位號），例如從 pin3_1 中取得 3
       var match = pin_old.match(/\d+/); 
       var output_pin = match ? parseInt(match[0]) : null;

       // 組合對應的時間輸入欄位 ID，例如：time3
       var time_ms = 'time' + output_pin;
       var wave_on = document.getElementById(time_ms).value;

       // 取得語言設定（cookie）
       var language = getCookie('language');

       // 定義各語系下的錯誤訊息
       var messages = {
           'en-us': "Please enter a wave value between 100 and 10000.",
           'zh-tw': "範圍介於100和10000之間。",
           'zh-cn': "范围介于100和10000之间。"
       };

       if (!language) {
           language = 'en-us'; // 預設語言
       }

       // 當模式為 3（trigger 時），才檢查 wave_on 是否在合理範圍內
       if (wave == 3 && !skipEvents.includes(Number(output_event))) {
           if (wave_on < 100 || wave_on > 10000) {
               alertify.alert(messages[language]); // 顯示語系對應的錯誤訊息
               setTimeout(function () {
                   alertify.closeAll();
               }, 3000);
               return; // 中止送出
           }
       }

       // 檢查 job_id 是否已定義
       if (job_id) {
           // 顯示 loading spinner
           document.getElementById('spinner').style.display = 'block';

           // 發送 AJAX 請求儲存輸出事件設定
           $.ajax({
               url: "?url=Outputs/create_output_event",
               method: "POST",
               data: {
                   job_id: job_id,
                   output_pin: output_pin,
                   output_event: output_event,
                   wave: wave,
                   wave_on: wave_on
               },

               success: function (response) {
                   // 成功後隱藏新增面板
                   document.getElementById('new_output').style.display = 'none';

                   // 將回傳的 JSON 字串解析成物件
                   var responseData = JSON.parse(response);

                   // 顯示回傳訊息
                   alertify.alert(responseData.res_type, responseData.res_msg);

                   // 延遲關閉彈窗與 Spinner，並更新畫面資料
                   setTimeout(function () {
                       alertify.closeAll();
                       document.getElementById('spinner').style.display = 'none';
                       document.querySelector(".main-content").classList.remove("overlay-active");
                       get_output_by_job_id(job_id); // 重新載入 job 對應的輸出資料
                   }, 1000);

                   // 重置事件選單選項為 -1（預設值）
                   if (document.getElementById("Event_Option").value !== "-1") {
                       document.getElementById("Event_Option").value = "-1";
                   }
               },

               error: function (xhr, status, error) {
                   console.error("AJAX request failed:", status, error);
               }
           });
       }
   } else {
       console.error("No pinval found or pinval[0] is undefined.");
   }
}



// ================================
// 讀cookie
// ================================

function getCookie(name) {
   var nameEQ = name + "=";
   var ca = document.cookie.split(';');
   for(var i = 0; i < ca.length; i++) {
       var c = ca[i];
       while (c.charAt(0) == ' ') c = c.substring(1, c.length);
       if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
   }
   return null;
}


// ================================
// 更新事件選單為未選狀態，並根據先前使用的 pin 解除其 disabled
// @param {string} old_output_pin - 例如 "pin3_high" 或 "pin5_low"
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
// 對應的id 進行 disabled
// ================================

function disableElements(filtered_array) {
   // 生成新的 id 数组，去除末尾的数字并添加 "_1", "_2", "_3" 和 "time1" 到 "time11"
   let new_array = filtered_array
       .map(item => item.replace(/_\d$/, ''))  // 去除原始字符串末尾的数字
       .flatMap(item => {
           let result = [
               item + "_1",
               item + "_2",
               item + "_3"
           ];

           // 新增 "time" + 1 到 11
           for (let i = 1; i <= 11; i++) {
               result.push("time" + i);
           }

           return result;
       });

   // 遍历新生成的 id 数组，如果元素存在就禁用它
   new_array.forEach(id => {
       let element = document.getElementById(id); 
       if (element) {
           element.disabled = true;  // 禁用该元素
       }
   });
}



function translatePage(language) {
   const translations = {
     "zh-cn": {
       "1": "OK",
       "2": "NG",
       "3": "超出上限",
       "4": "低于下限",
       "5": "工序完成信号",
       "6": "工作任务完成信号",
       "7": "马达信号",
       "8": "启动信号",
       "9": "拆螺丝",
       "10": "BS",
       "11": "条码",
       "12": "自定义1",
       "13": "自定义2",
       "14":"顺时针转动",
       "15":"逆时针转动",

     },
     "zh-tw": {
       "1": "OK",
       "2": "NG",
       "3": "超出上限",
       "4": "低於下限",
       "5": "工序完成信號",
       "6": "完工信號",
       "7": "馬達信號",
       "8": "啟動信號",
       "9": "拆螺絲",
       "10": "BS",
       "11": "條碼",
       "12": "自定義1",
       "13": "自定義2",
       "14":"順時針轉動",
       "15":"逆時針轉動",
     }
   };
 
   const langData = translations[language];
 
   if (langData) {
     for (const id in langData) {
       const element = document.getElementById(id);
       if (element) {
         element.textContent = langData[id];
       }
     }
   }
 }


function updateInputsBasedOnRadioSelection() {
   
   for (let i = 1; i <= 11; i++) {
       let radioId = 'pin' + i + '_3';
       let inputId = 'time' + i;
       
       let radioElement = document.getElementById(radioId);
       let inputElement = document.getElementById(inputId);

       if (radioElement && inputElement) {
           inputElement.disabled = !radioElement.checked;
       }
   }
}


function disableNextPin(filteredArray) {
   filteredArray.forEach(function(pinId) {
 
       var match = pinId.match(/^pin(\d+)_(\d+)$/);

       if (match) {
           var pinNumber = parseInt(match[1]); 
           var subNumber = parseInt(match[2]); 
           var nextPinId = 'pin' + pinNumber + '_' + (subNumber + 1); 
           var nextPinElement = document.getElementById(nextPinId);
           
           if (nextPinElement) {
               nextPinElement.disabled = true; 
           }

           console.log(document.getElementById(nextPinId).disabled);
       }
   });
}


function toggleElementsInRange(start, end, suffix, filtered_array = []) {
    let selectedOptionId = parseInt(eventOption.options[eventOption.selectedIndex].value);
    const disableOptions = [7, 8, 9, 12, 13, 14, 15, 16];
    let disableAll = disableOptions.includes(selectedOptionId);

    const isAlwaysEnable = [10, 11].includes(selectedOptionId);

    // 新增：記錄哪些 pin?_3 被勾選
    const pins3Checked = [];

    // 掃所有 pin?_3 看誰被勾
    for (let i = start; i <= end; i++) {
        let pin3Id = `pin${i}_3`;
        let pin3Element = document.getElementById(pin3Id);

        if (pin3Element && pin3Element.checked) {
            pins3Checked.push(i);
        }
    }

    for (let i = start; i <= end; i++) {
        for (let j = 1; j <= suffix; j++) {
            let id = 'pin' + i + '_' + j;
            let element = document.getElementById(id);

            if (element) {
                if (isAlwaysEnable) {
                    element.disabled = false;
                } else {
                    element.disabled = disableAll && (j === 1 || j === 2);
                }
            }
        }

        let timeId = 'time' + i;
        let timeElement = document.getElementById(timeId);
        if (timeElement) {
            if (isAlwaysEnable) {
                timeElement.disabled = false;
            } else if (pins3Checked.includes(i)) {
                // ✅ 新增邏輯：
                // 若 pin?_3 被勾選 → timeX 一定要 enabled
                timeElement.disabled = false;
            } else {
                timeElement.disabled = disableAll;
            }
        }
    }

    const pinDisableMap = {};
    for (let i = 1; i <= 9; i++) {
        pinDisableMap[`pin${i}_1`] = `pin${i}_3`;
        pinDisableMap[`pin${i}_2`] = `pin${i}_3`;
    }

    Object.entries(pinDisableMap).forEach(([triggerPin, targetPin]) => {
        const targetElement = document.getElementById(targetPin);
        if (targetElement) {
            if (isAlwaysEnable) {
                targetElement.disabled = false;
            } else if (filtered_array.includes(triggerPin)) {
                targetElement.disabled = true;
            }
        }
    });
}





// ================================
// 重置所有 job 對齊狀態（job_id_new = 0）
// 並重新載入目前選定的 job 資料
// @param {string} job_id 
// ================================

function resetalignsubmit(job_id) {

   var job_id_new = 0;
   if(job_id_new == 0){
       $.ajax({
           url: "?url=Outputs/output_alljob",
           method: "POST",
           data: {
               job_id_new: job_id_new
           },
           success: function (response) {
               get_output_by_job_id(job_id);
           },
           error: function (xhr, status, error) {

           }
       });

   }

}

// ================================
// 將 job_id 提交至 server 做全域對齊處理
// 並控制按鈕可用狀態與背景色提示
// @param {string} job_id 
// ================================

function alignsubmit(job_id) {
   if (job_id) {
       $.ajax({
           url: "?url=Outputs/output_alljob",
           method: "POST",
           data: {
               job_id: job_id
           },
           success: function (response) {
               get_output_by_job_id(job_id);
           
               buttonDisabled = !buttonDisabled;
               document.getElementById('Button_Select').disabled = buttonDisabled;
    
               backgroundColorYellow = !backgroundColorYellow;
               if (backgroundColorYellow) {
                   document.getElementById('job_id').style.backgroundColor = 'yellow';
               } else {
                   document.getElementById('job_id').style.backgroundColor = '';
               }
           },
           error: function (xhr, status, error) {

           }
       });
   }
}

// ================================
// 找出 job_id 對應的output 資料 
// ================================

function get_output_info(job_id,output_event){

   if(job_id){
    $.ajax({
            url: "?url=Outputs/check_job_event",
            method: "POST",
            data: { 
                job_id: job_id,
                output_event: output_event
            },
            success: function(response) {
             
               var responseJSON = JSON.stringify(response);
               var cleanString = responseJSON.replace(/Array|\\n/g, '');
               var cleanString = cleanString.substring(2, cleanString.length - 2);
               var [, job_id] = cleanString.match(/\[output_jobid]\s*=>\s*([^ ]+)/) || [, null];
               var [, output_event] = cleanString.match(/\[output_event]\s*=>\s*([^ ]+)/) || [, null];
               var [, output_pin] = cleanString.match(/\[output_pin]\s*=>\s*([^ ]+)/) || [, null];
               var [, wave] = cleanString.match(/\[wave]\s*=>\s*([^ ]+)/) || [, null];
               var [, wave_on] = cleanString.match(/\[wave_on]\s*=>\s*([^ ]+)/) || [, null];
               var time_ms = 'edit_time' + output_pin;

               if (wave_on !== "0") {
                   document.getElementById(time_ms).value = wave_on;
               }

               var edit_output_pin = "edit_pin" + output_pin + "_"+ wave;
               var radioButton = document.getElementById(edit_output_pin);
               radioButton.removeAttribute('disabled');

               var time_ms = 'edit_time'+ output_pin;

               if(wave != 2){
                   var time_id = 'edit_time' + output_pin;
                   var element = document.getElementById(time_id);
                   
                   if(element){
                       element.disabled = true
                   }
               }

               //完工信號 && 馬達信號 && 啟動信號 && 自定義1,2
               if (output_event == 7  || output_event == 8 || output_event == 9  || output_event == 12  || output_event == 13) {
                   for(let i = 1; i <= 11; i++) {
                       let element1 = document.getElementById(`edit_pin${i}_1`);
                       if (element1) {
                           element1.disabled = true;
                       }
               
                       let element2 = document.getElementById(`edit_pin${i}_2`);
                       if (element2) {
                           element2.disabled = true;
                       }
                   }

                   if (Array.isArray(temp)) {
                       //過濾出包含 "edit_pin" 的字串
                       const filteredArray = temp.filter(item => item.includes("edit_pin"));
                       
                       const updatedArray = filteredArray.map(item => {
                           // 如果字串為空，直接返回
                           if (item.length === 0) {
                               return item;
                           }
                           //強制字串的最後一個字元更換為 '3'
                           return item.slice(0, -1) + '3';
                       });
                       
                       updatedArray.forEach(item => {
                           const radio = document.getElementById(item);
                           if (radio && radio.type === 'radio') {
                               radio.disabled = true;
                           }
                       });
                   }   
               } 
                old_output_even = output_event;

               if(radioButton){
                   radioButton.checked = true;
               }



               if(output_event == 7 || output_event == 8 || output_event == 9 ||output_event == 12  || output_event == 13 ){
                   const chekElement = document.getElementById(edit_output_pin);
                   if (chekElement) {
                       chekElement.disabled = false;  
                   }
               }

                
                document.querySelector("select[name='edit_event_option']").value = output_event;
                document.getElementById("edit_event_option").onchange = function() {
                 var selectedValue = this.value; 
                };
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed:", status, error);
            }
    });      
   }
 
}


// ================================
// 複製的output 資料 
// ================================

function copy_output_id(){

   var language = getCookie('language');
   if(language == "zh-cn"){
       var text_info ='若设定已存在，将会取代原有设定';
   }else if(language == "zh-tw"){
       var text_info ='若設定已存在，將會取代原有設定';
   }else{
       var text_info ='If the job input already exists, it will replace the original setting';
   }
   alertify.confirm( text_info, function (e) {
       if (e) {
           var to_job_id = document.getElementById("JobSelect1").value;
           if(to_job_id){

               document.getElementById('spinner').style.display = 'block';

               $.ajax({
                   url: "?url=Outputs/copy_output",
                   method: "POST",
                   data: { 
                       from_job_id: job_id,
                       to_job_id: to_job_id
                   },
                   success: function(response) {

                       document.getElementById('copy_output').style.display='none';
                       var responseData = JSON.parse(response);
                       alertify.alert(responseData.res_type, responseData.res_msg);
                       setTimeout(function() {
                           alertify.closeAll(); 
                           get_output_by_job_id(job_id);
                           document.getElementById('spinner').style.display = 'none';
                           document.querySelector(".main-content").classList.remove("overlay-active"); 
                       }, 1000);     
                       

                   },
                   error: function(xhr, status, error) {
                       
                   }
               });
       
           } 
       } else {
           // cancel
       }
   });
   document.getElementById('copy_output').style.display='none';
}

// ================================
// 收集所有被選中的輸入元件
// ================================
function collectPinValues(selector) {
   var pinOptions = document.querySelectorAll(selector);
   var selectedValues = [];

   pinOptions.forEach(function(option) {
       if (option.checked){ 
           var radioInfo = {
               id: option.id,
               value: option.value
           };
           selectedValues.push(radioInfo);
       }
   });

   return selectedValues;
}

// ================================
// 用job_id 刪除 對應的output 資料
// ================================

function delete_output_id(job_id,del_output_val){

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

  // 如果 job_id 有值才進行操作
   if (job_id) {
       alertify.confirm(
           title, // 標題
           text_info, // 提示文字
           function() {
               //使用者選擇「是」後執行刪除動作
               document.getElementById('spinner').style.display = 'block';

               $.ajax({
                   url: "?url=Outputs/delete_output",
                   method: "POST",
                   data: { 
                       job_id: job_id,
               output_event: del_output_val,
                   },
                   success: function(response) {
                       var responseData = JSON.parse(response);
                       alertify.alert(responseData.res_type, responseData.res_msg);

                       setTimeout(function() {
                           alertify.closeAll(); // 關閉所有 alertify 彈窗
                           updateEventSelectAndPins(responseData.old_input_pin); // 更新 pins
                           get_output_by_job_id(job_id); 
                           document.getElementById('spinner').style.display = 'none'; 
                           document.querySelector(".main-content").classList.remove("overlay-active");
                       }, 1000); 
                   },
                   error: function(xhr, status, error) {
                       alertify.error("刪除失敗，請稍後再試！");
                       document.getElementById('spinner').style.display = 'none';
                   }
               });
           },
           function() {
               //使用者選擇「否」時不做任何事
               document.querySelector(".main-content").classList.remove("overlay-active");
           }
       ).set('labels', {ok:'YES', cancel:'NO'}); // 修改按鈕文字
   }
}



// ================================
// 用job_id 找出 對應的output 資料
// ================================

function get_output_by_job_id(job_id){
   $.ajax({
       url: "?url=Outputs/get_output_by_job_id",
       method: "POST",
       data: { 
           job_id: job_id,
       },
       success: function(response) {
           var data = JSON.parse(response);
           var job_outputlist = data.job_outputlist;
           temp = data.temp;
           tempA = data.tempA;

           document.getElementById("output_jobid_select").innerHTML = job_outputlist;
           document.getElementById("JobSelect").style.display = 'none';
           document.getElementById("job_id").value = job_id;
       
           var rows = document.querySelectorAll('#output_jobid_select tr');
           rows.forEach(function(row) {
               row.addEventListener('click', function() { 
                   output_event = this.className; 
               });
           });

           
           var language = getCookie('language');
           if(language == "zh-cn"){
               document.getElementById('1') && (document.getElementById('1').textContent = 'OK');
               document.getElementById('2') && (document.getElementById('2').textContent = 'NG');
               document.getElementById('3') && (document.getElementById('3').textContent = '超出上限');
               document.getElementById('4') && (document.getElementById('4').textContent = '低于下限');
               document.getElementById('5') && (document.getElementById('5').textContent = '工序完成信号');
               document.getElementById('6') && (document.getElementById('6').textContent = '工作任务完成信号');
               document.getElementById('7') && (document.getElementById('7').textContent = '马达信号');
               document.getElementById('8') && (document.getElementById('8').textContent = '启动信号');
               document.getElementById('9') && (document.getElementById('9').textContent = '拆螺丝');
               document.getElementById('10') && (document.getElementById('10').textContent = 'BS');
               document.getElementById('11') && (document.getElementById('11').textContent = '条码');
               document.getElementById('12') && (document.getElementById('12').textContent = '自定义1');
               document.getElementById('13') && (document.getElementById('13').textContent = '自定义2');
               document.getElementById('14') && (document.getElementById('14').textContent = '顺时针转动');
               document.getElementById('15') && (document.getElementById('15').textContent = '逆时针转动');

           } 
           else if(language == "zh-tw"){
               document.getElementById('1') && (document.getElementById('1').textContent = 'OK');
               document.getElementById('2') && (document.getElementById('2').textContent = 'NG');
               document.getElementById('3') && (document.getElementById('3').textContent = '超出上限');
               document.getElementById('4') && (document.getElementById('4').textContent = '低於下限');
               document.getElementById('5') && (document.getElementById('5').textContent = '工序完成信號');
               document.getElementById('6') && (document.getElementById('6').textContent = '完工信號');
               document.getElementById('7') && (document.getElementById('7').textContent = '馬達信號');
               document.getElementById('8') && (document.getElementById('8').textContent = '啟動信號');
               document.getElementById('9') && (document.getElementById('9').textContent = '拆螺絲');
               document.getElementById('10') && (document.getElementById('10').textContent = 'BS');
               document.getElementById('11') && (document.getElementById('11').textContent = '條碼');
               document.getElementById('12') && (document.getElementById('12').textContent = '自定義1');
               document.getElementById('13') && (document.getElementById('13').textContent = '自定義2');
               document.getElementById('14') && (document.getElementById('14').textContent = '順時針轉動');
               document.getElementById('15') && (document.getElementById('15').textContent = '逆時針轉動');
           }
           
       },
       error: function(xhr, status, error) {
           console.error("AJAX request failed:", status, error);
       }
   }); 

}


// ================================
// 用job_id 修改 對應的output 資料
// ================================

function edit_output_id(){
   var output_event = document.getElementById("edit_event_option").value;
   var pinval       = collectPinValues('input[name="edit_pin_option"]');
   var pin_old      = pinval[0]['id'];
   var wave         = pinval[0]['value'];
   var match        = pin_old.match(/\d+/); 
   var output_pin   = match ? parseInt(match[0]) : null;

   var time_ms = 'edit_time'+ output_pin;
   var wave_on =  document.getElementById(time_ms).value;

   if(job_id){

       document.getElementById('spinner').style.display = 'block';

       $.ajax({
           url: "?url=Outputs/edit_output_event",
           method: "POST",
           data: { 
               job_id: job_id,
               output_pin: output_pin,
               output_event: output_event,
               wave: wave,
               wave_on: wave_on,
               old_output_event: old_output_event
           },
           success: function(response) {
               
               document.getElementById('edit_output').style.display='none';
               var responseData = JSON.parse(response);
               alertify.alert(responseData.res_type, responseData.res_msg);
               setTimeout(function() {
                   alertify.closeAll(); 
                   get_output_by_job_id(job_id);
                   updateEventSelectAndPins(responseData.old_output_pin);
                   document.getElementById('spinner').style.display = 'none';
                   document.querySelector(".main-content").classList.remove("overlay-active"); 
               }, 1000);                  
           },
           error: function(xhr, status, error) {
               console.error("AJAX request failed:", status, error);
           }
       });         
   }
}


// ================================
// 用job_id 找出 對應的output 資料
// ================================

function job_confirm(){
   var jobid = document.getElementById("JobNameSelect").value;
   localStorage.setItem("jobid", jobid);
   job_id = jobid;
   all_job = jobid;

   if(jobid){
       $.ajax({
           url: "?url=Outputs/get_output_by_job_id",
           method: "POST",
           data:{ 
               job_id: job_id,
           },
           success: function(response) {
               var data = JSON.parse(response);
               var job_outputlist = data.job_outputlist;
               temp = data.temp;
               tempA = data.tempA;


               document.getElementById("output_jobid_select").innerHTML = job_outputlist;
               document.getElementById("JobSelect").style.display = 'none';
               document.getElementById("job_id").value = job_id;
           
               var rows = document.querySelectorAll('#output_jobid_select tr');
               rows.forEach(function(row) {
                   row.addEventListener('click', function() { 

                       row.getAttribute('data-event');
                       output_event = row.getAttribute('data-event');
                  
                   });
               });

               var language = getCookie('language');
               if(language == "zh-cn"){
                   document.getElementById('1') && (document.getElementById('1').textContent = 'OK');
                   document.getElementById('2') && (document.getElementById('2').textContent = 'NG');
                   document.getElementById('3') && (document.getElementById('3').textContent = '超出上限');
                   document.getElementById('4') && (document.getElementById('4').textContent = '低于下限');
                   document.getElementById('5') && (document.getElementById('5').textContent = '工序完成信号');
                   document.getElementById('6') && (document.getElementById('6').textContent = '工作任务完成信号');
                   document.getElementById('7') && (document.getElementById('7').textContent = '马达信号');
                   document.getElementById('8') && (document.getElementById('8').textContent = '启动信号');
                   document.getElementById('9') && (document.getElementById('9').textContent = '拆螺丝');
                   document.getElementById('10') && (document.getElementById('10').textContent = 'BS');
                   document.getElementById('11') && (document.getElementById('11').textContent = '条码');
                   document.getElementById('12') && (document.getElementById('12').textContent = '自定义1');
                   document.getElementById('13') && (document.getElementById('13').textContent = '自定义2');
                   document.getElementById('14') && (document.getElementById('14').textContent = '顺时针转动');
                   document.getElementById('15') && (document.getElementById('15').textContent = '逆时针转动');

               } 
               else if(language == "zh-tw"){
                   document.getElementById('1') && (document.getElementById('1').textContent = 'OK');
                   document.getElementById('2') && (document.getElementById('2').textContent = 'NG');
                   document.getElementById('3') && (document.getElementById('3').textContent = '超出上限');
                   document.getElementById('4') && (document.getElementById('4').textContent = '低於下限');
                   document.getElementById('5') && (document.getElementById('5').textContent = '工序完成信號');
                   document.getElementById('6') && (document.getElementById('6').textContent = '完工信號');
                   document.getElementById('7') && (document.getElementById('7').textContent = '馬達信號');
                   document.getElementById('8') && (document.getElementById('8').textContent = '啟動信號');
                   document.getElementById('9') && (document.getElementById('9').textContent = '拆螺絲');
                   document.getElementById('10') && (document.getElementById('10').textContent = 'BS');
                   document.getElementById('11') && (document.getElementById('11').textContent = '條碼');
                   document.getElementById('12') && (document.getElementById('12').textContent = '自定義1');
                   document.getElementById('13') && (document.getElementById('13').textContent = '自定義2');
                   document.getElementById('14') && (document.getElementById('14').textContent = '順時針轉動');
                   document.getElementById('15') && (document.getElementById('15').textContent = '逆時針轉動');
               }

           },
           error: function(xhr, status, error) {
           
           }
       });
   }
}