<script>
// ----------------------------------------------------
// 全域變數定義
// ----------------------------------------------------
var eventOption = document.getElementById('Event_Option'); 
var job_id, output_event, temp, tempA, output_job, all_job, del_output_val, output_pinval, dataoutput_pin_val;
var buttonDisabled = false;
var backgroundColorYellow = false;
var old_output_event;
var jobDisabledOptions;

// ----------------------------------------------------
// 統一管理 unified 狀態（localStorage）
// ----------------------------------------------------
class UnifiedManager {
    constructor() {
        this.keyStatus = 'output_unified';
        this.keyJob    = 'output_unified_job';
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

const unifiedManager = new UnifiedManager();

// ----------------------------------------------------
// 頁面初始載入
// ----------------------------------------------------
$(document).ready(function () {
    highlight_row_input('output_table');

    // 原本的 all_output_job 邏輯
    var all_output_job = '';
    job_id   = all_output_job;
    output_job = all_output_job;

    if (job_id) {
        get_output_by_job_id(job_id);
        document.getElementById('Button_Select').disabled = true;
        document.getElementById('job_id').style.backgroundColor = 'yellow';
    }
});

// ----------------------------------------------------
// 清除 alertify header
// ----------------------------------------------------
new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        document.querySelectorAll('.ajs-header').forEach(header => header.remove());
    });
}).observe(document.body, { childList: true, subtree: true });

// ----------------------------------------------------
// Modal 外部點擊關閉
// ----------------------------------------------------
window.onclick = function(event) {
    var modal = document.getElementById('newinput');
    if (event.target === modal) {
        modal.style.display = "none";
    }
};

// ----------------------------------------------------
// UI 幫手函式：套用 / 清除 unified 樣式
// ----------------------------------------------------
function applyUnifiedUI(jobId) {
    buttonDisabled = true;
    backgroundColorYellow = true;

    const btn = document.getElementById('Button_Select');
    const jobInput = document.getElementById('job_id');

    if (btn) btn.disabled = true;
    if (jobInput) {
        jobInput.style.backgroundColor = 'yellow';
        jobInput.value = jobId;
    }

    // 標記工作列為選取 + 黃色（如果有 job 列表）
    document.querySelectorAll('#output_jobid_select tr').forEach(tr => {
        if (tr.dataset.jobid == jobId) {
            tr.classList.add('selected');
            tr.classList.add('yellow-highlight');
        } else {
            tr.classList.remove('yellow-highlight');
        }
    });
}

function clearUnifiedUI() {
    buttonDisabled = false;
    backgroundColorYellow = false;

    const btn = document.getElementById('Button_Select');
    const jobInput = document.getElementById('job_id');

    if (btn) btn.disabled = false;
    if (jobInput) jobInput.style.backgroundColor = '';

    document.querySelectorAll('#output_jobid_select tr').forEach(tr => {
        tr.classList.remove('yellow-highlight');
    });
}

// ----------------------------------------------------
// 主要的 CRUD 控制
// ----------------------------------------------------
function crud_job_event(argument) {
    var table = document.getElementById('output_table');
    var selectedRow = table ? table.querySelector('tr.selected') : null;

    if (selectedRow) {
        output_event  = del_output_val = selectedRow.getAttribute('data-event');
        var pinElem   = selectedRow.querySelector('[data-outputpin]');
        output_pinval = pinElem ? pinElem.getAttribute('data-outputpin') : null;
    }

    // ---------------- 刪除 ----------------
    if (argument === 'del' && job_id && del_output_val) {
        if (!document.querySelectorAll('#output_jobid_select tr.selected').length) return;
        document.querySelector(".main-content").classList.add("overlay-active");
        delete_output_id(job_id, del_output_val);
        return;
    }

    // ---------------- 新增 ----------------
    if (argument === 'new' && job_id) {
        if (Array.isArray(tempA)) {
            tempA.forEach(val => {
                const opt = eventOption.querySelector(`option[value="${val}"]`);
                if (opt) opt.disabled = true;
            });
        }

        if (Array.isArray(temp)) {
            temp.forEach(id => {
                const radio = document.getElementById(id);
                if (radio?.type === 'radio') radio.disabled = true;
            });
        }

        var filtered_array = Array.isArray(temp)
            ? temp.filter(id => id.includes('pin') && !id.includes('edit_pin'))
            : [];

        disableElements(filtered_array);

        document.querySelector(".main-content").classList.add("overlay-active");
        document.getElementById('new_output').style.display = 'block';

        eventOption.addEventListener('change', function () {
            const selected = parseInt(this.value);
            const disableOptions = [7, 8, 9];

            toggleElementsInRange(1, 10, 3, filtered_array);
            if (!disableOptions.includes(selected)) {
                disableElements(filtered_array);
            }
        });
        return;
    }

    // ---------------- 編輯 ----------------
    if (argument === 'edit' && job_id && output_event) {
        if (!document.querySelectorAll('#output_jobid_select tr.selected').length) {
            getLanguageMessage('language');
            return;
        }

        document.querySelectorAll('input[type="text"], input[type="radio"]').forEach(el => el.disabled = false);

        const selectEl = document.getElementById('edit_event_option');
        if (selectEl) {
            selectEl.disabled = true;
            Array.from(selectEl.options).forEach(opt => {
                opt.disabled = true;
                opt.classList.add('disabled_input');
            });
        }

        if (Array.isArray(temp)) {
            temp.forEach(id => {
                const radio = document.getElementById(id);
                if (radio?.type === 'radio') radio.disabled = true;
            });

            const filtered_C = temp.filter(id => id.includes("edit_pin"));
            filtered_C.forEach(id => {
                const match = id.match(/(edit_pin\d+)_(\d+)/);
                if (match) {
                    const baseId = match[1];
                    for (let i = 1; i <= 3; i++) {
                        const radioId = `${baseId}_${i}`;
                        const radio = document.getElementById(radioId);
                        if (radio?.type === 'radio') radio.disabled = true;
                    }
                    let timeId = 'edit_time' + baseId.slice(3).replace('t_pin', '');
                    const timeEl = document.getElementById(timeId);
                    if (timeEl) timeEl.disabled = true;
                    if ([7, 8, 9].includes(output_event)) disableTimeFields();
                }
            });
        }

        if (output_pinval) {
            [`edit_pin${output_pinval}_1`, `edit_pin${output_pinval}_2`, `edit_pin${output_pinval}_3`, `edit_time${output_pinval}`]
            .forEach(id => {
                const el = document.getElementById(id);
                if (el) el.disabled = false;
            });
        }

        document.querySelector(".main-content").classList.add("overlay-active");
        document.getElementById('edit_output').style.display = 'block';
        get_output_info(job_id, output_event);
        return;
    }

    // ---------------- 複製 ----------------
    if (argument === 'copy' && job_id && output_event) {
        const jobinfo = <?php echo json_encode($data['job_list_new']); ?>;
        document.getElementById("from_job_id").value = job_id;
        document.getElementById("from_job_name").value = jobinfo[job_id]['job_name'];

        const options = document.getElementById('JobSelect1').options;
        for (let opt of options) {
            if (opt.value === job_id) {
                opt.disabled = true;
                opt.classList.add('disabled_input');
            }
        }

        if (!document.querySelectorAll('#output_jobid_select tr.selected').length) {
            getLanguageMessage('language');
            return;
        }

        document.querySelector(".main-content").classList.add("overlay-active");
        document.getElementById('copy_output').style.display = 'block';
        return;
    }

    // =================================================
    //  Unified 套用 / 解除（簡化版：有就關、沒有就開）
    // =================================================
    if (argument === 'unified') {

        const isUnified  = unifiedManager.isEnabled();
        const savedJobId = unifiedManager.getJob();

        // =========================================
        // ① 若目前 unified 已啟動 → 一律視為「解除」
        //    （不管現在選到哪一個 Job）
        // =========================================
        if (isUnified) {

            // 優先用當初套用 unified 的 job，沒有就用現在畫面上的 job_id
            const jobToReset = savedJobId || job_id || null;

            unifiedManager.disable();          // 清 localStorage 標記
            resetalignsubmit(jobToReset);      // 通知後端：解除 unified，並重畫 UI

            console.log("Unified OFF. jobToReset =", jobToReset);
            return;
        }

        // =========================================
        // ② 若目前 unified 沒有啟動 → 對「目前選擇的 job」啟用 unified
        // =========================================
        const currentJobId = job_id;
        if (!currentJobId) {
            alertify.alert('Info', '請先選擇一個工作 (Job) 再套用 unified');
            return;
        }

        unifiedManager.enable(currentJobId);   // 記錄這次 unified 的 job
        alignsubmit(currentJobId);             // 實際做「套用到所有工作」

        console.log("Unified ON. job =", currentJobId);
        return;
    }


}

// ----------------------------------------------------
// 進入頁面時，自動恢復 unified 狀態（如果有）
// ----------------------------------------------------
document.addEventListener("DOMContentLoaded", function () {

    const isUnified = unifiedManager.isEnabled();
    const job = unifiedManager.getJob();

    if (isUnified && job) {

        // 設定全域 job_id，讓後面 unified 判斷用
        job_id = job;

        // 恢復選取列
        const row = document.querySelector(`#output_jobid_select tr[data-jobid="${job}"]`);
        if (row) row.classList.add("selected");

        // 恢復 unified UI（只動畫面，不再去叫 alignsubmit()）
        const btn = document.getElementById('Button_Select');
        const jobInput = document.getElementById('job_id');

        if (btn) btn.disabled = true;
        if (jobInput) {
            jobInput.style.backgroundColor = 'yellow';
            jobInput.value = job;
        }

        // 重新載入 Job 的輸出設定（單純更新畫面）
        if (typeof get_output_by_job_id === 'function') {
            get_output_by_job_id(job);
        }

        console.log("Unified auto-restored for job:", job);
    }
});


// ----------------------------------------------------
// 這兩個函式建議改成「明確設狀態」，不要 toggle
// ----------------------------------------------------
function alignsubmit(jobId) {
    if (!jobId) return;

    $.ajax({
        url: "?url=Outputs/output_alljob",
        method: "POST",
        data: { job_id: jobId },
        success: function (response) {
            if (typeof get_output_by_job_id === 'function') {
                get_output_by_job_id(jobId);
            }
            applyUnifiedUI(jobId); // 統一在這裡設定 UI
        },
        error: function (xhr, status, error) {
            console.error("alignsubmit error:", status, error);
        }
    });
}

function resetalignsubmit(jobId) {

    // 從前端角度：一定要先清掉 UI
    clearUnifiedUI();

    // 如果你原本就有「job_id_new」邏輯，可照舊呼叫
    $.ajax({
        url: "?url=Outputs/output_alljob",
        method: "POST",
        data: { job_id_new: 0 }, // 表示解除 unified（依你原本後端實作）
        success: function (response) {
            if (jobId && typeof get_output_by_job_id === 'function') {
                get_output_by_job_id(jobId);
            }
        },
        error: function (xhr, status, error) {
            console.error("resetalignsubmit error:", status, error);
        }
    });
}


// =====================================
// 點選 Job 列時，更新 job_id（關鍵修正）
// =====================================
document.addEventListener('click', function (e) {
    const row = e.target.closest('#output_jobid_select tr');
    if (!row) return;

    // 取消其他選取
    document.querySelectorAll('#output_jobid_select tr.selected')
        .forEach(r => r.classList.remove('selected'));

    // 標記選取
    row.classList.add('selected');

    // ⭐ 更新全域 job_id（這是你的 unified 判斷會用到的）
    const selectedJob = row.getAttribute('data-jobid');
    if (selectedJob) {
        job_id = selectedJob;
        document.getElementById('job_id').value = selectedJob;
    }
});

</script>
