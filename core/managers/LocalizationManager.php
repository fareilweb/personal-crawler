<?php

class LocalizationManager extends BaseManager {

    /**
     * @var string $current_language_code
     */
    private $current_language_code;

    /**
     * @var object
     */
    private $current_localization_data;

    public function __construct() {
        $this->current_language_code = LANGUAGE_CODE;

        $localizations_file_path = PATH_LOCALIZATIONS . DIRECTORY_SEPARATOR . LANGUAGE_CODE . ".json";
        if (file_exists($localizations_file_path)) {
            $localizations_json_string = file_get_contents($localizations_file_path);
            $this->current_localization_data = json_decode($localizations_json_string, FALSE);
        }
    }

    public function GetString(string $string_id): string {
        $isLocalizationEmpty = empty($this->current_localization_data);
        if ($isLocalizationEmpty)
            return $string_id;

        $string_idExists = property_exists($this->current_localization_data, $string_id);
        if (!$string_idExists)
            return $string_id;

        // All controls passed return the localized string
        return $this->current_localization_data->{$string_id};
    }

    public function GetStringWith(string $string_id, array $strings_to_interpolate = []): string {
        $new_string = $this->GetString($string_id);
        foreach ($strings_to_interpolate as $key => $val) {
            $new_string = str_replace('{' . $key . '}', $val, $new_string);
        }
        return $new_string;
    }

    public function GetStringPlural(string $string_id, int $how_many) {
        if (!is_int($how_many))
            return $string_id;

        if ($how_many == 0) {
            $plural_string_id = $string_id . "_plural_zero";
            return $this->GetString($plural_string_id);
        }

        if ($how_many == 1) {
            $plural_string_id = $string_id . "_plural_one";
            return $this->GetString($plural_string_id);
        }

        if ($how_many > 1) {
            $plural_string_id = $string_id . "_plural_many";
            return $this->GetString($plural_string_id);
        }

        return $string_id;
    }

}
