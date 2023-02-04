<?php

defined('ABSPATH') || defined('DUPXABSPATH') || exit;

/**
 * @copyright 2016 Snap Creek LLC
 */
class DUP_PRO_Constants
{
    const PLUGIN_SLUG                              = 'duplicator-pro';
    const DAYS_TO_RETAIN_DUMP_FILES                = 1;
    const ZIPPED_LOG_FILENAME                      = 'duplicator_pro_log.zip';
    const TEMP_CLEANUP_SECONDS                     = 900;   // 15 min = How many seconds to keep temp files around when delete is requested
    const IMPORTS_CLEANUP_SECS                     = 86400; // 24 hours - how old files in import directory can be before getting cleane up
    const MAX_LOG_SIZE                             = 400000;    // The higher this is the more overhead
    const MAX_BUILD_RETRIES                        = 15; // Max number of tries doing the same part of the package before auto cancelling
    const PACKAGE_CHECK_TIME_IN_SEC                = 10;
    const DEFAULT_MAX_PACKAGE_RUNTIME_IN_MIN       = 90;
    const DEFAULT_MAX_WORKER_TIME                  = 20;
    const DEFAULT_ZIP_ARCHIVE_CHUNK                = 32;
    const ORPAHN_CLEANUP_DELAY_MAX_PACKAGE_RUNTIME = 60;

    //SVG Icon: See https://websemantics.uk/tools/image-to-data-uri-converter/
    const ICON_SVG = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNS4xLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB3aWR0aD0iMjU2cHgiIGhlaWdodD0iMjU2cHgiIHZpZXdCb3g9IjAgMCAyNTYgMjU2IiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCAyNTYgMjU2IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxnPg0KCTxnPg0KCQk8cGF0aCBmaWxsPSIjQTdBOUFDIiBkPSJNMTcyLjEwMywzNS4yMjNsLTEuMzk1LTI0LjA5N0wxNTMuMzYsNi40NzhsLTEzLjI1MywyMC4xNzFjLTYuNzQyLTAuNjc1LTEzLjUzNS0wLjY5Ni0yMC4yNzYtMC4wNDINCgkJCUwxMDYuNDY3LDYuMjdsLTE3LjM0OCw0LjY0N2wtMS40LDI0LjIwNGMtNi4wNzMsMi43MjQtMTEuOTMsNi4wNzQtMTcuNDg1LDEwLjAzOUw0OC40MDMsMzQuMTgzTDM1LjcwNCw0Ni44ODJsMTAuOTEsMjEuNzAxDQoJCQljLTQuMDExLDUuNTIyLTcuMzk2LDExLjM1Mi0xMC4xNywxNy4zOTdsLTI0LjM2NywxLjQxbC00LjY0OCwxNy4zNDhsMjAuMjQxLDEzLjNjLTAuNzA4LDYuNzM0LTAuNzg1LDEzLjUyMy0wLjE2NiwyMC4yNjUNCgkJCWwtMjAuMjg0LDEzLjMzbDQuNjQ4LDE3LjM0OGwyMy4zMjQsMS4zNDlsMC4yMjctMC44MjZsOS4xMDYtMzMuMTc2Yy0yLjEyNS0yNC4zMzMsNi4xMDQtNDkuMzk3LDI0LjcyOS02OC4wMjMNCgkJCWMyNy4zOTMtMjcuMzkzLDY4LjcxLTMyLjMxNSwxMDEuMTQ1LTE0LjgzM0w1NC40MjIsMTY5LjQ0N2wtMi41ODUtMzIuMzU1TDMwLjg5LDIxMy4zOThsMzEuNjE0LTMxLjYxNEwxODIuNzM1LDYxLjU1Mw0KCQkJbDQuMjA0LTQuMjA2bDcuOTc4LTcuOTc4QzE4Ny44MzYsNDMuNTU3LDE4MC4xNSwzOC44NTcsMTcyLjEwMywzNS4yMjN6Ii8+DQoJCTxwYXRoIGZpbGw9IiNBN0E5QUMiIGQ9Ik0xMDUuMjE0LDkuNTU4bDEyLjIzMSwxOC42MTRsMC45NDUsMS40NGwxLjcxNS0wLjE2NmMzLjE4Mi0wLjMwOCw2LjQyMy0wLjQ2NSw5LjYzNC0wLjQ2NQ0KCQkJYzMuMzQ3LDAsNi43MzYsMC4xNywxMC4wODIsMC41MDZsMS43MTksMC4xNzJsMC45NS0xLjQ0NGwxMi4xMjItMTguNDQ5bDEzLjM2NSwzLjU4MWwxLjI3NCwyMi4wNDFsMC4xMDEsMS43MjRsMS41NzMsMC43MTENCgkJCWM3LjAzNiwzLjE3NSwxMy42NTIsNy4xMzYsMTkuNzExLDExLjc5MWwtNS43MTcsNS43MThsLTQuMjAzLDQuMjAzTDYwLjQ4NSwxNzkuNzY2bC0yMy45OTEsMjMuOTkybDEzLjc5Mi01MC4yNDRsMS4yOTIsMTYuMTYyDQoJCQlsMC40OTMsNi4xNTlsNC4zNjktNC4zNjdMMTcyLjQxNiw1NS40OWwyLjcwOS0yLjcxMWwtMy4zNzItMS44MThjLTEyLjgyOS02LjkxNS0yNy4zNDktMTAuNTcxLTQxLjk5NC0xMC41NzENCgkJCWMtMjMuNjIsMC00NS44MjMsOS4xOTgtNjIuNTIyLDI1Ljg5N0M0OC44MzMsODQuNjksMzkuNTIsMTEwLjA5NSw0MS42MzksMTM2LjA2MWwtOC41ODcsMzEuMjg4bC0xOC45NjItMS4wOTlsLTMuNTgxLTEzLjM2Mw0KCQkJbDE4LjU2Mi0xMi4xOThsMS40MzEtMC45NDJsLTAuMTU2LTEuNzA0Yy0wLjU5Mi02LjQzNi0wLjUzOC0xMy4wNjUsMC4xNjEtMTkuNzA2bDAuMTgyLTEuNzI4bC0xLjQ1Mi0wLjk1NWwtMTguNTE4LTEyLjE2Nw0KCQkJbDMuNTgxLTEzLjM2NmwyMi4zMDktMS4yOTFsMS43MTItMC4wOThsMC43MTctMS41NTljMi43MjktNS45NDgsNi4wNTUtMTEuNjM5LDkuODg1LTE2LjkxM2wxLjAyMS0xLjQwNmwtMC43NzktMS41NTINCgkJCWwtOS45ODQtMTkuODU5bDkuNzg0LTkuNzg0bDE5Ljk4OCwxMC4wNDlsMS41MzgsMC43NzRsMS40MDEtMS4wMDFjNS4zNDMtMy44MTEsMTEuMDYxLTcuMDk0LDE2Ljk5Ny05Ljc1N2wxLjU4MS0wLjcxMmwwLjEtMS43MjgNCgkJCWwxLjI4MS0yMi4xNDVMMTA1LjIxNCw5LjU1OCBNMTA2LjQ2Nyw2LjI3bC0xNy4zNDgsNC42NDdsLTEuNCwyNC4yMDRjLTYuMDczLDIuNzI2LTExLjkzLDYuMDc0LTE3LjQ4NiwxMC4wMzlsLTIxLjgzLTEwLjk3Ng0KCQkJbC0xMi43LDEyLjcwMWwxMC45MSwyMS42OTljLTQuMDExLDUuNTIyLTcuMzk2LDExLjM1My0xMC4xNywxNy4zOTdsLTI0LjM2NywxLjQxbC00LjY0OCwxNy4zNDhsMjAuMjQsMTMuMw0KCQkJYy0wLjcwOCw2LjczNC0wLjc4NCwxMy41MjMtMC4xNjUsMjAuMjY1bC0yMC4yODQsMTMuMzNsNC42NDgsMTcuMzQ4bDIzLjMyNCwxLjM0OWwwLjIyNy0wLjgyNmw5LjEwNi0zMy4xNzYNCgkJCWMtMi4xMjUtMjQuMzMzLDYuMTA0LTQ5LjM5NywyNC43MjktNjguMDIzYzE2LjcxLTE2LjcxMSwzOC42MDctMjUuMDYsNjAuNTA1LTI1LjA2YzEzLjk5OCwwLDI3Ljk5MiwzLjQxMSw0MC42NCwxMC4yMjcNCgkJCUw1NC40MjIsMTY5LjQ0OWwtMi41ODUtMzIuMzU3TDMwLjg5LDIxMy4zOThsMzEuNjE0LTMxLjYxNEwxODIuNzM1LDYxLjU1M2w0LjIwMy00LjIwNGw3Ljk3OS03Ljk3OQ0KCQkJYy03LjA4My01LjgxNS0xNC43NjctMTAuNTEzLTIyLjgxNC0xNC4xNDdsLTEuMzk1LTI0LjA5N0wxNTMuMzYsNi40NzhsLTEzLjI1NCwyMC4xN2MtMy40NDctMC4zNDYtNi45MDctMC41Mi0xMC4zNjYtMC41Mg0KCQkJYy0zLjMwNywwLTYuNjE0LDAuMTYtOS45MSwwLjQ3OUwxMDYuNDY3LDYuMjdMMTA2LjQ2Nyw2LjI3eiIvPg0KCTwvZz4NCgk8Zz4NCgkJPHBhdGggZmlsbD0iI0E3QTlBQyIgZD0iTTg3LjgwMiwyMjIuMjFsMS4zOTQsMjQuMDk3bDE3LjM0OCw0LjY0OWwxMy4yNTUtMjAuMTdjNi43NDIsMC42NzUsMTMuNTMzLDAuNjkzLDIwLjI3NCwwLjA0MQ0KCQkJbDEzLjM2NSwyMC4zMzVsMTcuMzQ3LTQuNjQ2bDEuMzk5LTI0LjIwMmM2LjA3My0yLjcyNSwxMS45My02LjA3NCwxNy40ODYtMTAuMDM4bDIxLjgzMSwxMC45NzRsMTIuNjk5LTEyLjY5OGwtMTAuOTEtMjEuNzAxDQoJCQljNC4wMTItNS41MjEsNy4zOTYtMTEuMzUyLDEwLjE2OS0xNy4zOThsMjQuMzY5LTEuNDA4bDQuNjQ2LTE3LjM0OGwtMjAuMjM5LTEzLjNjMC43MDgtNi43MzYsMC43ODQtMTMuNTIzLDAuMTY0LTIwLjI2Ng0KCQkJbDIwLjI4NC0xMy4zMjhsLTQuNjQ3LTE3LjM0OGwtMjMuMzIzLTEuMzQ5bC0wLjIyOCwwLjgyNWwtOS4xMDcsMzMuMTc1YzIuMTI3LDI0LjMzMi02LjEwNCw0OS4zOTctMjQuNzI5LDY4LjAyNA0KCQkJYy0yNy4zOTIsMjcuMzkzLTY4LjcwOSwzMi4zMTUtMTAxLjE0NCwxNC44MzFMMjA1LjQ4LDg3Ljk4NGwyLjU4NiwzMi4zNTZsMjAuOTQ4LTc2LjMwNWwtMzEuNjE1LDMxLjYxM0w3Ny4xNjksMTk1Ljg4DQoJCQlsLTQuMjA2LDQuMjA1bC03Ljk3OCw3Ljk3OUM3Mi4wNjgsMjEzLjg3Niw3OS43NTIsMjE4LjU3NSw4Ny44MDIsMjIyLjIxeiIvPg0KCQk8cGF0aCBmaWxsPSIjQTdBOUFDIiBkPSJNMjIzLjQwOSw1My42NzZsLTEzLjc5Myw1MC4yNGwtMS4yOS0xNi4xNmwtMC40OTQtNi4xNTlsLTQuMzY4LDQuMzdMODcuNDg3LDIwMS45NDJsLTIuNzA5LDIuNzEyDQoJCQlsMy4zNzMsMS44MThjMTIuODI4LDYuOTE0LDI3LjM1MSwxMC41NjgsNDEuOTk3LDEwLjU2OGMyMy42MTgsMCw0NS44MjEtOS4xOTUsNjIuNTItMjUuODk2DQoJCQljMTguNDAzLTE4LjQwMiwyNy43MTctNDMuODA3LDI1LjU5OC02OS43NzVsOC41ODgtMzEuMjgzbDE4Ljk2MSwxLjA5N2wzLjU4MiwxMy4zNjRsLTE4LjU2MywxMi4xOTdsLTEuNDMsMC45NDFsMC4xNTUsMS43MDUNCgkJCWMwLjU5Miw2LjQzNiwwLjUzOSwxMy4wNjctMC4xNiwxOS43MDZsLTAuMTgzLDEuNzI3bDEuNDUxLDAuOTU0bDE4LjUyMSwxMi4xNzFsLTMuNTgyLDEzLjM2NWwtMjIuMzExLDEuMjkxbC0xLjcxMiwwLjA5OQ0KCQkJbC0wLjcxNiwxLjU2Yy0yLjcyNyw1Ljk0NC02LjA1MywxMS42MzMtOS44ODYsMTYuOTExbC0xLjAyLDEuNDA0bDAuNzgsMS41NTRsOS45ODMsMTkuODU5bC05Ljc4NSw5Ljc4M2wtMTkuOTktMTAuMDUNCgkJCWwtMS41MzYtMC43NzJsLTEuNDAyLDAuOTk5Yy01LjM0MSwzLjgxNC0xMS4wNTksNy4wOTYtMTYuOTk0LDkuNzU4bC0xLjU4MiwwLjcxbC0wLjA5OSwxLjcyOWwtMS4yODMsMjIuMTQ2bC0xMy4zNjMsMy41ODENCgkJCWwtMTIuMjMzLTE4LjYxNWwtMC45NDYtMS40MzhsLTEuNzEzLDAuMTYzYy0zLjE4LDAuMzEtNi40MTcsMC40NjUtOS42MjYsMC40NjVjLTMuMzQ4LDAtNi43NDMtMC4xNjktMTAuMDktMC41MDVsLTEuNzE5LTAuMTcxDQoJCQlsLTAuOTUsMS40NDNsLTEyLjEyMiwxOC40NDhsLTEzLjM2Ni0zLjU4MWwtMS4yNzUtMjIuMDM4bC0wLjEtMS43MjdsLTEuNTc0LTAuNzA5Yy03LjAzNS0zLjE4LTEzLjY1My03LjEzOS0xOS43MS0xMS43OTINCgkJCWw1LjcxNi01LjcxNWw0LjIwNS00LjIwN0wxOTkuNDE4LDc3LjY2NkwyMjMuNDA5LDUzLjY3NiBNMjI5LjAxNSw0NC4wMzZsLTMxLjYxNSwzMS42MTNMNzcuMTY5LDE5NS44OGwtNC4yMDYsNC4yMDVsLTcuOTc3LDcuOTc5DQoJCQljNy4wOCw1LjgxMiwxNC43NjUsMTAuNTExLDIyLjgxNCwxNC4xNDZsMS4zOTQsMjQuMDk3bDE3LjM0OCw0LjY0OWwxMy4yNTQtMjAuMTczYzMuNDQ4LDAuMzQ4LDYuOTEyLDAuNTIzLDEwLjM3NCwwLjUyMw0KCQkJYzMuMzA0LDAsNi42MDctMC4xNjIsOS45LTAuNDc5bDEzLjM2NSwyMC4zMzVsMTcuMzQ3LTQuNjQ2bDEuMzk5LTI0LjIwMmM2LjA3My0yLjcyNSwxMS45MzEtNi4wNzcsMTcuNDg2LTEwLjAzOGwyMS44MzEsMTAuOTc0DQoJCQlsMTIuNjk5LTEyLjY5OGwtMTAuOTEtMjEuNzAxYzQuMDEyLTUuNTIxLDcuMzk2LTExLjM1MiwxMC4xNjktMTcuMzk4bDI0LjM2OS0xLjQwOGw0LjY0OS0xNy4zNDhsLTIwLjI0Mi0xMy4zDQoJCQljMC43MDgtNi43MzYsMC43ODQtMTMuNTIzLDAuMTY0LTIwLjI2NmwyMC4yODUtMTMuMzI4bC00LjY0OC0xNy4zNDhsLTIzLjMyNC0xLjM0OWwtMC4yMjcsMC44MjVsLTkuMTA3LDMzLjE3NQ0KCQkJYzIuMTI3LDI0LjMzMi02LjEwNCw0OS40MDEtMjQuNzI5LDY4LjAyNGMtMTYuNzA5LDE2LjcxLTM4LjYwNCwyNS4wNjEtNjAuNTAxLDI1LjA2MWMtMTMuOTk4LDAtMjcuOTk1LTMuNDEtNDAuNjQzLTEwLjIyOQ0KCQkJTDIwNS40OCw4Ny45ODRsMi41ODYsMzIuMzU2TDIyOS4wMTUsNDQuMDM2TDIyOS4wMTUsNDQuMDM2eiIvPg0KCTwvZz4NCjwvZz4NCjwvc3ZnPg0K'; // phpcs:ignore

    /* Pseudo constants */
    public static $PACKAGES_SUBMENU_SLUG  = 'duplicator-pro';
    public static $IMPORT_SUBMENU_SLUG    = 'duplicator-pro-import';
    public static $SCHEDULES_SUBMENU_SLUG = 'duplicator-pro-schedules';
    public static $STORAGE_SUBMENU_SLUG   = 'duplicator-pro-storage';
    public static $TEMPLATES_SUBMENU_SLUG = 'duplicator-pro-templates';
    public static $TOOLS_SUBMENU_SLUG     = 'duplicator-pro-tools';
    public static $SETTINGS_SUBMENU_SLUG  = 'duplicator-pro-settings';
    public static $DEBUG_SUBMENU_SLUG     = 'duplicator-pro-debug';
    public static $IMPORT_INSTALLER_PAGE  = 'duplicator-pro-import-installer';

    // SQL CONSTANTS
    const PHP_DUMP_READ_PAGE_SIZE         = 500;
    const DEFAULT_MYSQL_DUMP_CHUNK_SIZE   = 131072; // 128K
    const MYSQL_DUMP_CHUNK_SIZE_MIN_LIMIT = 1024;
    const MYSQL_DUMP_CHUNK_SIZE_MAX_LIMIT = 1046528;

    /**
     * max query insert sizes (valid on mysqldump and phpdump)
     *
     * @return array
     */
    public static function getMysqlDumpChunkSizes()
    {
        return array(
            "8192"    => '8k',
            "32768"   => '32K',
            "131072"  => '128K',
            "524288"  => '512K',
            "1046528" => '1M');
    }
}
