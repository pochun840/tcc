
function delete_jobid(jobid) {
    if (!jobid) return;

    const language = getCookie('language');
    let text_info, title;

    if (language === "zh-cn") {
        text_info = '你确定吗？';
        title = '删除工作';
    } else if (language === "zh-tw") {
        text_info = '你確定嗎？';
        title = '刪除工作';
    } else {
        text_info = 'Are you sure?';
        title = 'Delete Job';
    }

    // ✅ 先詢問再送出 AJAX
    alertify.confirm(title, text_info, function (confirmed) {
        if (confirmed) {
            document.getElementById('spinner').style.display = 'block';

            $.ajax({
                url: "?url=Jobs/delete_jobid",
                method: "POST",
                data: { jobid: jobid },
                success: function(response) {
                    const responseData = JSON.parse(response);
                    setTimeout(function() {
                        document.getElementById('spinner').style.display = 'none';

                        alertify.alert(responseData.res_type, responseData.res_msg, function () {
                            history.go(0); // 手動刷新
                        });

                        setTimeout(() => {
                            alertify.closeAll();
                            history.go(0);
                        }, 3000);
                    }, 1000);
                },
                error: function(xhr, status, error) {
                    console.error("Delete failed:", error);
                    document.getElementById('spinner').style.display = 'none';
                }
            });
        }
    }, function () {
        // 使用者按「取消」的時候什麼都不做
        document.querySelector(".main-content").classList.remove("overlay-active");
    });
}



var oldjobname ='';
var old_jobid  = '';
function cound_job(argument){

    var table = document.getElementById('job_table');
    var selectedRow = table.querySelector('.selected');
    var jobid  = selectedRow ? selectedRow.cells[0].innerText : null;
    oldjobname = selectedRow ? selectedRow.cells[1].innerText : null;
    old_jobid  = selectedRow ? selectedRow.cells[0].innerText : null;
    if(argument == 'del' && jobid != null){
        document.querySelector(".main-content").classList.add("overlay-active");
        delete_jobid(jobid);
    }

    if(argument =="edit" && jobid != null){
        document.querySelector(".main-content").classList.add("overlay-active");
        edit_job(jobid);
    }

    if(argument =="new"){
        document.querySelector(".main-content").classList.add("overlay-active");
        create_job();
    }

    if(argument =="copy" && jobid != null){
        document.querySelector(".main-content").classList.add("overlay-active");
        copy_job(jobid);
    }

}


function readFromLocalStorage(key) {
    return localStorage.getItem(key);
}

function create_job() {
    // Hiển thị form
    document.getElementById('newjob').style.display = 'block';

    // Set giá trị mặc định
    document.getElementById('rev_speed').value = 550;
    document.getElementById('rev_force').value = 50;

    // Đặt select rev_option về 0
    document.getElementById('rev_option').selectedIndex = 0;

    // Set giá trị threshold trước khi trigger change
    document.getElementById('threshold_tor').value = 0.0;  
    document.getElementById('threshold_ang').value = 0;

    /*
    // Gọi trực tiếp hàm
    updateThresholdInputs(
        document.getElementById("rev_option"),
        document.getElementById("threshold_tor"),
        document.getElementById("threshold_ang")
    );
    */

    // Thiết lập các radio/checkbox
    document.getElementById('rev_direction_CCW').checked = true;
    document.getElementById('job_ok').checked = true;
    document.getElementById('job_ok_stop_off').checked = true;
}

function copy_job(jobid){
    document.getElementById('copyjob').style.display = 'block';
    copy_data(jobid);
}

function updatejob() {

    var jobid      = document.getElementById("edit_jobid").value;
    var jobname    = document.getElementById("edit_jobname").value;
    var speedvalue = document.getElementById("edit_rev_speed").value;
    var forcevalue = document.getElementById("edit_rev_force").value;

    // Các giá trị mới được thêm
    var rev_option     = document.getElementById("edit_rev_option").value;
    var threshold_tor  = document.getElementById("edit_threshold_tor").value;
    var threshold_ang  = document.getElementById("edit_threshold_ang").value;

    // Đảm bảo định dạng số
    threshold_tor  = parseFloat(threshold_tor) || 0.0;
    threshold_ang  = parseInt(threshold_ang) || 0;

    var directionValue = document.querySelector('input[name="edit_direction"]:checked').value;
    var jobokValue     = document.querySelector('input[name="edit_job_ok"]:checked').value;
    var stopjobValue   = document.querySelector('input[name="edit_job_ok_stop"]:checked').value;

    //驗證
    let check = input_check_editjob();
    
    if(check) {
        document.getElementById('spinner').style.display = 'block';

        $.ajax({
            url: "?url=Jobs/update_job",
            method: "POST",
            data: { 
                jobid: jobid,
                jobname: jobname,
                speedvalue: speedvalue,
                forcevalue: forcevalue,
                directionValue: directionValue,
                jobokValue: jobokValue,
                stopjobValue: stopjobValue,
                rev_option: rev_option,
                threshold_tor: threshold_tor,
                threshold_ang: threshold_ang
            },
            success: function(response) {
                var responseData = JSON.parse(response);

                setTimeout(function() {
                    document.getElementById('spinner').style.display = 'none';

                    alertify.alert(responseData.res_type, responseData.res_msg, function () {});

                    // Lưu lại vào localStorage
                    localStorage.setItem('jobid', jobid);
                    localStorage.setItem('jobname', jobname);
                    localStorage.setItem('rev_speed', speedvalue);
                    localStorage.setItem('rev_force', forcevalue);
                    localStorage.setItem('direction', directionValue);

                    // Lưu thêm 3 giá trị mới
                    localStorage.setItem('rev_option', rev_option);
                    localStorage.setItem('threshold_tor', threshold_tor);
                    localStorage.setItem('threshold_ang', threshold_ang);

                    setTimeout(function () {
                        alertify.closeAll();
                        history.go(0);
                    }, 3000);
                }, 1000);
            },
            error: function(xhr, status, error) {
                console.error("AJAX error:", error);
            }
        });
    }
}


function edit_job(jobid) {
    if(jobid){
        $.ajax({
            url: "?url=Jobs/search_job",
            method: "POST",
            data:{ 
                jobid: jobid
            },
            success: function(response) {
                var responseJSON = JSON.stringify(response);
                var cleanString = responseJSON.replace(/Array|\\n/g, '');
                var cleanString = cleanString.substring(2, cleanString.length - 2);
                var [, jobid] = cleanString.match(/\[job_id]\s*=>\s*([^ ]+)/) || [, null];
                var [, jobname] = cleanString.match(/\[job_name]\s*=>\s*([^ ]+)/) || [, null];
                var [, rev_direction] = cleanString.match(/\[rev_direction]\s*=>\s*([^ ]+)/) || [, null];
                var [, rev_force] = cleanString.match(/\[rev_force]\s*=>\s*([^ ]+)/) || [, null];
                var [, rev_speed] = cleanString.match(/\[rev_speed]\s*=>\s*([^ ]+)/) || [, null];
                var [, job_ok] = cleanString.match(/\[job_ok]\s*=>\s*([^ ]+)/) || [, null];
                var [, job_ok_stop] = cleanString.match(/\[job_ok_stop]\s*=>\s*([^ ]+)/) || [, null];

                var [, rev_option] = cleanString.match(/\[rev_option]\s*=>\s*([^ ]+)/) || [, null];
                var [, threshold_tor] = cleanString.match(/\[threshold_tor]\s*=>\s*([^ ]+)/) || [, null];
                var [, threshold_ang] = cleanString.match(/\[threshold_ang]\s*=>\s*([^ ]+)/) || [, null];
          
                document.getElementById('editjob').style.display = 'block';


                document.getElementById("edit_jobid").value = jobid;
                document.getElementById("edit_jobname").value = jobname;

                document.getElementById("edit_rev_speed").value = rev_speed;
                document.getElementById("edit_rev_force").value = rev_force;

                document.getElementById("edit_rev_option").value = rev_option;
                document.getElementById("edit_threshold_tor").value = threshold_tor;
                document.getElementById("edit_threshold_ang").value = threshold_ang;

                var radioButtons = document.getElementsByName("edit_direction");
                setRadioButtonValue(radioButtons, rev_direction);

                var radioButtons_job = document.getElementsByName("edit_job_ok");
                setRadioButtonValue(radioButtons_job, job_ok);

                var radioButtons_stop_job = document.getElementsByName("edit_job_ok_stop");
                setRadioButtonValue(radioButtons_stop_job, job_ok_stop);

                
                // ✅ Gọi lại update hiển thị threshold sau khi gán dữ liệu
                updateThresholdInputs(
                    document.getElementById("edit_rev_option"),
                    document.getElementById("edit_threshold_tor"),
                    document.getElementById("edit_threshold_ang")
                );
                
              
            },
            error: function(xhr, status, error) {
                
            }
        });
    }   
}

// JOB 驗證
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


// Hàm chung để cập nhật trạng thái input theo lựa chọn reverse option
function updateThresholdInputs(revOptionEl, torInputEl, angInputEl) {
    const value = revOptionEl.value;

    switch (value) {
        case "0": // OFF
            torInputEl.disabled = true; torInputEl.value = "";
            angInputEl.disabled = true; angInputEl.value = "";
            break;
        case "1": // Threshold Tor.
            torInputEl.disabled = false;
            angInputEl.disabled = true; angInputEl.value = "";
            break;
        case "2": // Threshold Ang.
            torInputEl.disabled = true; torInputEl.value = "";
            angInputEl.disabled = false;
            break;
        case "3": // All (Torque & Angle)
        case "4": // All (Torque First)
            torInputEl.disabled = false;
            angInputEl.disabled = false;
            break;
        default:
            torInputEl.disabled = true;
            angInputEl.disabled = true;
    }
}

// Gán sự kiện khi DOM đã load
document.addEventListener("DOMContentLoaded", function () {
    const revOption = document.getElementById("rev_option");
    const torInput = document.getElementById("threshold_tor");
    const angInput = document.getElementById("threshold_ang");

    const editRevOption = document.getElementById("edit_rev_option");
    const editTorInput = document.getElementById("edit_threshold_tor");
    const editAngInput = document.getElementById("edit_threshold_ang");

    // Gán sự kiện cho newjob
    revOption.addEventListener("change", function () {
        updateThresholdInputs(revOption, torInput, angInput);
    });

    // Gán sự kiện cho editjob
    editRevOption.addEventListener("change", function () {
        updateThresholdInputs(editRevOption, editTorInput, editAngInput);
    });

    // Khởi tạo ban đầu (khi trang vừa load)
    updateThresholdInputs(revOption, torInput, angInput);
    updateThresholdInputs(editRevOption, editTorInput, editAngInput);
});
