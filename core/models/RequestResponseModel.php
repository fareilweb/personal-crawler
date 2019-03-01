<?php
class RequestResponseModel
{
    /**
     * @var int $id
     */
    public $id;

    /**
     * @var string $url
     */
    public $url;

    /**
     * @var string $redirect_url
     */
    public $redirect_url;

    /**
     * @var string $ip
     */
    public $ip;

    /**
     * @var int $port
     */
    public $port;

    /**
     * @var int $response_code
     */
    public $response_code;

    /**
     * @var string $content_type
     */
    public $content_type;

    /**
     * @var string $language
     */
    public $language;

    /**
     * @var string $title
     */
    public $title;

    /**
     * @var string $h1
     */
    public $h1;

    /**
     * @var string $h2
     */
    public $h2;

    /**
     * @var string $h3
     */
    public $h3;

    /**
     * @var array $meta_keywords
     */
    public $meta_keywords;

    /**
     * @var string $meta_description
     */
    public $meta_description;

    /**
     * @var array $top_words
     */
    public $top_words;

    /**
     * @var DateTime $insert_date
     */
    public $insert_date;

    /**
     * @var DateTime $update_date
     */
    public $update_date;



    /**
     * Constructor method
     *
     * @param integer $id
     * @param string $url
     * @param string $redirect_url
     * @param string $ip
     * @param integer $port
     * @param integer $response_code
     * @param string $content_type
     * @param string $language
     * @param string $title
     * @param string $h1
     * @param string $h2
     * @param string $h3
     * @param array $meta_keywords
     * @param string $meta_description
     * @param array $top_words
     * @param DateTime $insert_date
     * @param DateTime $update_date
     */
    public function __construct
    (
        int $id = NULL,
        string $url = NULL,
        string $redirect_url = NULL,
        string $ip = NULL,
        int $port = NULL,
        int $response_code = NULL,
        string $content_type = NULL,
        string $language = NULL,
        string $title = NULL,
        string $h1 = NULL,
        string $h2 = NULL,
        string $h3 = NULL,
        array $meta_keywords = [],
        string $meta_description = NULL,
        array $top_words = [],
        DateTime $insert_date = NULL,
        DateTime $update_date = NULL
    ) {
        $this->id = $id;
        $this->url = $url;
        $this->redirect_url = $redirect_url;
        $this->ip = $ip;
        $this->port = $port;
        $this->response_code = $response_code;
        $this->content_type = $content_type;
        $this->language = $language;
        $this->title = $title;
        $this->h1 = $h1;
        $this->h2 = $h2;
        $this->h3 = $h3;
        $this->meta_keywords = $meta_keywords;
        $this->meta_description = $meta_description;
        $this->top_words = $top_words;
        $this->insert_date = $insert_date;
        $this->update_date = $update_date;
    }



    /**
     * Destructor method of the class
     */
    public function __destruct()
    {
        unset( $this->id );
        unset( $this->url );
        unset( $this->redirect_url );
        unset( $this->ip );
        unset( $this->port );
        unset( $this->response_code );
        unset( $this->content_type );
        unset( $this->language );
        unset( $this->title );
        unset( $this->h1 );
        unset( $this->h2 );
        unset( $this->h3 );
        unset( $this->meta_keywords );
        unset( $this->meta_description );
        unset( $this->top_words );
        unset( $this->insert_date );
        unset( $this->update_date );
    }

}