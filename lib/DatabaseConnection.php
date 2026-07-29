<?php
/**
 * CATS
 * Database Connection Library — MySQL / PostgreSQL PDO implementation
 *
 * Connects using MySQL (pdo_mysql) when DATABASE_PORT=3306 (Docker/production),
 * or PostgreSQL (pdo_pgsql) when DATABASE_PORT=5432 (local dev).
 * MySQL-specific SQL is passed through as-is; PostgreSQL mode translates at runtime.
 */
class DatabaseConnection
{
    static private $_instance;
    private $_pdo        = null;
    private $_stmt       = null;
    private $_timeZone   = 0;
    private $_dateDMY    = false;
    private $_inTransaction = false;
    private $_foundRows  = 0;
    private $_bufferedRows = null;
    private $_isMysql    = false;
    private $_queryHadRows = false;

    // -----------------------------------------------------------------------
    // Singleton
    // -----------------------------------------------------------------------

    public static function getInstance()
    {
        if (self::$_instance == null)
        {
            self::$_instance = new DatabaseConnection();
            self::$_instance->connect();
            self::$_instance->setInTransaction(false);
        }

        if (isset($_SESSION['CATS']) && $_SESSION['CATS']->isLoggedIn())
        {
            self::$_instance->_timeZone = $_SESSION['CATS']->getTimeZoneOffset();
            self::$_instance->_dateDMY  = $_SESSION['CATS']->isDateDMY();
        }
        else
        {
            self::$_instance->_timeZone = OFFSET_GMT * -1;
            self::$_instance->_dateDMY  = false;
        }

        return self::$_instance;
    }

    private function __construct() {}
    private function __clone()    {}

    public function setInTransaction($tf)
    {
        return ($this->_inTransaction = $tf);
    }

    /** Returns the underlying PDO connection object. */
    public function getConnection()
    {
        return $this->_pdo;
    }

    /** Returns true when connected to MySQL, false for PostgreSQL. */
    public function isMysql()
    {
        return $this->_isMysql;
    }

    // -----------------------------------------------------------------------
    // Connection
    // -----------------------------------------------------------------------

    public function connect()
    {
        $host   = defined('DATABASE_HOST') ? DATABASE_HOST : '127.0.0.1';
        $user   = defined('DATABASE_USER') ? DATABASE_USER : '';
        $pass   = defined('DATABASE_PASS') ? DATABASE_PASS : '';
        $dbName = defined('DATABASE_NAME') ? DATABASE_NAME : '';
        $port   = defined('DATABASE_PORT') ? (int) DATABASE_PORT : 5432;

        // Use MySQL driver when port is 3306 (Docker/production), PostgreSQL otherwise (local dev).
        $this->_isMysql = ($port === 3306);

        if ($this->_isMysql) {
            $dsn  = "mysql:host={$host};port={$port};dbname={$dbName};charset=utf8mb4";
            $hint = "Connect test: mysql -h {$host} -P {$port} -u {$user} {$dbName}";
        } else {
            $dsn  = "pgsql:host={$host};port={$port};dbname={$dbName}";
            $hint = "Connect test: psql -h {$host} -p {$port} -U {$user} {$dbName}";
        }

        try
        {
            $this->_pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_WARNING,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);

            if ($this->_isMysql) {
                $this->_pdo->exec("SET SESSION sql_mode = ''");
            }
        }
        catch (\PDOException $e)
        {
            $driver = $this->_isMysql ? 'MySQL' : 'PostgreSQL';
            die(
                '<!-- NOSPACEFILTER --><p style="background:#ec3737;padding:4px;'
                . 'margin-top:0;font:normal normal bold 12px/130% Arial,Tahoma,'
                . 'sans-serif;">Error Connecting to Database</p><pre>'
                . "\n\nHost: {$host}, Port: {$port}, User: {$user}, DB: {$dbName}"
                . "\n\n" . $e->getMessage()
                . "\n\n<b>HINT:</b> Make sure {$driver} is running and a database"
                . " named '{$dbName}' exists.\n"
                . $hint
                . "</pre>\n\n"
            );
        }

        return true;
    }

    // -----------------------------------------------------------------------
    // Query execution
    // -----------------------------------------------------------------------

    /**
     * Returns the row count captured immediately after the last query
     * containing SQL_CALC_FOUND_ROWS - callers should use this instead of
     * issuing their own "SELECT FOUND_ROWS()" query, since FOUND_ROWS() is
     * only valid for the ONE statement immediately following the original
     * SQL_CALC_FOUND_ROWS query. A second query() call in between (even
     * this class's own internal MySQL FOUND_ROWS() prefetch) invalidates
     * it, so a caller re-issuing "SELECT FOUND_ROWS()" afterward would
     * actually get FOUND_ROWS() of THAT prefetch query itself (always 1).
     */
    public function getFoundRows()
    {
        return $this->_foundRows;
    }

    public function query($query, $ignoreErrors = false)
    {
        if (!$this->allowQuery($query))
        {
            return false;
        }

        // Handle FOUND_ROWS() — MySQL supports it natively; PostgreSQL uses a stored count.
        if (preg_match('/^\s*SELECT\s+FOUND_ROWS\s*\(\s*\)/i', $query)) {
            $this->_stmt = null;
            if ($this->_isMysql) {
                // MySQL: FOUND_ROWS() is native — pass through directly.
                $this->_stmt = $this->_pdo->query("SELECT FOUND_ROWS() AS rowCount");
            } else {
                // PostgreSQL: return the count we stored from the previous count query.
                $this->_stmt = $this->_pdo->query("SELECT " . (int)$this->_foundRows . ' AS "rowCount"');
            }
            return $this->_stmt;
        }

        // Detect SQL_CALC_FOUND_ROWS: need to run count query after
        $hasCalcFoundRows = (stripos($query, 'SQL_CALC_FOUND_ROWS') !== false);

        // Only translate MySQL→PostgreSQL syntax when running on PostgreSQL.
        // In MySQL mode the codebase SQL is already valid MySQL — no translation needed.
        if (!$this->_isMysql) {
            $query = $this->_translateQuery($query);
        }

        set_time_limit(0);

        // If SQL_CALC_FOUND_ROWS was present, resolve the total row count.
        if ($hasCalcFoundRows && !$this->_isMysql) {
            // PostgreSQL: run a separate COUNT(*) subquery (pg has no FOUND_ROWS()).
            // Strip SQL_CALC_FOUND_ROWS from inner query — it's MySQL-only syntax.
            $countQuery = preg_replace('/\bSQL_CALC_FOUND_ROWS\s*/i', '', $query);
            $countQuery = preg_replace('/\bLIMIT\s+\d+(\s+OFFSET\s+\d+)?\s*$/i', '', $countQuery);
            $countQuery = "SELECT COUNT(*) AS cnt FROM (" . $countQuery . ") AS _cfr_sub";
            try {
                $cstmt = $this->_pdo->query($countQuery);
                if ($cstmt) {
                    $crow = $cstmt->fetch(PDO::FETCH_ASSOC);
                    $this->_foundRows = (int)($crow['cnt'] ?? 0);
                }
            } catch (Exception $e) {
                $this->_foundRows = 0;
            }
        }

        // Reset per-query state.
        $this->_bufferedRows = null;
        $this->_queryHadRows = false;

        $this->_stmt = $this->_pdo->query($query);

        // MySQL: after running the query with SQL_CALC_FOUND_ROWS, fetch FOUND_ROWS() immediately.
        if ($hasCalcFoundRows && $this->_isMysql && $this->_stmt !== false) {
            try {
                $frStmt = $this->_pdo->query("SELECT FOUND_ROWS() AS cnt");
                if ($frStmt) {
                    $crow = $frStmt->fetch(PDO::FETCH_ASSOC);
                    $this->_foundRows = (int)($crow['cnt'] ?? 0);
                }
            } catch (Exception $e) {
                $this->_foundRows = 0;
            }
        }

        if ($this->_stmt === false && !$ignoreErrors)
        {
            $info   = $this->_pdo->errorInfo();
            $errMsg = isset($info[2]) ? $info[2] : 'Unknown error';
            $driver = $this->_isMysql ? 'MySQL' : 'PostgreSQL';

            die(
                '<!-- NOSPACEFILTER --><p style="background:#ec3737;padding:4px;'
                . 'margin-top:0;font:normal normal bold 12px/130% Arial,Tahoma,'
                . 'sans-serif;">Query Error — Report to System Administrator</p>'
                . "<pre>\n\n{$driver} Query Failed: " . $errMsg
                . "\n\n" . $query . "</pre>\n\n"
            );

            return false;
        }

        return $this->_stmt;
    }

    public function queryMultiple($string, $delimiter = ';')
    {
        $statements = explode($delimiter, str_replace("\r\n", "\n", $string));

        foreach ($statements as $sql)
        {
            $sql = trim($sql);
            if (empty($sql)) continue;
            $this->query($sql);
        }
    }

    // -----------------------------------------------------------------------
    // Result fetching
    // -----------------------------------------------------------------------

    public function getColumn($row, $column, $query = null)
    {
        if ($query != null)
        {
            $this->query($query);
        }

        if (!$this->_stmt) return false;

        $rows = $this->_stmt->fetchAll(PDO::FETCH_NUM);

        if ($row < 0 || $row >= count($rows)) return false;

        return $rows[$row];
    }

    public function getAssoc($query = null)
    {
        if ($query != null)
        {
            $this->query($query);
        }

        if (!$this->_stmt) {
            $this->_bufferedRows = [];
            $this->_queryHadRows = false;
            return [];
        }

        // Buffer ALL rows on first access so isEOF() and repeated getAssoc() calls work correctly.
        if ($this->_bufferedRows === null) {
            $this->_bufferedRows = $this->_stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->_queryHadRows = count($this->_bufferedRows) > 0;
        }

        $row = array_shift($this->_bufferedRows);

        return $row ?: [];
    }

    public function getAllAssoc($query = null)
    {
        if ($query != null)
        {
            $this->query($query);
        }

        if (!$this->_stmt) return [];

        return $this->_stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNumRows($query = null)
    {
        if ($query != null)
        {
            $this->query($query);
        }

        if (!$this->_stmt) return 0;

        $rows = $this->_stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = count($rows);

        // Re-create a statement-like result so subsequent fetch calls still work
        // by storing rows back. We use a simple array buffer approach.
        $this->_bufferedRows = $rows;
        return $count;
    }

    public function isEOF()
    {
        if (!$this->_stmt) return true;

        // Buffer not yet populated - fetch now and set the flag.
        if ($this->_bufferedRows === null) {
            $this->_bufferedRows = $this->_stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->_queryHadRows = count($this->_bufferedRows) > 0;
        }

        // isEOF() = "query produced no rows at all" (original mysqli behaviour).
        // Use _queryHadRows so getAssoc() consuming rows does not affect this.
        return !$this->_queryHadRows;
    }

    // -----------------------------------------------------------------------
    // Advisory locks — MySQL GET_LOCK / PostgreSQL pg_advisory_lock
    // -----------------------------------------------------------------------

    public function getAdvisoryLock($lockName, $timeout = 120)
    {
        if ($this->_isMysql) {
            $safe = $this->_pdo->quote($lockName);
            $this->query("SELECT GET_LOCK({$safe}, {$timeout})", true);
        } else {
            $key = $this->_advisoryKey($lockName);
            $this->query("SELECT pg_advisory_lock({$key})", true);
        }
    }

    public function isAdvisoryLockFree($lockName)
    {
        if ($this->_isMysql) {
            $safe = $this->_pdo->quote($lockName);
            $rs = $this->getAssoc("SELECT IS_FREE_LOCK({$safe}) AS isfreeLock");
            return !empty($rs['isfreeLock']) && $rs['isfreeLock'] == 1;
        }

        $key = $this->_advisoryKey($lockName);
        $rs  = $this->getAssoc("SELECT pg_try_advisory_lock({$key}) AS isfreeLock");

        if (!empty($rs['isfreeLock']) && $rs['isfreeLock'] !== 'f')
        {
            $this->query("SELECT pg_advisory_unlock({$key})", true);
            return true;
        }

        return false;
    }

    public function releaseAdvisoryLock($lockName)
    {
        if ($this->_isMysql) {
            $safe = $this->_pdo->quote($lockName);
            $this->query("SELECT RELEASE_LOCK({$safe})", true);
        } else {
            $key = $this->_advisoryKey($lockName);
            $this->query("SELECT pg_advisory_unlock({$key})", true);
        }
    }

    /** Convert a string lock name to a stable bigint for pg_advisory_lock(). */
    private function _advisoryKey($name)
    {
        return sprintf('%u', crc32($name));
    }

    // -----------------------------------------------------------------------
    // String / value escaping helpers
    // -----------------------------------------------------------------------

    public function escapeString($string)
    {
        $string = (string) ($string ?? '');
        // PDO::quote() surrounds with single quotes; strip them for escapeString().
        $quoted = $this->_pdo->quote($string);
        return substr($quoted, 1, -1);
    }

    public function makeQueryString($string)
    {
        // PDO::quote() returns a fully quoted, escaped string suitable for PostgreSQL.
        return $this->_pdo->quote((string) ($string ?? ''));
    }

    public function makeQueryStringOrNULL($string)
    {
        $string = trim((string) ($string ?? ''));
        if ($string === '') return 'NULL';
        return $this->makeQueryString($string);
    }

    public function makeQueryIntegerOrNULL($value)
    {
        if ($value == '-1') return 'NULL';
        return (integer) $value;
    }

    public function makeQueryInteger($value)
    {
        return (integer) $value;
    }

    public function makeQueryDouble($value, $precision = false)
    {
        $value = trim($value);

        if (empty($value) || !preg_match('/^-?[0-9]+(?:\.[0-9]+)?$/', $value))
        {
            return '0.0';
        }

        if ($precision !== false)
        {
            $valueAsDouble    = round($value, $precision);
            $isAWholeNumber   = fmod($valueAsDouble, 1) == 0;
            return number_format($valueAsDouble, $isAWholeNumber ? 0 : 2);
        }

        return (string) $value;
    }

    // -----------------------------------------------------------------------
    // Metadata / misc
    // -----------------------------------------------------------------------

    public function getError()
    {
        if (!$this->_pdo) return 'No connection established.';
        $info = $this->_pdo->errorInfo();
        return 'errno: ' . ($info[1] ?? '') . ', error: ' . ($info[2] ?? '');
    }

    public function getLastInsertID()
    {
        // lastInsertId() uses the most-recently-touched SERIAL sequence.
        return $this->_pdo->lastInsertId();
    }

    public function getAffectedRows()
    {
        return $this->_stmt ? $this->_stmt->rowCount() : 0;
    }

    public function getRDBMSVersion()
    {
        $rs = $this->getAssoc('SELECT version() AS version');
        return 'PostgreSQL ' . ($rs['version'] ?? '');
    }

    public function allowQuery($query)
    {
        if (CATS_SLAVE &&
            preg_match('/^\s*(?:UPDATE|INSERT|DELETE)\s/i', trim($query)))
        {
            return false;
        }

        return true;
    }

    // -----------------------------------------------------------------------
    // Transactions
    // -----------------------------------------------------------------------

    public function beginTransaction()
    {
        if (!$this->_inTransaction)
        {
            $this->_pdo->beginTransaction();
            return ($this->_inTransaction = true);
        }

        return false;
    }

    public function commitTransaction()
    {
        if ($this->_inTransaction)
        {
            $this->_pdo->commit();
            $this->_inTransaction = false;
            return true;
        }

        return false;
    }

    public function rollbackTransaction()
    {
        if ($this->_inTransaction)
        {
            $this->_pdo->rollBack();
            $this->_inTransaction = false;
            return true;
        }

        return false;
    }

    // -----------------------------------------------------------------------
    // MySQL → PostgreSQL SQL translation (runs on every query at runtime)
    // -----------------------------------------------------------------------

    private function _translateQuery($query)
    {
        // -1. Protect single-quoted string literals from translation.
        //     Extract them, replace with placeholders, translate, then restore.
        $literals = [];
        $query = preg_replace_callback(
            "/'(?:[^'\\\\]|\\\\.)*'/s",
            function ($m) use (&$literals) {
                $idx = count($literals);
                $key = "/*__LIT{$idx}__*/";
                $literals[$key] = $m[0];
                return $key;
            },
            $query
        );

        // 0. Quote PostgreSQL reserved words used as table names in this codebase.
        //    'user' = current_user in PG; must be "user" when used as a table.
        $query = preg_replace('/\buser\./i',                              '"user".', $query);
        $query = preg_replace('/\b(FROM|JOIN|UPDATE|INTO|TABLE)\s+user\b/i', '$1 "user"', $query);

        // 0a. If "user" is aliased (e.g. JOIN "user" AS owner_user), replace any
        //     remaining "user". references with the alias, because PostgreSQL
        //     requires using the alias once defined.
        //     Match only explicit AS aliases: JOIN "user" AS alias (not JOIN "user" ON).
        if (preg_match_all('/\bJOIN\s+"user"\s+AS\s+"?(\w+)"?/i', $query, $aliasMatches)) {
            $userAliases = $aliasMatches[1];
            if (count($userAliases) === 1) {
                $alias = $userAliases[0];
                if (strcasecmp($alias, 'user') !== 0) {
                    $query = preg_replace('/"user"\./', $alias . '.', $query);
                }
            } else {
                // Multiple aliases: replace "user". occurrences with each alias in order
                foreach ($userAliases as $alias) {
                    if (strcasecmp($alias, 'user') !== 0) {
                        $query = preg_replace('/"user"\./', $alias . '.', $query, 1);
                    }
                }
            }
        }

        // 'column' is also reserved in PG
        $query = preg_replace('/\bcolumn\./i',                            '"column".', $query);
        $query = preg_replace('/\b(FROM|JOIN|UPDATE|INTO|TABLE)\s+column\b/i', '$1 "column"', $query);

        // 0b. Preserve AS alias case: PostgreSQL lowercases unquoted aliases.
        //     Only quote column aliases in SELECT (needed so $rs['camelKey'] works).
        //     Do NOT quote JOIN table aliases — those are referenced unquoted in ON
        //     clauses and PostgreSQL will lowercase both consistently.
        $query = preg_replace_callback(
            '/\bAS\s+([a-zA-Z_][a-zA-Z0-9_]*)\b/i',
            function ($m) {
                $alias = $m[1];
                // Heuristic: table aliases are used in subsequent table.col references.
                // They appear after JOIN ... AS or FROM ... AS.
                // We detect this by looking at what precedes the AS — if it's a table
                // reference (ends after closing paren or a word that looks like a table),
                // we leave unquoted. For column aliases (appear in SELECT lists), we quote.
                // Simpler rule: quote only if alias contains uppercase (camelCase column alias).
                // Table aliases in this codebase are camelCase too (reportsToContact) but
                // we need them unquoted so ON clause references work.
                // Best approach: quote only aliases that have mixed case AND are NOT
                // followed by a '.' (table reference). We handle this at the regex level
                // by checking the context with a lookahead.
                return 'AS "' . $alias . '"';
            },
            $query
        );
        // Fix: table aliases in JOIN clauses that were just quoted need to be unquoted
        // for ON clause references to work. Unquote them by replacing
        // JOIN ... AS "alias" with JOIN ... AS alias (lowercase).
        $query = preg_replace_callback(
            '/\b((?:LEFT|RIGHT|INNER|OUTER|CROSS|FULL)?\s*JOIN\s+\S+\s+AS\s+)"([a-zA-Z_][a-zA-Z0-9_]*)"/i',
            function ($m) {
                return $m[1] . strtolower($m[2]);
            },
            $query
        );

        // 0c. Quote camelCase identifiers in ORDER BY / HAVING that reference quoted aliases.
        $query = preg_replace_callback(
            '/\bORDER\s+BY\s+(.+?)(?=\s*\b(?:LIMIT|HAVING|UNION)\b|\s*$)/is',
            function ($m) {
                $body = $m[1];
                $body = preg_replace_callback(
                    '/\b([a-z][a-zA-Z0-9]*[A-Z][a-zA-Z0-9]*)\b(?!\s*\()/',
                    function ($t) { return '"' . $t[1] . '"'; },
                    $body
                );
                return 'ORDER BY ' . $body;
            },
            $query
        );
        // Quote camelCase identifiers inside HAVING (e.g. ORD(UPPER(lastName)) → uses "lastName" alias)
        $query = preg_replace_callback(
            '/\bHAVING\b(.+?)(?=\s*\bORDER\b|\s*\bLIMIT\b|\s*$)/is',
            function ($m) {
                $body = $m[1];
                $body = preg_replace_callback(
                    '/\b([a-z][a-zA-Z0-9]*[A-Z][a-zA-Z0-9]*)\b(?!\s*\()/',
                    function ($t) { return '"' . $t[1] . '"'; },
                    $body
                );
                return 'HAVING' . $body;
            },
            $query
        );

        // 0c2. MySQL ORD() → PostgreSQL ASCII()
        $query = preg_replace('/\bORD\s*\(/i', 'ASCII(', $query);

        // 0d. email_history column name mapping (MySQL names → PostgreSQL names)
        if (stripos($query, 'email_history') !== false) {
            $query = preg_replace('/\bemail_history_id\b/', 'email_sent_id', $query);
            $query = preg_replace('/\bfrom_address\b/',     'from_addr',     $query);
            $query = preg_replace('/\brecipients\b/',       'to_addr',       $query);
        }

        // 1. MySQL index syntax inside CREATE TABLE — must run BEFORE backtick conversion
        //    so backtick-quoted index names are still in their original form.
        // UNIQUE KEY `name` (cols) → UNIQUE (cols)
        $query = preg_replace('/\bUNIQUE\s+KEY\s+(?:`[^`]*`|\w+)\s*/i', 'UNIQUE ', $query);
        // Non-unique KEY definitions are not supported inline in PostgreSQL — remove them.
        $query = preg_replace('/,\s*KEY\s+(?:`[^`]*`|\w+)\s*\([^)]+\)/i', '', $query);

        // 2. Backtick identifiers → double-quoted identifiers
        $query = preg_replace('/`([^`]*)`/', '"$1"', $query);

        // 2b. Boolean-safe comparisons: cast column to int so both boolean and
        //     integer columns work with = 0 / = 1 comparisons.
        //     MySQL used TINYINT everywhere; PostgreSQL has real BOOLEAN columns.
        //     (column)::int = 1 works for both boolean and integer columns.
        //     IMPORTANT: only apply this in WHERE/HAVING/ON clauses, NOT in SET clauses,
        //     because `SET (col)::int = 0` is invalid PostgreSQL syntax.
        $boolCastCallback = function ($m) {
            return '(' . $m[1] . ')::int ' . $m[2] . ' ' . $m[3];
        };
        $boolPattern = '/\b((?:[\w"]+\.)?(?:is|has)_\w+)\s*(=|!=|<>)\s*(0|1)\b/i';
        // Split on WHERE/HAVING/ON so the SET part is left untouched
        if (preg_match('/\b(WHERE|HAVING|ON)\b/i', $query, $splitMatch, PREG_OFFSET_CAPTURE)) {
            $splitPos  = $splitMatch[0][1];
            $setClause  = substr($query, 0, $splitPos);
            $restClause = substr($query, $splitPos);
            $restClause = preg_replace_callback($boolPattern, $boolCastCallback, $restClause);
            $query = $setClause . $restClause;
        } else {
            // No WHERE/HAVING/ON — safe to apply only if this is not an UPDATE/INSERT SET clause
            if (!preg_match('/^\s*(UPDATE|INSERT)\b/i', $query)) {
                $query = preg_replace_callback($boolPattern, $boolCastCallback, $query);
            }
        }

        // 3. IFNULL(a, b) → COALESCE(a, b)
        $query = preg_replace('/\bIFNULL\s*\(/i', 'COALESCE(', $query);

        // 3b. ISNULL(expr) → (expr IS NULL)
        $query = preg_replace_callback(
            '/\bISNULL\s*\(\s*([^)]+)\s*\)/i',
            function ($m) { return '(' . trim($m[1]) . ' IS NULL)'; },
            $query
        );

        // 4. CURDATE() → CURRENT_DATE
        $query = preg_replace('/\bCURDATE\s*\(\s*\)/i', 'CURRENT_DATE', $query);

        // 5. Remove MySQL storage-engine / charset options from CREATE TABLE
        $query = preg_replace('/\s+ENGINE\s*=\s*\w+/i',            '', $query);
        $query = preg_replace('/\s+DEFAULT\s+CHARSET\s*=\s*\w+/i', '', $query);
        $query = preg_replace('/\s+COLLATE\s*=?\s*[\w_]+/i',       '', $query);
        $query = preg_replace('/\s+CHARACTER\s+SET\s+\w+/i',        '', $query);

        // 6. AUTO_INCREMENT column type → SERIAL
        $query = preg_replace(
            '/\bINT\s*\(\s*\d+\s*\)\s+NOT\s+NULL\s+AUTO_INCREMENT\b/i',
            'SERIAL', $query
        );
        $query = preg_replace(
            '/\bINT\s*\(\s*\d+\s*\)\s+AUTO_INCREMENT\b/i',
            'SERIAL', $query
        );

        // 7. INT(n) display-width → plain INTEGER
        $query = preg_replace('/\bINT\s*\(\s*\d+\s*\)\s+UNSIGNED\b/i', 'INTEGER',  $query);
        $query = preg_replace('/\bINT\s*\(\s*\d+\s*\)\b/i',            'INTEGER',  $query);
        $query = preg_replace('/\bTINYINT\s*\(\s*\d+\s*\)\b/i',        'SMALLINT', $query);
        $query = preg_replace('/\bSMALLINT\s*\(\s*\d+\s*\)\b/i',       'SMALLINT', $query);

        // 8. DATE_ADD(expr, INTERVAL n UNIT) → (expr + INTERVAL 'n unit')
        $query = preg_replace_callback(
            '/\bDATE_ADD\s*\(\s*(.+?)\s*,\s*INTERVAL\s+(\S+)\s+(\w+)\s*\)/i',
            function ($m) {
                return '(' . trim($m[1]) . " + INTERVAL '" . $m[2] . ' ' . strtolower($m[3]) . "')";
            },
            $query
        );

        // 9. DATE_SUB(expr, INTERVAL n UNIT) → (expr - INTERVAL 'n unit')
        $query = preg_replace_callback(
            '/\bDATE_SUB\s*\(\s*(.+?)\s*,\s*INTERVAL\s+(\S+)\s+(\w+)\s*\)/i',
            function ($m) {
                return '(' . trim($m[1]) . " - INTERVAL '" . $m[2] . ' ' . strtolower($m[3]) . "')";
            },
            $query
        );

        // 10. DATE_FORMAT(expr, 'fmt') → TO_CHAR(tz-adjusted-expr, 'pg-fmt')
        $query = $this->_translateDateFormat($query, $literals);  // $literals passed by ref inside

        // 11. DAYOFWEEK(col) → (EXTRACT(DOW FROM col)::INTEGER + 1)
        //     MySQL: 1=Sunday … 7=Saturday; PG DOW: 0=Sunday … 6=Saturday
        $query = $this->_translateSingleArgFunc($query, '/\bDAYOFWEEK\s*\(/i',
            function ($arg) { return '(EXTRACT(DOW FROM ' . $arg . ')::INTEGER + 1)'; }
        );

        // 12. DAYOFMONTH(col) → EXTRACT(DAY FROM col)::INTEGER
        $query = $this->_translateSingleArgFunc($query, '/\bDAYOFMONTH\s*\(/i',
            function ($arg) { return 'EXTRACT(DAY FROM ' . $arg . ')::INTEGER'; }
        );

        // 13. MONTHNAME(col) → TO_CHAR(col, 'Month')
        $query = $this->_translateSingleArgFunc($query, '/\bMONTHNAME\s*\(/i',
            function ($arg) { return "TO_CHAR(" . $arg . ", 'Month')"; }
        );

        // 14. MONTH(col) → EXTRACT(MONTH FROM col)::INTEGER
        $query = $this->_translateSingleArgFunc($query, '/\bMONTH\s*\(/i',
            function ($arg) { return 'EXTRACT(MONTH FROM ' . $arg . ')::INTEGER'; }
        );

        // 15. YEAR(col) → EXTRACT(YEAR FROM col)::INTEGER
        $query = $this->_translateSingleArgFunc($query, '/\bYEAR\s*\(/i',
            function ($arg) { return 'EXTRACT(YEAR FROM ' . $arg . ')::INTEGER'; }
        );

        // 16. YEARWEEK(col) → TO_CHAR(col, 'IYYYIW')::INTEGER
        $query = $this->_translateSingleArgFunc($query, '/\bYEARWEEK\s*\(/i',
            function ($arg) { return "TO_CHAR(" . $arg . ", 'IYYYIW')::INTEGER"; }
        );

        // 17. EXTRACT(YEAR_MONTH FROM col) → TO_CHAR(col, 'YYYYMM')::INTEGER
        $query = $this->_translateSingleArgFunc($query, '/\bEXTRACT\s*\(\s*YEAR_MONTH\s+FROM\s+/i',
            function ($arg) { return "TO_CHAR(" . $arg . ", 'YYYYMM')::INTEGER"; }
        );

        // 18. TO_DAYS(col) — only used for date comparisons; cast to date is equivalent
        $query = $this->_translateSingleArgFunc($query, '/\bTO_DAYS\s*\(/i',
            function ($arg) { return '(' . $arg . ')::date'; }
        );

        // 18b. UNIX_TIMESTAMP(expr) → EXTRACT(EPOCH FROM expr)::bigint
        //      UNIX_TIMESTAMP() with no args → EXTRACT(EPOCH FROM NOW())::bigint
        $query = preg_replace('/\bUNIX_TIMESTAMP\s*\(\s*\)/i', 'EXTRACT(EPOCH FROM NOW())::bigint', $query);
        $query = $this->_translateSingleArgFunc($query, '/\bUNIX_TIMESTAMP\s*\(/i',
            function ($arg) { return 'EXTRACT(EPOCH FROM (' . $arg . '))::bigint'; }
        );

        // 19. GROUP_CONCAT(col) → STRING_AGG(col::text, ',')
        $query = preg_replace_callback(
            '/\bGROUP_CONCAT\s*\(\s*(.*?)\s*\)/i',
            function ($m) {
                $inner = trim($m[1]);
                // GROUP_CONCAT(' ', col) — first arg is separator, second is column
                if (preg_match("/^'([^']*)'\s*,\s*(.+)$/", $inner, $parts)) {
                    return "STRING_AGG((" . trim($parts[2]) . ")::text, '" . $parts[1] . "')";
                }
                return "STRING_AGG(({$inner})::text, ',')";
            },
            $query
        );

        // 20. MySQL IF(cond, true_val, false_val) → CASE WHEN cond THEN true_val ELSE false_val END
        $query = $this->_translateIF($query);

        // 21. FIND_IN_SET(val, set) → val = ANY(string_to_array(set, ','))
        $query = preg_replace_callback(
            '/\bFIND_IN_SET\s*\(\s*(.+?)\s*,\s*(.+?)\s*\)/i',
            function ($m) {
                return '(' . trim($m[1]) . ' = ANY(string_to_array(' . trim($m[2]) . ", ',')))";
            },
            $query
        );

        // 22. SUBSTRING_INDEX(str, delim, 1) → SPLIT_PART(str, delim, 1)
        $query = preg_replace_callback(
            '/\bSUBSTRING_INDEX\s*\(\s*(.+?)\s*,\s*(\'[^\']+\')\s*,\s*(\d+)\s*\)/i',
            function ($m) {
                return 'SPLIT_PART(' . trim($m[1]) . ', ' . $m[2] . ', ' . $m[3] . ')';
            },
            $query
        );

        // 23. CONCAT_WS(sep, a, b, ...) — works in PG, no change needed
        // 24. LPAD / RPAD — PG requires text first arg; add ::text cast for integer args
        $query = preg_replace_callback(
            '/\bLPAD\s*\(\s*([^,]+)\s*,/i',
            function ($m) {
                $arg = trim($m[1]);
                // If arg looks like a plain column reference (no cast already), add ::text
                if (!preg_match('/::text\b/i', $arg) && !preg_match("/^'/", $arg)) {
                    $arg = "({$arg})::text";
                }
                return "LPAD({$arg},";
            },
            $query
        );

        // 24b. DATEDIFF(date1, date2) → (date1::date - date2::date)
        $query = preg_replace_callback(
            '/\bDATEDIFF\s*\(\s*(.+?)\s*,\s*(.+?)\s*\)/i',
            function ($m) {
                return '((' . trim($m[1]) . ')::date - (' . trim($m[2]) . ')::date)';
            },
            $query
        );

        // 25a. SHOW COLUMNS FROM table LIKE 'col' → information_schema query
        $query = preg_replace_callback(
            '/\bSHOW\s+(?:COLUMNS|FIELDS)\s+FROM\s+(["\w]+)(?:\s+LIKE\s*(\'[^\']+\'))?\s*$/i',
            function ($m) {
                $table = trim($m[1], '"\'`');
                if (!empty($m[2])) {
                    $colName = trim($m[2], "'");
                    return "SELECT column_name AS \"Field\", data_type AS \"Type\", is_nullable AS \"Null\", '' AS \"Key\", column_default AS \"Default\", '' AS \"Extra\" FROM information_schema.columns WHERE table_name = '{$table}' AND column_name = '{$colName}'";
                }
                return "SELECT column_name AS \"Field\", data_type AS \"Type\", is_nullable AS \"Null\", '' AS \"Key\", column_default AS \"Default\", '' AS \"Extra\" FROM information_schema.columns WHERE table_name = '{$table}' ORDER BY ordinal_position";
            },
            $query
        );

        // 25c. SQL_CALC_FOUND_ROWS — MySQL-only hint, remove it
        $query = preg_replace('/\bSQL_CALC_FOUND_ROWS\s*/i', '', $query);

        // 25b. LIMIT offset, count → LIMIT count OFFSET offset
        $query = preg_replace_callback(
            '/\bLIMIT\s+(\d+)\s*,\s*(\d+)\b/i',
            function ($m) {
                return 'LIMIT ' . $m[2] . ' OFFSET ' . $m[1];
            },
            $query
        );

        // 25. INSERT IGNORE INTO — keep as-is for MySQL/MariaDB
        // (ON CONFLICT DO NOTHING is PostgreSQL only)

        // 25a. ON DUPLICATE KEY UPDATE ... → ON CONFLICT DO NOTHING
        //      This codebase only uses this idiom for insert-or-ignore semantics
        //      (e.g. "UPDATE version=version"), never a real update, so a no-op
        //      conflict clause is equivalent on PostgreSQL.
        $query = preg_replace('/\bON\s+DUPLICATE\s+KEY\s+UPDATE\s+.+$/is', 'ON CONFLICT DO NOTHING', $query);

        // 25d. TO_CHAR(...) = integer → TO_CHAR(...)::integer = integer
        //      (handles DATE_FORMAT('%%c') comparisons with integer values)
        $query = preg_replace_callback(
            '/\bTO_CHAR\s*\(([^)]+)\)\s*(=|!=|<>|<|>|<=|>=)\s*(\d+)\b/i',
            function ($m) {
                return 'TO_CHAR(' . $m[1] . ')::integer ' . $m[2] . ' ' . $m[3];
            },
            $query
        );

        // 26. Expand GROUP BY for PostgreSQL strict mode:
        //     Find all JOIN ... ON join_col = alias.alias_id patterns and add
        //     alias.alias_id to GROUP BY so non-aggregate columns from those
        //     tables are permitted.
        if (preg_match('/\bGROUP\s+BY\b/i', $query)) {
            $query = $this->_expandGroupBy($query);
        }

        // Restore protected string literals
        if (!empty($literals)) {
            $query = str_replace(array_keys($literals), array_values($literals), $query);
        }

        return $query;
    }

    /**
     * Expand GROUP BY to include JOIN table PKs so PostgreSQL's strict
     * functional dependency check passes for MySQL-style GROUP BY queries.
     * Looks for LEFT JOIN table [AS alias] ON ... = alias.pk patterns
     * and adds alias.pk to the GROUP BY clause.
     *
     * Only outer-query JOINs are considered — subquery JOINs (inside any
     * parentheses) are excluded to avoid referencing table aliases that do
     * not exist in the outer FROM scope.
     */
    private function _expandGroupBy($query)
    {
        // Find the GROUP BY clause
        if (!preg_match('/\bGROUP\s+BY\s+(.+?)(?=\s*\b(?:HAVING|ORDER\s+BY|LIMIT|UNION)\b|\s*$)/is', $query, $gbMatch)) {
            return $query;
        }
        $currentGroupBy = trim($gbMatch[1]);
        $groupByItems   = array_map('trim', explode(',', $currentGroupBy));

        // Strip all subquery content (balanced parentheses) so regex only
        // sees JOINs that belong to the outer query's FROM clause.
        $outerQuery = $this->_stripSubqueries($query);

        // Find outer-query JOIN ON conditions to extract joined table PKs.
        preg_match_all(
            '/\bJOIN\s+(["\w]+)\s+(?:AS\s+(["\w]+)\s+)?ON\s+(.+?)(?=\s*\b(?:LEFT|RIGHT|INNER|OUTER|CROSS|JOIN|WHERE|GROUP\s+BY|ORDER\s+BY|LIMIT|HAVING|UNION)\b|\s*$)/is',
            $outerQuery,
            $joins,
            PREG_SET_ORDER
        );

        $extraCols = [];
        foreach ($joins as $join) {
            // If AS alias present use it, otherwise table name is the alias
            $alias    = !empty($join[2]) ? trim($join[2], '"') : trim($join[1], '"');
            $onClause = $join[3];
            // Look for alias.col_id = ... or ... = alias.col_id patterns
            if (preg_match('/\b' . preg_quote($alias, '/') . '\.(\w+(?:_id|_key)?)\b/i', $onClause, $colMatch)) {
                $col       = $alias . '.' . $colMatch[1];
                $colQuoted = '"' . $alias . '"."' . $colMatch[1] . '"';
                // Add if not already in GROUP BY (check both quoted and unquoted)
                $already = false;
                foreach ($groupByItems as $item) {
                    if (strcasecmp(trim($item), $col) === 0 || strcasecmp(trim($item), $colQuoted) === 0) {
                        $already = true;
                        break;
                    }
                }
                if (!$already) {
                    $extraCols[]    = $col;
                    $groupByItems[] = $col;
                }
            }
        }

        if (empty($extraCols)) {
            return $query;
        }

        $newGroupBy = implode(', ', $groupByItems);
        return preg_replace(
            '/\bGROUP\s+BY\s+' . preg_quote($currentGroupBy, '/') . '/is',
            'GROUP BY ' . $newGroupBy,
            $query,
            1
        );
    }

    /**
     * Replace all content inside parentheses (at any nesting depth) with
     * spaces, so only the outer query structure remains for analysis.
     * Single-quoted string literals at depth 0 are preserved as-is.
     */
    private function _stripSubqueries($sql)
    {
        $result = '';
        $depth  = 0;
        $len    = strlen($sql);
        $inStr  = false;

        for ($i = 0; $i < $len; $i++) {
            $c = $sql[$i];

            if ($inStr) {
                // Inside a single-quoted string: look for closing quote
                if ($c === "'" && ($i === 0 || $sql[$i - 1] !== '\\')) {
                    $inStr = false;
                }
                if ($depth === 0) $result .= $c;
                continue;
            }

            if ($c === "'") {
                $inStr = true;
                if ($depth === 0) $result .= $c;
                continue;
            }

            if ($c === '(') {
                $depth++;
                $result .= ' '; // replace with space so word boundaries still work
                continue;
            }

            if ($c === ')') {
                if ($depth > 0) $depth--;
                $result .= ' ';
                continue;
            }

            // Only emit characters that are at depth 0 (outer query)
            if ($depth === 0) {
                $result .= $c;
            }
        }

        return $result;
    }

    /**
     * Parse DATE_FORMAT(expr, 'fmt') calls and convert them to
     * PostgreSQL TO_CHAR(tz_adjusted_expr, 'pg_fmt').
     * Uses a character-by-character balanced-parenthesis parser so nested
     * function calls inside the first argument are handled correctly.
     */
    /**
     * Convert MySQL IF(cond, true_val, false_val) to PostgreSQL
     * CASE WHEN cond THEN true_val ELSE false_val END.
     * Uses a balanced-parenthesis parser so nested calls are handled correctly.
     */
    private function _translateIF($query)
    {
        $result    = '';
        $remaining = $query;

        while (($pos = preg_match('/\bIF\s*\(/i', $remaining, $m, PREG_OFFSET_CAPTURE)) === 1)
        {
            $matchPos  = $m[0][1];
            $result   .= substr($remaining, 0, $matchPos);
            // Skip past 'IF('
            $inner     = substr($remaining, $matchPos + strlen($m[0][0]));

            // Walk through finding the two commas at depth 1 and the closing ')'
            $depth         = 1;
            $i             = 0;
            $len           = strlen($inner);
            $commas        = [];
            $inSingleQuote = false;

            while ($i < $len && $depth > 0)
            {
                $c = $inner[$i];
                if ($inSingleQuote) {
                    if ($c === "'" && ($i === 0 || $inner[$i-1] !== '\\')) {
                        $inSingleQuote = false;
                    }
                } else {
                    if      ($c === '(')  { $depth++; }
                    elseif  ($c === ')')  { $depth--; if ($depth === 0) break; }
                    elseif  ($c === "'")  { $inSingleQuote = true; }
                    elseif  ($c === ',' && $depth === 1 && count($commas) < 2) {
                        $commas[] = $i;
                    }
                }
                $i++;
            }

            if (count($commas) === 2 && $depth === 0)
            {
                $cond     = trim(substr($inner, 0, $commas[0]));
                $trueVal  = trim(substr($inner, $commas[0] + 1, $commas[1] - $commas[0] - 1));
                $falseVal = trim(substr($inner, $commas[1] + 1, $i - $commas[1] - 1));

                // Recursively translate nested IF() calls inside each part
                $cond     = $this->_translateIF($cond);
                $trueVal  = $this->_translateIF($trueVal);
                $falseVal = $this->_translateIF($falseVal);

                $result   .= "CASE WHEN {$cond} THEN {$trueVal} ELSE {$falseVal} END";
                $remaining = substr($inner, $i + 1);
            }
            else
            {
                // Could not parse — leave as-is and move past 'IF('
                $result   .= 'IF(';
                $remaining = $inner;
            }
        }

        return $result . $remaining;
    }

    /**
     * Extract the balanced-parenthesis argument from a MySQL function call.
     * Given "FUNC(" already matched, walks $remaining to find the closing ')'.
     * Returns [argument_string, rest_of_query] or false on failure.
     */
    private function _extractBalancedArg($remaining)
    {
        $depth = 1;
        $i     = 0;
        $len   = strlen($remaining);
        $inQ   = false;

        while ($i < $len && $depth > 0) {
            $c = $remaining[$i];
            if ($inQ) {
                if ($c === "'" && ($i === 0 || $remaining[$i - 1] !== '\\')) {
                    $inQ = false;
                }
            } else {
                if      ($c === '(')  { $depth++; }
                elseif  ($c === ')')  { $depth--; if ($depth === 0) break; }
                elseif  ($c === "'")  { $inQ = true; }
            }
            $i++;
        }

        if ($depth !== 0) {
            return false;
        }

        return [trim(substr($remaining, 0, $i)), substr($remaining, $i + 1)];
    }

    /**
     * Translate a single-argument MySQL function to a PostgreSQL expression
     * using balanced-parenthesis parsing. $funcPattern is the regex to find
     * the function name + opening paren. $callback receives the argument string
     * and returns the replacement expression.
     */
    private function _translateSingleArgFunc($query, $funcPattern, $callback)
    {
        $result    = '';
        $remaining = $query;

        while (preg_match($funcPattern, $remaining, $m, PREG_OFFSET_CAPTURE) === 1) {
            $matchPos  = $m[0][1];
            $result   .= substr($remaining, 0, $matchPos);
            $inner     = substr($remaining, $matchPos + strlen($m[0][0]));

            $parsed = $this->_extractBalancedArg($inner);
            if ($parsed === false) {
                $result   .= $m[0][0];
                $remaining = $inner;
                continue;
            }

            list($arg, $rest) = $parsed;
            $result   .= $callback($arg);
            $remaining = $rest;
        }

        return $result . $remaining;
    }

    private function _translateDateFormat($query, &$literals = [])
    {
        $result    = '';
        $remaining = $query;

        while (($pos = stripos($remaining, 'DATE_FORMAT(')) !== false)
        {
            $result   .= substr($remaining, 0, $pos);
            $remaining = substr($remaining, $pos + 12); // skip 'DATE_FORMAT('

            // Walk through characters tracking paren depth to find:
            // – the last comma at depth 1  (separates expr from format string)
            // – the closing ')' at depth 0 (end of DATE_FORMAT call)
            $depth             = 1;
            $i                 = 0;
            $len               = strlen($remaining);
            $lastCommaAtDepth1 = -1;
            $inSingleQuote     = false;

            while ($i < $len && $depth > 0)
            {
                $c = $remaining[$i];

                if ($inSingleQuote)
                {
                    if ($c === "'" && ($i === 0 || $remaining[$i - 1] !== '\\'))
                    {
                        $inSingleQuote = false;
                    }
                }
                else
                {
                    if      ($c === '(')  { $depth++; }
                    elseif  ($c === ')')  { $depth--; if ($depth === 0) break; }
                    elseif  ($c === "'")  { $inSingleQuote = true; }
                    elseif  ($c === ',' && $depth === 1) { $lastCommaAtDepth1 = $i; }
                }

                $i++;
            }

            // $i is now the position of the closing ')'.
            if ($lastCommaAtDepth1 < 0 || $depth !== 0)
            {
                // Could not parse — leave this DATE_FORMAT unchanged.
                $result   .= 'DATE_FORMAT(' . substr($remaining, 0, $i + 1);
                $remaining = substr($remaining, $i + 1);
                continue;
            }

            $expr    = trim(substr($remaining, 0, $lastCommaAtDepth1));
            $fmtPart = trim(substr($remaining, $lastCommaAtDepth1 + 1,
                                   $i - $lastCommaAtDepth1 - 1));
            $remaining = substr($remaining, $i + 1);

            // Extract the raw format string (strip surrounding quotes).
            // If the format part is a placeholder, resolve it first.
            $fmtResolved = $fmtPart;
            if (!empty($literals) && isset($literals[$fmtPart])) {
                $fmtResolved = $literals[$fmtPart];
                // Remove the placeholder from literals so it won't be double-restored
                unset($literals[$fmtPart]);
            }
            $mysqlFmt = trim($fmtResolved, "'");

            // Apply timezone offset to the date expression.
            if ($this->_timeZone > 0) {
                $tzExpr = "({$expr} + INTERVAL '{$this->_timeZone} hour')";
            } elseif ($this->_timeZone < 0) {
                $tz     = abs($this->_timeZone);
                $tzExpr = "({$expr} - INTERVAL '{$tz} hour')";
            } else {
                $tzExpr = $expr;
            }

            // Convert MySQL date format codes to PostgreSQL TO_CHAR codes.
            $pgFmt = $this->_convertDateFormat($mysqlFmt);

            // Swap m/d order when running in DMY locale mode.
            if ($this->_dateDMY)
            {
                $pgFmt = str_replace(
                    ['MM-DD-YY', 'MM-DD-YYYY', 'MM/DD/YYYY', 'MM/DD/YY'],
                    ['DD-MM-YY', 'DD-MM-YYYY', 'DD/MM/YYYY', 'DD/MM/YY'],
                    $pgFmt
                );
            }

            $result .= "TO_CHAR({$tzExpr}, '{$pgFmt}')";
        }

        return $result . $remaining;
    }

    /** Map MySQL strftime-style codes to PostgreSQL TO_CHAR codes. */
    private function _convertDateFormat($mysqlFmt)
    {
        $map = [
            '%Y' => 'YYYY',
            '%y' => 'YY',
            '%m' => 'MM',
            '%c' => 'FMMM',
            '%d' => 'DD',
            '%e' => 'FMDD',
            '%H' => 'HH24',
            '%h' => 'HH12',
            '%I' => 'HH12',
            '%i' => 'MI',
            '%s' => 'SS',
            '%S' => 'SS',
            '%M' => 'Month',
            '%b' => 'Mon',
            '%p' => 'AM',
            '%W' => 'Day',
            '%a' => 'Dy',
            '%j' => 'DDD',
            '%U' => 'WW',
            '%w' => 'D',
            '%T' => 'HH24:MI:SS',
            '%r' => 'HH12:MI:SS AM',
        ];

        return strtr($mysqlFmt, $map);
    }
}
?>
