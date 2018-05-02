<?php

class StorageMongoDB implements IStorage {

    private $db_client;
    private $db;
    private $collection;

    public function __construct($_db_client) {
	$this->db_client = $_db_client;
	$this->db = $this->db_client->PersonalCrawler;
    }

    public function findWebPageByUri($web_page_model) {
	$web_page = $this->db->WebPages->findOne(['uri' => $web_page_model->url]);
	if ($web_page !== null) {
	    return $web_page;
	} else {
	    return FALSE;
	}
    }

    public function insertWebPage($web_page_model) {
	foreach ($web_page_model as $key => $val) {
	    if ($key !== "id") {
		$web_page_model->{$key} = utf8_encode($val);
	    }
	}
	$insertResult = $this->db->WebPages->insertOne($web_page_model);
	if ($insertResult) {
	    return $insertResult->getInsertedId();
	} else {
	    return FALSE;
	}
    }

    public function updateWebPage($web_page_model) {
	foreach ($web_page_model as $key => $val) {
	    if ($key !== "id") {
		$web_page_model->{$key} = utf8_encode($val);
	    }
	}
	$updateResult = $this->db->WebPages->updateOne(
	    ['_id' => $web_page_model->id], ['$set' => $web_page_model]
	);
	if ($updateResult) {
	    return TRUE;
	} else {
	    return FALSE;
	}
    }

}
