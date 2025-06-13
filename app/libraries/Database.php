<?php

class Database{

    private $db_data;
    private $db_iDas;
    private $db_iDas_login;
    private $db_tools;

    public function __construct(){

        //  Linux 下若存在 extracted 資料夾則遞迴刪除
        if (PHP_OS_FAMILY === 'Linux') {
            $extractedPath = '/var/www/html/extracted';
            if (is_dir($extractedPath)) {
                $this->deleteDirectory($extractedPath);
            }
        }

        // 根據目前年份產生資料 DB 的檔名（例如：data2025.db）
        $year = date("Y");
        $data_db_name = "data{$year}.db";

        // 根據作業系統決定資料庫路徑
        $isLinux = PHP_OS_FAMILY === 'Linux';
        $basePath = $isLinux ? '/var/www/html/database/' : '../';
        $defaultDataPath = $isLinux ? '/var/www/html/tccidas/default_data.db' : '../default_data.db';

        // 初始化 data 資料庫，如果找不到就使用預設資料庫
        $this->db_data = $this->initPDO(
            $basePath . $data_db_name,
            $defaultDataPath
        );

        // 初始化 iDas 登入資料庫與工具資料庫
        $this->db_iDas_login = $this->initPDO($basePath . 'itccdev.db');
        $this->db_tools = $this->initPDO($basePath . 'tccdev.db');

        // 初始化主 iDas 設定資料庫（若不存在會從 tcccon.db 複製一份）
        $this->db_iDas = $this->initIpasDb($basePath);

        // 設定 SQLite 使用 UTF-8 編碼（僅影響內部 PRAGMA 設定）
        $this->execUTF8($this->db_iDas, $this->db_iDas_login, $this->db_tools);
    }

    /**
     * 遞迴刪除整個資料夾
     */
    private function deleteDirectory($dir) {
        if (!file_exists($dir)) return;
        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') continue;
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                unlink($path);
            }
        }
        rmdir($dir);
    }

    private function initPDO($path, $fallback = null){
        if (file_exists($path)) {
            return new PDO('sqlite:' . $path);
        } elseif ($fallback && file_exists($fallback)) {
            return new PDO('sqlite:' . $fallback);
        }
        return null;
    }

    private function initIpasDb($basePath){
        $idasPath = $basePath . 'idas_data.db';
        $tccconPath = $basePath . 'tcccon.db';
        if (!file_exists($idasPath) && file_exists($tccconPath)) {
            copy($tccconPath, $idasPath);
        }
        return new PDO('sqlite:' . $idasPath);
    }

    private function execUTF8(...$connections){
        foreach ($connections as $conn) {
            if ($conn instanceof PDO) {
                $conn->exec('PRAGMA encoding = "UTF-8"');
            }
        }
    }

    public function getDb_data() {
        return $this->db_data;
    }

    public function getDb_das() {
        return $this->db_iDas;
    }

    public function getDb_das_login() {
        return $this->db_iDas_login;
    }

    public function getDb_tools() {
        return $this->db_tools;
    }
}
