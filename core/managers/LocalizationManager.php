<?php

class LocalizationManager
{
    /**
     * @var string $current_language_code
     */
    private $current_language_code;

    public function __construct()
    {
        $this->current_language_code = LANGUAGE_CODE;
    }

    

}
