
//Apply default values
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

    // Đặt select time_limit về 0
    document.getElementById('time_limit').selectedIndex = 0;

    document.getElementById('DT_time').value = 0;
    document.getElementById('TT_time').value = 0;

}


// Hàm chung để cập nhật trạng thái input theo lựa chọn Time limit Option
function updateThresholdInputs(revOptionEl, torInputEl, angInputEl) {
    const value = revOptionEl.value;

    switch (value) {
        case "0": // OFF
            torInputEl.disabled = true; torInputEl.value = "";
            angInputEl.disabled = true; angInputEl.value = "";
            break;
        case "1": // DT Time.
            torInputEl.disabled = false;
            angInputEl.disabled = true; angInputEl.value = "";
            break;
        case "2": // TT Time.
            torInputEl.disabled = true; torInputEl.value = "";
            angInputEl.disabled = false;
            break;
        case "3": // All
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
    const revOption = document.getElementById("time_limit");
    const torInput = document.getElementById("DT_time");
    const angInput = document.getElementById("TT_time");

    const editRevOption = document.getElementById("edit_time_limit");
    const editTorInput = document.getElementById("edit_DT_time");
    const editAngInput = document.getElementById("edit_TT_time");

    // Gán sự kiện cho newseq
    revOption.addEventListener("change", function () {
        updateThresholdInputs(revOption, torInput, angInput);
    });

    // Gán sự kiện cho editseq
    editRevOption.addEventListener("change", function () {
        updateThresholdInputs(editRevOption, editTorInput, editAngInput);
    });

    // Khởi tạo ban đầu (khi trang vừa load)
    updateThresholdInputs(revOption, torInput, angInput);
    updateThresholdInputs(editRevOption, editTorInput, editAngInput);
});
