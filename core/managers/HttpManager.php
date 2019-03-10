<?php

/**
 * An helper to handle network comunications by HTTP/HTTPS protocol
 */
class HttpManager extends BaseManager {

    /**
     * A list of known browser user agents
     *
     * @var array $user_agents
     */
    private $user_agents = [
        'chrome' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.119 Safari/537.36',
        'firefox' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:63.0) Gecko/20100101 Firefox/63.0'
    ];

    /**
     * A list of known browser request headers
     *
     * @var array
     */
    private $headers = [
        'chrome' => [
            'method: GET',
            'path: /',
            'scheme: https',
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            //'accept-encoding: gzip, deflate, br',
            'accept-language: it,en;q=0.9,ru;q=0.8,de;q=0.7,fr;q=0.6',
            'cache-control: no-cache',
            'cookie: ay=b',
            'dnt: 1',
            'pragma: no-cache',
            'upgrade-insecure-requests: 1'
        ],
        'firefox' => []
    ];

    /**
     * The constructor of the class
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Make an HTTP request by cURL
     *
     * @param string $url
     * @param boolean $ignore_redirect
     * @return array
     */
    public function MakeCurlHttpRequest(string $url, bool $ignore_redirect = FALSE): array {
        $curl = curl_init(); // Initialize curl

        // Set hardcoded options
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // Set Timeout for requests
        curl_setopt($curl, CURLOPT_TIMEOUT, WEB_REQUEST_TIMEOUT);

        // Set if follow redirects
        $follow_redirect = !$ignore_redirect; // Invert logic
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, $follow_redirect);

        // Set user agent
        $user_agent = empty(trim(USER_AGENT_OVERRIDE)) ? $this->user_agents[USER_AGENT] : USER_AGENT_OVERRIDE;
        curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);

        // Set headers
        $headers = $this->headers['chrome'];
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        // Set URL
        curl_setopt($curl, CURLOPT_URL, $url);

        // Make request and get info about it
        $curl_exec_result = curl_exec($curl);
        $curl_getinfo_result = curl_getinfo($curl); // NOTE: must be called after curl_exec()!

        $result = [
            'curl_getinfo_result' => $curl_getinfo_result,
            'curl_exec_result' => $curl_exec_result
        ];

        curl_close($curl); // Close curl connection

        return $result;
    }

    /**
     * Set user agent by choose from those available or set an arbitrary one
     * @param string $user_agent_key - The key of the choosed user agent, choice betwen: "chrome", "firefox" or NULL if an arbitrary user agent will be gived.
     * @param string $user_agent - The arbitrary user agent to set or NULL OR NULL if has been already chosen between availables.
     * @return bool - TRUE if success FALSE if fail
     */
    public function SetUserAgent(string $user_agent_key = NULL, string $user_agent = NULL): bool {
        if ($user_agent_key != NULL) {
            $this->current_user_agent = $this->user_agents[$user_agent_key];
            return TRUE;
        }

        if ($user_agent != NULL) {
            $this->current_user_agent = $user_agent;
            return TRUE;
        }

        return FALSE;
    }

}
