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


    public function __construct
    (
        $id = NULL,
        $url = NULL,
        $redirect_url = NULL,
        $ip = NULL,
        $port = NULL,
        $response_code = NULL,
        $content_type = NULL,
        $language = NULL,
        $title = NULL, 
        $h1 = NULL,
        $h2 = NULL,
        $h3 = NULL,    
        $meta_keywords = [],
        $meta_description = NULL,
        $top_words = [],
        $insert_date = NULL,
        $update_date = NULL        
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



}