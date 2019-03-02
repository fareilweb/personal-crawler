<?php
class RequestResponseModel
{
    /** @var int $id */                     public $id;
    /** @var string $language */            public $language;
    /** @var string $title */               public $title;
    /** @var string $h1 */                  public $h1;
    /** @var string $h2 */                  public $h2;
    /** @var string $h3 */                  public $h3;
    /** @var array $meta_keywords */        public $meta_keywords;
    /** @var string $meta_description */    public $meta_description;
    /** @var array $top_words */            public $top_words;
    /** @var DateTime $insert_date */       public $insert_date;
    /** @var DateTime $update_date */       public $update_date;


    /**
     * Constructor method
     *
     * @param integer $id
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
        foreach( get_object_vars($this) as $key => $val)
		{
			unset( $this->{$key} );
		}
    }

}