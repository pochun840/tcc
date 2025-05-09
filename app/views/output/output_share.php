<script>
    // 全域變數定義
var eventOption = document.getElementById('Event_Option'); 
var job_id, output_event, temp, tempA, output_job, all_job, del_output_val, output_pinval, dataoutput_pin_val;
var buttonDisabled = false;
var backgroundColorYellow = false;
var old_output_event;

// 初始載入
$(document).ready(function () {
    highlight_row_input('output_table');
    var all_output_job = '';
    job_id = all_output_job;
    output_job = all_output_job;

    if (job_id) {
        get_output_by_job_id(job_id);
        document.getElementById('Button_Select').disabled = true;
        document.getElementById('job_id').style.backgroundColor = 'yellow';
    }
});

// 清除 alertify header
new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        document.querySelectorAll('.ajs-header').forEach(header => header.remove());
    });
}).observe(document.body, { childList: true, subtree: true });

// Modal 外部點擊關閉
window.onclick = function(event) {
    var modal = document.getElementById('newinput');
    if (event.target === modal) {
        modal.style.display = "none";
    }
};

function crud_job_event(argument) {
    var table = document.getElementById('output_table');
    var selectedRow = table.querySelector('tr.selected');
    if (selectedRow) {
        output_event = del_output_val = selectedRow.getAttribute('data-event');
        var pinElem = selectedRow.querySelector('[data-outputpin]');
        output_pinval = pinElem ? pinElem.getAttribute('data-outputpin') : null;
    }

    if (argument === 'del' && job_id && del_output_val) {
        if (!document.querySelectorAll('#output_jobid_select tr.selected').length) return;
        document.querySelector(".main-content").classList.add("overlay-active");
        delete_output_id(job_id, del_output_val);
    }

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

        var filtered_array = temp.filter(id => id.includes('pin') && !id.includes('edit_pin'));
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
    }

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
    }

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
    }

    if (argument === 'unified' && job_id) {
        enableButton();
        resetBackgroundColor();
        if (output_job !== job_id) {
            alignsubmit(job_id);
        } else {
            resetalignsubmit(job_id);
        }
    }
}

</script>