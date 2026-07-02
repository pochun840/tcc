<?php

class Tool
{
    /** @var PDO|null */
    private $db_iDas_tools = null;

    /** @var string */
    private $lastError = '';

    /** @var string */
    private $databasePath = '';

    /** @var array<string,string> */
    private $resolvedTables = [];


    /**
     * tccdev.db 連線。
     *
     * 正式 Database accessor：
     *     getDb_tools()
     *
     * getDb_das_tools() 僅作為相容別名。
     */
    public function __construct()
    {
        $this->connectDatabase();
    }


    /**
     * 建立資料庫連線。
     *
     * 優先使用專案既有 Database 類別；
     * 若 Database 尚未初始化成功，再嘗試直接連線既知路徑。
     */
    private function connectDatabase(): void
    {
        try {
            $database = new Database();

            /*
             * 目前正式 Tool DB 是 tccdev.db，優先使用既有 accessor。
             */
            if (method_exists($database, 'getDb_tools')) {
                $pdo = $database->getDb_tools();

                if ($pdo instanceof PDO) {
                    $this->setPdo($pdo);
                    $this->databasePath = method_exists(
                        $database,
                        'getDb_tools_path'
                    )
                        ? (string)$database->getDb_tools_path()
                        : '/var/www/html/database/tccdev.db';

                    return;
                }
            }

            /*
             * 相容曾經使用 getDb_das_tools() 的版本。
             */
            if (method_exists($database, 'getDb_das_tools')) {
                $pdo = $database->getDb_das_tools();

                if ($pdo instanceof PDO) {
                    $this->setPdo($pdo);
                    $this->databasePath =
                        '/var/www/html/database/tccdev.db';

                    return;
                }
            }
        } catch (Throwable $exception) {
            /*
             * Database 建構子可能因其他 DB 初始化失敗而拋例外。
             * Tool 頁面不應因此失效，下面會直接連線 tccdev.db。
             */
            $this->setError(
                'Database class connection failed: '
                . $exception->getMessage()
            );
        }

        $this->connectKnownDatabasePath();
    }

    /**
     * Database 類別回傳 null 時的直接連線 fallback。
     */
    private function connectKnownDatabasePath(): void
    {
        $paths = [];

        if (defined('BASE_PATH')) {
            $paths[] = rtrim(
                (string)BASE_PATH,
                '/\\'
            )
                . DIRECTORY_SEPARATOR
                . 'tccdev.db';
        }

        $paths[] = '/var/www/html/database/tccdev.db';

        /*
         * __DIR__：/var/www/html/idas/app/models
         * dirname(__DIR__, 3)：/var/www/html
         */
        $paths[] = dirname(__DIR__, 3)
            . DIRECTORY_SEPARATOR
            . 'database'
            . DIRECTORY_SEPARATOR
            . 'tccdev.db';

        $paths = array_values(array_unique($paths));
        $checked = [];

        foreach ($paths as $path) {
            $checked[] = $path;
            clearstatcache(true, $path);

            if (!is_file($path)) {
                continue;
            }

            if (!is_readable($path)) {
                $this->setError(
                    'Tool database is not readable: '
                    . $path
                );

                continue;
            }

            try {
                $pdo = new PDO('sqlite:' . $path);

                $this->setPdo($pdo);
                $this->databasePath = $path;

                return;
            } catch (Throwable $exception) {
                $this->setError(
                    'Direct SQLite connection failed: '
                    . $exception->getMessage()
                );
            }
        }

        if (!$this->db_iDas_tools instanceof PDO) {
            $this->setError(
                'Tool database connection is unavailable. Checked: '
                . implode(', ', $checked)
            );
        }
    }

    private function setPdo(PDO $pdo): void
    {
        $pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );

        $pdo->setAttribute(
            PDO::ATTR_DEFAULT_FETCH_MODE,
            PDO::FETCH_ASSOC
        );

        $this->db_iDas_tools = $pdo;
        $this->lastError = '';
    }


    private function setError(string $message): void
    {
        $message = trim($message);

        if ($message === '') {
            return;
        }

        $this->lastError = $message;

        error_log(
            '[Tool Model] ' . $message
        );
    }


    public function getLastError(): string
    {
        return $this->lastError;
    }


    public function isConnected(): bool
    {
        return $this->db_iDas_tools
            instanceof PDO;
    }


    /**
     * 取得工具資料。
     */
    public function GetToolInfo(): array
    {
        $candidates = [];

        foreach (
            [
                'TABLE_NTCS_TOOLS',
                'TABLE_NTCS_TOOL',
            ] as $constantName
        ) {
            if (defined($constantName)) {
                $candidates[] = constant(
                    $constantName
                );
            }
        }

        $candidates = array_merge(
            $candidates,
            [
                'ntcs_tool_test',
                'tool_info',
                'ntcs_tool',
            ]
        );

        return $this->fetchFirstRow(
            'tool',
            $candidates
        );
    }


    /**
     * 取得控制器資料。
     */
    public function GetControllerInfo(): array
    {
        $candidates = [];

        if (defined('TABLE_NTCS_DEVICE')) {
            $candidates[] = TABLE_NTCS_DEVICE;
        }

        $candidates = array_merge(
            $candidates,
            [
                'ntcs_device_test',
                'device_info',
                'ntcs_device',
            ]
        );

        return $this->fetchFirstRow(
            'controller',
            $candidates
        );
    }


    /**
     * 從候選資料表中找出存在的表，讀取第一筆。
     */
    private function fetchFirstRow(
        string $type,
        array $candidates
    ): array {
        if (!$this->db_iDas_tools instanceof PDO) {
            $this->setError(
                'Cannot read '
                . $type
                . ' data because the DB handle is null.'
            );

            return [];
        }

        try {
            $table = $this->resolveTableName(
                $candidates
            );

            if ($table === '') {
                $this->setError(
                    ucfirst($type)
                    . ' table was not found. Checked: '
                    . implode(
                        ', ',
                        $this->normalizeTableCandidates(
                            $candidates
                        )
                    )
                );

                return [];
            }

            $sql = sprintf(
                'SELECT * FROM "%s" LIMIT 1',
                $table
            );

            $statement =
                $this->db_iDas_tools->prepare(
                    $sql
                );

            $statement->execute();

            $row = $statement->fetch(
                PDO::FETCH_ASSOC
            );

            $this->resolvedTables[$type] = $table;

            if (!is_array($row)) {
                $this->setError(
                    ucfirst($type)
                    . ' table is empty: '
                    . $table
                );

                return [];
            }

            return $row;

        } catch (Throwable $exception) {
            $this->setError(
                'Read '
                . $type
                . ' data failed: '
                . $exception->getMessage()
            );

            return [];
        }
    }


    /**
     * 找出實際存在的 SQLite table。
     */
    private function resolveTableName(
        array $candidates
    ): string {
        $candidates =
            $this->normalizeTableCandidates(
                $candidates
            );

        if (empty($candidates)) {
            return '';
        }

        $statement =
            $this->db_iDas_tools->prepare(
                'SELECT name
                   FROM sqlite_master
                  WHERE type = :type
                    AND name = :name
                  LIMIT 1'
            );

        foreach ($candidates as $table) {
            $statement->execute([
                ':type' => 'table',
                ':name' => $table,
            ]);

            if (
                $statement->fetchColumn()
                !== false
            ) {
                return $table;
            }
        }

        return '';
    }


    private function normalizeTableCandidates(
        array $candidates
    ): array {
        $normalized = [];

        foreach ($candidates as $candidate) {
            $candidate = trim(
                (string)$candidate
            );

            if (
                $candidate === ''
                || !preg_match(
                    '/^[A-Za-z_][A-Za-z0-9_]*$/',
                    $candidate
                )
            ) {
                continue;
            }

            $normalized[] = $candidate;
        }

        return array_values(
            array_unique($normalized)
        );
    }


    /**
     * Tools/db_debug 使用的診斷資料。
     */
    public function GetDatabaseDebug(): array
    {
        $debug = [
            'connected' => $this->isConnected(),
            'database_path' => $this->databasePath,
            'last_error' => $this->lastError,
            'resolved_tables' =>
                $this->resolvedTables,
            'tables' => [],
        ];

        if (!$this->db_iDas_tools instanceof PDO) {
            return $debug;
        }

        try {
            $statement =
                $this->db_iDas_tools->query(
                    "SELECT name
                       FROM sqlite_master
                      WHERE type = 'table'
                        AND name NOT LIKE 'sqlite_%'
                      ORDER BY name"
                );

            $tables = $statement->fetchAll(
                PDO::FETCH_COLUMN
            );

            $debug['tables'] = is_array($tables)
                ? $tables
                : [];

        } catch (Throwable $exception) {
            $debug['last_error'] =
                'Read DB schema failed: '
                . $exception->getMessage();
        }

        return $debug;
    }
}
