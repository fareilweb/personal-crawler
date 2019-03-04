<?php
/**
 * PersonalCrawler - Class that manage crawling, scraping, search, index, and more works.
 */
class PersonalCrawler
{
#region #################### Members, properties, fields, static resources ####################

    /** @var LocalizationManager */
    private $localizationManager;

	/** @var ParametersManager */
    private $parametersManager;

    /** @var CrawlingManager */
	private $crawlingManager;

#endregion #################### END OF: Members, properties, fields, static resources ####################



#region #################### Public methods ####################

    /**
	 * The constructor of the class
	 * @param LocalizationManager
	 * @param ParametersManager
     * @param CrawlingManager
	 */
    public function __construct(
        LocalizationManager $localization_manager,
        ParametersManager $parameters_manager,
        CrawlingManager $crawling_manager
    ) {
        // Store dependencies instances
        $this->localizationManager  = $localization_manager;
        $this->parametersManager    = $parameters_manager;
        $this->crawlingManager      = $crawling_manager;
    }

    /**
	 * Do all initialization stuff
	 *
	 * @param array
	 */
    public function Initialize(array $argv)
    {
        // Initialize fields
        $this->urlset = [];

        $this->parametersManager->ParseParams( $argv );

        // User ask for help
        if ($this->parametersManager->help) {
			$this->ShowUserManual();
			return;
		}

        // If an action was set execute it
        if (!empty($this->parametersManager->action)) {
			$action_suffix = "Action";
			$action_method = ucfirst($this->parametersManager->action) . $action_suffix;
			$this->{$action_method}();
		}
    }

	/**
	 * Actions Methods. All action method must end with "Action"
	 * Following methods are related 1 to 1 with available --action parameter
	 * */

    /**
	 * "Help" Action
	 * @return void
	 */
	public function HelpAction()
	{
        $params_ok = $this->parametersManager->TestParamsByAction( __FUNCTION__ );
        if( $params_ok == false ) return;

        $this->ShowUserManual();
	}

    /**
	 * "Crawl" Action
	 * @return void
	 */
    public function CrawlAction()
    {
        $params_ok = $this->parametersManager->TestParamsByAction( __FUNCTION__ );
		if( $params_ok == false ) return;

        $this->crawlingManager->StartCrawling( $this->parametersManager->ToArray() );
    }

#endregion #################### END OF: Public methods ####################



#region #################### Private methods ####################

    /**
	 * Show help documentation
	 *
	 * @return void
	 */
    private function ShowUserManual()
    {
        $lang = LANGUAGE_CODE;
        $user_manual_path = PATH_DOC . DIRECTORY_SEPARATOR . "Personal-Crawler-User-Manual_{$lang}.txt";
        $user_manual_text = file_get_contents($user_manual_path);
        echo $user_manual_text;
    }

#endregion #################### END OF: Private methods ####################

}
