function startApiPolling(url = '?url=Dashboards/get_new_data', interval = 1000) {
    async function fetchData() {
        try {
            const response = await fetch(url);
            
            if (!response.ok) {
                throw new Error(`HTTP 錯誤！狀態碼: ${response.status}`);
            }

            const data = await response.json();
            console.log('最新資料:', data);
            
            // 在此處更新頁面資料
            updateDataOnPage(data);

        } catch (error) {
            console.error('API 呼叫錯誤:', error);
        }
    }

    // 立即呼叫一次 API，然後每 interval 毫秒呼叫一次
    fetchData();
    setInterval(fetchData, interval);
}

// 更新頁面資料的函數
function updateDataOnPage(data) {

    if (!data) return;
    const systemSnField = document.getElementById('data_time');
    if (systemSnField) {
        // 设置输入框的值
        systemSnField.value = data.data_time || '--';  // 如果没有 system_sn，则设置为 '--'
    }

    document.getElementById('job_name').value = data.job_name || '--';
    document.getElementById('seq_name').value = data.seq_name || '--';
    document.getElementById('max_screw_count').value = data.max_screw_count || '--';
    document.getElementById('max_screw_count').value = data.max_screw_count || '--';
    document.getElementById('fasten_torque').innerText = data.fasten_torque || 'N/A';
    document.getElementById('fasten_angle').innerText  = data.fasten_angle  || 'N/A';
    document.getElementById('fasten_status_explain').innerText = data.fasten_status_explain || 'N/A';
    document.getElementById('fasten_status_unit_explain').innerText = data.fasten_status_unit_explain || '';

    const bgColor = data.fasten_status_bg || '';  
    document.getElementById('fasten_status_bg').style.backgroundColor = bgColor;

}

// 開始即時 API 呼叫，每 3 秒更新一次資料
startApiPolling();