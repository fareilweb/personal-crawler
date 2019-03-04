<?php

class ParametersManager
{

#region #################### Members, properties, fields, static resources ####################

    /** Statics Resourcs */

    /** @var array */
    private static $requiredParamsByAction = [
        'CrawlAction' => [ 'urlset' ],
        'HelpAction' => []
    ];


    /** @var LocalizationManager */
    private $localizationManager;

    /** @var boolean */
    public $help;

    /** @var string */
    public $action;

    /** @var string[] */
    public $urlset;

    /** @var boolean - default value is "FALSE" */
    public $ignore_redirect;

#endregion #################### Members, properties, fields, static resources ####################


    public function __construct (
        $localization_manager
    ) {
        $this->localizationManager = $localization_manager;
    }

    public function __destruct() {
        foreach (get_object_vars($this) as $key => $val) {
            unset($this->{$key});
        }
    }

    /**
     * Get parameters array and switch the right action
     *
     * @param array $real_params [parameters]
     * @return void
     */
    public function ParseParams( array $params ) {
        unset($params[0]); // Remove first argoument (is the script file name)

        $real_params = array_values($params); // Re-Index argouments array now without script name

        if (count($real_params) == 0)
        {
            echo $this->localizationManager->GetString("no_params_passed_error");
            return;
        }

        // Get "help" parameter
        $this->help = $this->HasParam("--help", "-h", $real_params) ? TRUE : FALSE;

        // Get "action" parameter
        $this->action = $this->GetParam("--action", "-a", $real_params, "crawl");

        // Get "urlset" parameter
        $this->urlset = $this->GetParamValueSet("--urlset", "-us", $real_params);

        // Get "follow redirect" parameter
        $this->ignore_redirect = $this->HasParam("--ignore-redirect", "-ir", $real_params) ? TRUE : FALSE;
    }

    /**
     * Get the name of requested action, and check if all needed parameters are set
     *
     * @param string
     * @return bool - return TRUE if all required paraeters are passed FALSE otherwise
     */
	public function TestParamsByAction( string $action ) : bool
	{
        $required_parameters = ParametersManager::$requiredParamsByAction[$action];
        $required_parameters_count = count($required_parameters);

        if($required_parameters_count > 0)
        {
            $missing_parameters_count = 0;
            foreach($required_parameters as $paramName) {
                if( empty($this->{$paramName}) ) {
                    $missing_parameters_count++;
                }
            }
            if($missing_parameters_count > 0) {
                echo $this->localizationManager->GetStringPlural("missing_params", $missing_parameters_count);
                return false;
            }
        }

        return true;
	}

    /**
     * Retrieve the gived parameter value set as an array from $params array
     *
     * @param string $extended_param_key - the extended version of the param key
     * @param string $short_param_key - the short version of the param key
     * @param array $params - the list of parameters passed by the user
     * @return string[] - the set of values of the searched param or an empty array if nothing was found
     */
    public function GetParamValueSet( string $extended_param_key, string $short_param_key, array $params = [] )
    {
        #region # Nested functions area
            function GetValues($start_index, $_params) : array {
                $value_set = []; $index = $start_index; $next_param_found = FALSE;
                while( $next_param_found == FALSE && $index < count($_params) ) {
                    array_push($value_set, $_params[$index]);
                    $index++;
                    if(array_key_exists($index, $_params) && (StringStartsWith($_params[$index], "--") || StringStartsWith($_params[$index], "-"))) {
                        $next_param_found = TRUE;
                    }
                }
                return $value_set;
            }
            function StringStartsWith($haystack, $needle) : bool {
                $length = strlen($needle);
                return (substr($haystack, 0, $length) === $needle);
            }
        #endregion # END OF: Nested functions area

        /* search extended version */
        $extended_param_key_index = array_search($extended_param_key, $params);
        if ($extended_param_key_index !== FALSE && count($params) > $extended_param_key_index + 1)
        {
            return GetValues($extended_param_key_index+1, $params);
        }

        /* search short version	 */
        $short_param_key_index = array_search($short_param_key, $params);
        if ($short_param_key_index !== FALSE && count($params) > $short_param_key_index + 1)
        {
            return GetValues($short_param_key_index+1, $params);
        }

        /* Parameter not found or invalid, return an empty array */
        return [];
    }

    /**
     * Retrieve the gived parameter value from $params array
     *
     * @param string $extended_param_key - the extended version of the param key
     * @param string $short_param_key - the short version of the param key
     * @param mixed $default_value - the fallback value to use if no one will be found
     * @param array $params - the list of parameters passed by the user
     * @return string - the value of the searched param or a default value if parameter will not be found or invalid
     */
    public function GetParam(string $extended_param_key, string $short_param_key, array $params = [], $default_value = ""): string {
        /* search extended version */
        $extended_param_key_index = array_search($extended_param_key, $params);
        if ($extended_param_key_index !== FALSE && count($params) > $extended_param_key_index + 1)
            return $params[$extended_param_key_index + 1];

        /* search short version	 */
        $short_param_key_index = array_search($short_param_key, $params);
        if ($short_param_key_index !== FALSE && count($params) > $short_param_key_index + 1)
            return $params[$short_param_key_index + 1];

        /* Parameter not found or invalid, return empty string */
        return "";
    }

    /**
     * Test if the gived array contains the gived param key
     *
     * @param string $extended_param_key - the extended version of the param key
     * @param string $short_param_key - the short version of the param key
     * @param array $params - the list of parameters passed by the user
     * @return boolean - return TRUE if parameter key was found FALSE otherwise
     */
    public function HasParam(string $extended_param_key, string $short_param_key, $params = []): bool {
        /* search extended version */
        $extended_param_key_index = array_search($extended_param_key, $params);
        if ($extended_param_key_index !== FALSE)
            return TRUE;

        /* search short version	 */
        $short_param_key_index = array_search($short_param_key, $params);
        if ($short_param_key_index !== FALSE)
            return TRUE;

        /* Param not found */
        return FALSE;
    }

    /**
     * Export all current parameters values as an array
     *
     * @return array
     */
    public function ToArray() : array
    {
        return [
            'help'              => $this->help,
            'action'            => $this->action,
            'urlset'            => $this->urlset,
            'ignore_redirect'   => $this->ignore_redirect
        ];
    }

}













