<?php
class WebPageModel implements MongoDB\BSON\Persistable
{
    public $id;
    public $uri;
    public $lang;
    public $title;
    public $h1;
    public $h2;
    public $metakeywords;
    public $metadescription;
    public $top_word;
    public $response_code;
    public $timestamp;

    public function __construct($uri = "") {
        $this->id = new MongoDB\BSON\ObjectID;
        if( !empty($uri) ){
	    $this->uri = $uri;	
	}
    }

    function bsonSerialize() {
        return [
            '_id' => $this->id,
            'uri' => $this->uri,
            'lang' => $this->lang,
            'title' => $this->title,
            'h1' => $this->h1,
            'h2' => $this->h2,
            'metakeywords' => $this->metakeywords,
            'metadescription' => $this->metadescription,
            'top_word' => $this->top_word,
            'response_code' => $this->response_code,
            'timestamp' => $this->timestamp                
        ];
    }   

    function bsonUnserialize(array $data) {
        $this->id = $data['_id'];
        $this->uri = $data['uri'];
        $this->lang = $data['lang'];
        $this->title = $data['title'];
        $this->h1 = $data['h1'];
        $this->h2 = $data['h2'];
        $this->metakeywords = $data['metakeywords'];
        $this->metadescription = $data['metadescription'];
        $this->top_word = $data['top_word'];
        $this->response_code = $data['response_code'];
        $this->timestamp = $data['timestamp'];
    }

}
