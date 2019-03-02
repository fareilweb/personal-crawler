<?php
// ----------------------------------------------------------------------------------------------------------
const PATH_APP              = __DIR__;
const PATH_CORE             = PATH_APP . DIRECTORY_SEPARATOR . "core";
const PATH_MODELS           = PATH_APP . DIRECTORY_SEPARATOR . "core" . DIRECTORY_SEPARATOR . "models";
const PATH_MANAGERS         = PATH_APP . DIRECTORY_SEPARATOR . "core" . DIRECTORY_SEPARATOR . "managers";
const PATH_DTOS             = PATH_APP . DIRECTORY_SEPARATOR . "core" . DIRECTORY_SEPARATOR . "dtos";
const PATH_HELPERS          = PATH_APP . DIRECTORY_SEPARATOR . "core" . DIRECTORY_SEPARATOR . "helpers";
const PATH_INTERFACES       = PATH_APP . DIRECTORY_SEPARATOR . "core" . DIRECTORY_SEPARATOR . "interfaces";
// ----------------------------------------------------------------------------------------------------------
const PATH_LOCALIZATIONS    = PATH_APP . DIRECTORY_SEPARATOR . "localizations";
const PATH_DOC              = PATH_APP . DIRECTORY_SEPARATOR . "doc";
// ----------------------------------------------------------------------------------------------------------
const PATH_SQLITE           = PATH_APP . DIRECTORY_SEPARATOR . "sqlite_databases";
const SQLITE_DB_NAME        = "my_crawling_data.sqlite";
//-----------------------------------------------------------------------------------------------------------
const LANGUAGE_CODE         = "en-GB";
//-----------------------------------------------------------------------------------------------------------
const USER_AGENT			= "chrome"; // Can be one between: chrome, firefox
const USER_AGENT_OVERRIDE	= ""; // Use any user agent string, will override previus. (Should be the entire UA string)
?>