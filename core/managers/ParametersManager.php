<?php
class ParametersManager
{
    #region # Properties

	/** @var boolean */
    public $help;

	/** @var string */
	public $action;

	/** @var string */
	public $url;

	/** @var boolean - default value is "FALSE" */
	public $ignore_redirect;

	#endregion # Properties

    public function __construct (
        boolean $help = NULL,
        string $action = NULL,
		string $url = NULL,
        string $ignore_redirect = NULL
    ) {
        $this->help = $help;
        $this->action = $action;
		$this->url = $url;
        $this->ignore_redirect = $ignore_redirect;
    }


    /**
	 * Get parameters array and switch the right action
	 *
	 * @param array $params [parameters]
	 * @return void
	 */
	public function ParseParams( array $params )
	{
		unset( $params[0] ); // Remove first argoument (is the script file name)
		$params = array_values( $params ); // Re-Index argouments array

		if(count($params) == 0)
			return;

		// Get "help" parameter
		$this->help = $this->HasParam("--help", "-h", $params) ? TRUE : FALSE;

		// Get "action" parameter
		$this->action = $this->GetParam("--action", "-a", $params, "crawl");

		// Get "url" parameter
		$this->url = $this->GetParam("--url", "-u", $params, NULL);

		// Get "follow redirect" parameter
		$this->ignore_redirect = $this->HasParam("--ignore-redirect", "-ir", $params) ? TRUE : FALSE;
	}


    public function __destruct()
    {
        foreach( get_object_vars($this) as $key => $val)
		{
			unset( $this->{$key} );
		}
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
	public function GetParam(string $extended_param_key, string $short_param_key, array $params = [], $default_value = "") : string
	{
		/* search extended version */
		$extended_param_key_index = array_search( $extended_param_key, $params );
		if ( $extended_param_key_index !== FALSE && count($params) > $extended_param_key_index + 1 )
			return $params[ $extended_param_key_index + 1  ];

		/* search short version	*/
		$short_param_key_index = array_search( $short_param_key, $params );
		if ( $short_param_key_index !== FALSE && count($params) > $short_param_key_index + 1 )
			return $params[ $short_param_key_index + 1  ];

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
	public function HasParam( string $extended_param_key, string $short_param_key, $params = [] ) : bool
	{
		/* search extended version */
		$extended_param_key_index = array_search( $extended_param_key, $params );
		if ( $extended_param_key_index !== FALSE )
			return TRUE;

		/* search short version	*/
		$short_param_key_index = array_search( $short_param_key, $params );
		if ( $short_param_key_index !== FALSE )
			return TRUE;

		/* Param not found */
		return FALSE;
	}

}