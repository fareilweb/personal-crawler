<?php

class UrlHelper {

    public static $UrlSchemes = [
        'http' => 'http',
        'https' => 'https',
        'ftp' => 'ftp',
        'ftps' => 'ftps',
        'sftp' => 'sftp',
        'mailto' => 'mailto',
        'tel' => 'tel',
        'skype' => 'skype',
        'whatsapp' => 'whatsapp'
    ];

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

        $url_parts = parse_url($main_url);
        if (isset($parent_url)) {
            $parent_url_parts = parse_url($parent_url);
        }

        // Fix Url scheme
        if( isset( $url_parts['scheme'] ) ) {
            $valid_url .= $url_parts['scheme'];
        } else {
            
        }

        $valid_url .= isset($url_parts['scheme']) ? $url_parts['scheme'] . "://" : ( isset($parent_url_parts['scheme']) ? $parent_url_parts['scheme'] . "://" : "" );
        $valid_url .= isset($url_parts['host']) ? $url_parts['host'] : ( isset($parent_url_parts['host']) ? $parent_url_parts['host'] : "" );
        $valid_url .= isset($url_parts['path']) ? $url_parts['path'] : ( isset($parent_url_parts['path']) ? $parent_url_parts['path'] : "" );
        $valid_url .= isset($url_parts['query']) ? "?" . $url_parts['query'] : ""; // ?
        $valid_url .= isset($url_parts['fragment']) ? "#" . $url_parts['fragment'] : ""; // #

        return $valid_url;
    }

}
