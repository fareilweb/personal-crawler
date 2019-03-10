<?php

class UrlHelper {

    /** @var string */
    public static $DefaultScheme = UrlSchemes::Http;

    /**
     * Try to get the scheme of gived url
     *
     * @param string $url
     * @return string
     */
    public static function GetUrlScheme(string $url): string {
        $scheme = "";
        $url_parts = parse_url($url);
        if (array_key_exists('scheme', $url_parts)) {
            $scheme = $url_parts['scheme'];
        }
        return $scheme;
    }

    /**
     * Test gived url for validity
     * @param string $url - the url to test
     * @return bool - return TRUE if url is valid, FALSE otherwise
     */
    public static function IsValidUrl(string $url): bool {
        $is_valid = true;

        if (empty(trim($url))) {
            $is_valid = false; // Empty url
        }

        //$url_parts = parse_url($url);

        return $is_valid;
    }

    /**
     * Analize and try to fix and build a valid url
     *
     * @param string
     * @param string
     * @return string
     */
    public static function FixUrl($main_url, $parent_url = NULL) {
        $valid_url = "";

        $main_url_parts = parse_url($main_url);
        $parent_url_parts = isset($parent_url) ? parse_url($parent_url) : NULL;

        $valid_url .= isset($main_url_parts['scheme']) ? $main_url_parts['scheme'] . "://" : ( isset($parent_url_parts['scheme']) ? $parent_url_parts['scheme'] . "://" : "" );
        $valid_url .= isset($main_url_parts['host']) ? $main_url_parts['host'] : ( isset($parent_url_parts['host']) ? $parent_url_parts['host'] : "" );
        $valid_url .= isset($main_url_parts['path']) ? $main_url_parts['path'] : ( isset($parent_url_parts['path']) ? $parent_url_parts['path'] : "" );
        $valid_url .= isset($main_url_parts['query']) ? "?" . $main_url_parts['query'] : ""; // ?
        $valid_url .= isset($main_url_parts['fragment']) ? "#" . $main_url_parts['fragment'] : ""; // #

        return $valid_url;
    }


    /**
     * Test gived url to see if it has the same domain of one of the url in the gived urlset
     * @param string
     * @param string[]
     * @return boolean
     */
    public static function IsUrlDomainInUrlUrlsetDomains(string $url, array $url_set) : ?bool {
        $parsed_url = parse_url($url);
        if(empty($parsed_url['host']) && empty($parsed_url['path'])) {
            return NULL;
        }

        foreach ($url_set as $jail_url) {
            $parsed_from_urlset = parse_url($jail_url);
            if ($parsed_url['host'] === $parsed_from_urlset['host'] || $parsed_url['host'] === $parsed_from_urlset['path']) {
                return TRUE;
            }
            if ($parsed_url['path'] === $parsed_from_urlset['host'] || $parsed_url['path'] === $parsed_from_urlset['path']) {
                return TRUE;
            }
        }
        return FALSE;
    }

}
