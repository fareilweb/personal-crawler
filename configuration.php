<?php
// App Core Paths ----------------------------------------------------------------------------------------------
const PATH_APP              = __DIR__;
const PATH_CORE             = PATH_APP . DIRECTORY_SEPARATOR . "core";
const PATH_MODELS           = PATH_APP . DIRECTORY_SEPARATOR . "core" . DIRECTORY_SEPARATOR . "models";
const PATH_MANAGERS         = PATH_APP . DIRECTORY_SEPARATOR . "core" . DIRECTORY_SEPARATOR . "managers";
const PATH_HELPERS          = PATH_APP . DIRECTORY_SEPARATOR . "core" . DIRECTORY_SEPARATOR . "helpers";
const PATH_INTERFACES       = PATH_APP . DIRECTORY_SEPARATOR . "core" . DIRECTORY_SEPARATOR . "interfaces";
const PATH_ENUMERATIONS     = PATH_APP . DIRECTORY_SEPARATOR . "core" . DIRECTORY_SEPARATOR . "enumerations";
const PATH_DOC              = PATH_APP . DIRECTORY_SEPARATOR . "doc";
// Localization ------------------------------------------------------------------------------------------------
const PATH_LOCALIZATIONS    = PATH_APP . DIRECTORY_SEPARATOR . "localizations";
const LANGUAGE_CODE         = "en-GB"; // Language (must be the name of an existent localization file, no ext)
// SQLite ------------------------------------------------------------------------------------------------------
const PATH_SQLITE           = PATH_APP . DIRECTORY_SEPARATOR . "sqlite_databases";
const SQLITE_DB_NAME        = "my_crawling_data.sqlite";
// App behavior and various ------------------------------------------------------------------------------------
const USER_AGENT			= "chrome"; // Can be one between: chrome, firefox
const USER_AGENT_OVERRIDE	= ""; // Use any user agent string, will override previus. (Should be the entire UA string)
const WEB_REQUEST_TIMEOUT	= 5; // How much seconds wait for response before consider url unavailable
const CONTENT_REFRESH_RATE  = 3; // How many days to wait before retrieve again stored contents
const IGNORE_REFRESH_RATE   = TRUE; // Set to TRUE if you want to update content from url, every time.