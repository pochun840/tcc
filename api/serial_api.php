<?php
echo "<pre>";
print_r($_SERVER);
echo "</pre>";
die();
// SFTP 伺服器的 IP 位址
/*$sftp_server = 'sftp://192.168.0.105'; 

// SFTP 登入資訊
$username = 'kls';             
$password = '12345678rd'; 

// 目標文件路徑（在 SFTP 伺服器上）
$remote_file = '/home/kls/tcc/resource/db_emmc/data_2023.db';

// 要下載的本地文件
$local_file = '../brian_test_20240826_sss.db';      

// 初始化 cURL 會話
$ch = curl_init();

// 設置 cURL 選項
curl_setopt($ch, CURLOPT_URL, $sftp_server . $remote_file);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");

// 執行 cURL 請求
$data = curl_exec($ch);
if ($data === false) {
    echo "文件下載失敗：" . curl_error($ch) . "\n";
} else {
    file_put_contents($local_file, $data);
    echo "文件下載成功。\n";
}

// 關閉 cURL 會話
curl_close($ch);*/
?>