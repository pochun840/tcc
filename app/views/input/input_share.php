<script>
/**
 * 定義全域變數
 */
var job_id;
var input_event;
var temp;              // 儲存需 disable 的 radio ID 陣列
var tempA;             // 儲存需 disable 的 option value 陣列
var tempB;
var jobDisabledOptions;
var selectedValue;     // 記錄被選取的值
var old_input_event;
var all_job;
var buttonDisabled = false;
var backgroundColorYellow = false;
var input_job;

class InputUnifiedManager {
    constructor() {
        this.keyStatus = 'input_unified';
        this.keyJob = 'input_unified_job';
    }

    isEnabled() {
        return localStorage.getItem(this.keyStatus) === '1';
    }

    getJob() {
        return localStorage.getItem(this.keyJob);
    }

    enable(jobId) {
        localStorage.setItem(this.keyStatus, '1');
        localStorage.setItem(this.keyJob, jobId);
    }

    disable() {
        localStorage.removeItem(this.keyStatus);
        localStorage.removeItem(this.keyJob);
    }
}

const inputUnifiedManager = new InputUnifiedManager();

// === 頁面載入時，若 unified 已啟動 → 自動套用狀態顯示 ===
if (inputUnifiedManager.isEnabled()) {
    const unifiedJob = inputUnifiedManager.getJob();
    if (unifiedJob) {
        job_id = unifiedJob;
        get_input_by_job_id(job_id);

        document.getElementById("job_id").value = job_id;
        document.getElementById("Button_Select").disabled = true;
        document.getElementById("job_id").style.backgroundColor = 'yellow';
    }
}

/**
 * DOM 載入完成時執行初始化
 */
$(document).ready(function () {
    highlight_row_input('input_table');  // 高亮資料列

    var all_input_job = '';
    job_id = all_input_job;
    input_job = all_input_job;

    // 如果已選擇 job_id，自動載入資料並禁用選擇按鈕
    if (job_id) {
        get_input_by_job_id(job_id);
        document.getElementById('Button_Select').disabled = true;
        document.getElementById('job_id').style.backgroundColor = 'yellow';
    }
});

/**
 * 自動移除 alertify 的 header 元素（多語系或自定義 alert 界面）
 */
document.addEventListener('DOMContentLoaded', function () {
    var observer = new MutationObserver(function (mutations) {
        mutations.forEach(function () {
            var headerElements = document.querySelectorAll('.ajs-header');
            headerElements.forEach(function (headerElement) {
                headerElement.parentNode.removeChild(headerElement);
            });
        });
    });

    // 監聽整個 body DOM 結構變化
    observer.observe(document.body, { childList: true, subtree: true });
});

/**
 * CRUD 操作處理主函式
 * @param {string} argument - 操作類型（new/edit/copy/del/unified）
 */
function crud_job_event(argument) {
    if (argument === 'new' && job_id !== '') {
        // disable 指定的 radio（通常與 pin 設定有關）
        if (Array.isArray(temp)) {
            temp.forEach(function (element) {
                var radio = document.getElementById(element);
                if (radio && radio.type === 'radio') {
                    radio.disabled = true;
                }
            });
        }

        // 禁用已選取的事件 option（防止重複選擇）
        if (Array.isArray(tempA)) {
            tempA.forEach(function (element) {
                var option = document.querySelector('#Event_Option option[value="' + element + '"]');
                if (option) {
                    if (option.selected) {
                        selectedValue = element; // 保存目前選中的值
                    }
                    option.disabled = true;
                    option.classList.add('disabled_input'); // 套用灰色樣式
                }
            });
        }

        document.getElementById('newinput').style.display = 'block';
        document.querySelector(".main-content").classList.add("overlay-active"); // UI 灰階鎖定
    }

    if (argument === 'del' && job_id !== '' && input_event !== '') {
        document.querySelector(".main-content").classList.add("overlay-active");
        delete_input_id(job_id, input_event, function () {
            // ✅ 刪除成功後，重置前端狀態
            resetInputEventState();

            // ✅ 重新撈 DB 狀態
            get_input_by_job_id(job_id);
        });
    }

    if (argument === 'edit' && job_id !== '' && input_event !== '') {
        var selectElement = document.getElementById('edit_Event_Option');

        // 將下拉選單整個鎖定
        if (selectElement) {
            selectElement.disabled = true;
            var options = selectElement.options;
            for (var i = 0; i < options.length; i++) {
                options[i].disabled = true;
                options[i].classList.add('disabled_input');
            }
        }

        // 禁用 radio
        if (Array.isArray(temp)) {
            temp.forEach(function (element) {
                var radio = document.getElementById(element);
                if (radio && radio.type === 'radio') {
                    radio.disabled = true;
                }
            });
        }

        // 載入編輯資料
        get_input_info(job_id, input_event);
        handleEventChange(input_event);

        document.querySelector(".main-content").classList.add("overlay-active");
        document.getElementById('edit_input').style.display = 'block';
    }

    if (argument === 'copy' && job_id !== '' && input_event !== '') {
        var jobinfo = <?php echo json_encode($data['job_list_new']); ?>;
        var from_job_name_bk = jobinfo[job_id]['job_name'];

        // 複製表單預填
        document.getElementById("from_job_id").value = job_id;
        document.getElementById("from_job_name").value = from_job_name_bk;

        // 禁用自身 job_id 於複製目的選單中
        var selectElement = document.getElementById('JobSelect1');
        var options = selectElement.getElementsByTagName('option');

        for (var i = 0; i < options.length; i++) {
            var optionValue = options[i].value;
            if (optionValue == job_id) {
                options[i].disabled = true;
                options[i].classList.add('disabled_input');
            }
        }

        document.querySelector(".main-content").classList.add("overlay-active");
        document.getElementById('copyinput').style.display = 'block';
    }

    if (argument === 'unified' && job_id !== '') {

        const unifiedOn = inputUnifiedManager.isEnabled();
        const unifiedJob = inputUnifiedManager.getJob();

        // =====================
        // ① unified 已啟動 → 嘗試解除
        // =====================
        if (unifiedOn) {

            // ❗ 必須回到當初 unified 的 job 才能解除
            if (job_id !== unifiedJob) {
                alertify.alert("請切回 " + unifiedJob + " 才能解除 unified");
                return;
            }

            inputUnifiedManager.disable();
            resetalignsubmit(unifiedJob);

            console.log("Unified OFF job:", unifiedJob);
            return;
        }

        // =====================
        // ② unified 關閉 → 套用到現在的 job
        // =====================
        inputUnifiedManager.enable(job_id);
        alignsubmit(job_id);

        console.log("Unified ON job:", job_id);
        return;
    }



}

function resetInputEventState() {

    // ===============================
    // 1️⃣ 清空前端暫存（保險寫法）
    // ===============================
    if (typeof temp !== 'undefined') temp.length = 0;
    if (typeof tempA !== 'undefined') tempA.length = 0;
    if (typeof temp_gateconfirm !== 'undefined') temp_gateconfirm.length = 0;
    if (typeof jobDisabledOptions !== 'undefined') jobDisabledOptions = {};

    // ===============================
    // 2️⃣ 只解除 Input 區塊的控制項
    // （避免誤傷其他頁面元素）
    // ===============================
    document
        .querySelectorAll('#input_jobid_select input, #input_jobid_select select')
        .forEach(el => {
            el.disabled = false;
            el.readOnly = false;
            el.classList.remove('disabled_input');
        });

    // ===============================
    // 3️⃣ 清除 Input 區塊的 radio / checkbox
    // ===============================
    document
        .querySelectorAll('#input_jobid_select input[type=radio], #input_jobid_select input[type=checkbox]')
        .forEach(el => el.checked = false);

    console.log('[Input] front-end input state reset');
}

$(document).ready(function () {

    // === 統一模式 (Unified) UI 狀態恢復 ===
    if (inputUnifiedManager.isEnabled()) {
        const unifiedJob = inputUnifiedManager.getJob();

        if (unifiedJob) {
            job_id = unifiedJob;

            // 套用 unified 外觀
            $("#job_id").val(unifiedJob).css("background-color", "yellow");
            $("#Button_Select").prop("disabled", true);

            // 載入該 job 的 input list
            get_input_by_job_id(unifiedJob);

            console.log("Unified UI Restored for job:", unifiedJob);
        }
    }

    // === 原本的初始化 ===
    highlight_row_input('input_table');
});

</script>

<style>
/* unified 啟用時 job_id 變色 */
#job_id.is-unified {
  background-color: yellow !important;
  color: #000;
}

/* unified 關閉時還原 */
#job_id:not(.is-unified) {
  background-color: transparent;
}

</style>
