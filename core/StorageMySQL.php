<?php

class StorageMySQL implements IStorage {

    private $mysqli;

    public function __construct() {
	$this->connectToDatabase();
    }

    public function connectToDatabase() {
	$this->mysqli = new mysqli(Config::$mysql_host, Config::$mysql_user, Config::$mysql_pass, Config::$mysql_db);
	if($this->mysqli->connect_errno) {
            echo "Failed to connect to MySQL: " . $this->mysqli->connect_error;
	    return FALSE;
	}
        return TRUE;
    }


    public function findWebPageByUri($uri)
    {
        if($uri)
        {
            $query = "SELECT * FROM webpages WHERE uri = '" . $uri . "';";
            $result = $this->mysqli->query($query);
            if(!$result || $result->num_rows == 0) {
                return FALSE;
            } else {
                $web_page = new WebPageModel();
                $web_page->loadFromObject($result->fetch_object());
                return $web_page;
            }
        }
    }

    public function insertWebPage($web_page_model) {
	    $ps = $this->mysqli->prepare(
            "INSERT INTO webpages (
            id,
            uri,
            lang,
            title,
            h1,
            h2,
            metakeywords,
            metadescription,
            top_word,
            response_code,
            timestamp
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);"
        );

	    $ps->bind_param(
            'isssssssssi',
            $id,
            $uri,
            $lang,
            $title,
            $h1,
            $h2,
            $metakeywords,
            $metadescription,
            $top_word,
            $response_code,
            $timestamp
        );

        $id = NULL;
        $uri = $web_page_model->uri;
        $lang = $web_page_model->lang;
        $title = $web_page_model->title;
        $h1 = $web_page_model->h1;
        $h2 = $web_page_model->h2;
        $metakeywords = $web_page_model->metakeywords;
        $metadescription = $web_page_model->metadescription;
        $top_word = $web_page_model->top_word;
        $response_code = $web_page_model->response_code;
        $timestamp = $web_page_model->timestamp;

        $result = $ps->execute();

        $ps->close();

        if(!$result) {
            return FALSE;
        }

	return $this->mysqli->insert_id;
    }

    public function updateWebPage($web_page_model) {
	$ps = $this->mysqli->prepare(
            "UPDATE webpages
             SET
                uri = ?,
                lang = ?,
                title = ?,
                h1 = ?,
                h2 = ?,
                metakeywords = ?,
                metadescription = ?,
                top_word = ?,
                response_code = ?,
                timestamp = ?
             WHERE id = ?"
        );

        $ps->bind_param(
            'sssssssssii',
            $uri,
            $lang,
            $title,
            $h1,
            $h2,
            $metakeywords,
            $metadescription,
            $top_word,
            $response_code,
            $timestamp,
            $id
        );


        $uri = $web_page_model->uri;
        $lang = $web_page_model->lang;
        $title = $web_page_model->title;
        $h1 = $web_page_model->h1;
        $h2 = $web_page_model->h2;
        $metakeywords = $web_page_model->metakeywords;
        $metadescription = $web_page_model->metadescription;
        $top_word = $web_page_model->top_word;
        $response_code = $web_page_model->response_code;
        $timestamp = $web_page_model->timestamp;
        $id = $web_page_model->id;

        $result = $ps->execute();

        $ps->close();

        return $result;
    }

}
