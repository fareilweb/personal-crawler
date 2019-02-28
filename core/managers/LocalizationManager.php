<?php

class LocalizationManager
{
    /**
     * @var string $current_language_code
     */
    private $current_language_code;

    /**
     * @var object
     */
    private $current_localization_data;

    public function __construct()
    {
        $this->current_language_code = LANGUAGE_CODE;


        $localizations_file_path = PATH_LOCALIZATIONS . DIRECTORY_SEPARATOR . LANGUAGE_CODE . ".json";
        if(file_exists($localizations_file_path)) 
        {
            $localizations_json_string = file_get_contents( $localizations_file_path );            
            $this->current_localization_data = json_decode( $localizations_json_string, FALSE );
        }
        
    }

    public function GetString(string $stringId) : string
    {
        return "";
    }  

}
