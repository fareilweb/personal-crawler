<?php
class RequestResponseDto
{
	/** @var int */			public $id;
    /** @var string */      public $language;
    /** @var string */      public $title;
    /** @var string */      public $h1;
    /** @var string */      public $h2;
    /** @var string */      public $h3;
    /** @var array */       public $meta_keywords;
    /** @var string */    	public $meta_description;
    /** @var array */       public $top_words;
    /** @var DateTime */    public $insert_date;
    /** @var DateTime */	public $update_date;

	public function __construct(  )
	{

	}

	public function __destruct()
	{
		foreach( get_object_vars($this) as $key => $val)
		{
			unset( $this->{$key} );
		}
	}
}
