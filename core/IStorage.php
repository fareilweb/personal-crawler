<?php 
interface IStorage {

    public function findWebPageByUri($web_page_model);

    public function insertWebPage($web_page_model);
    
    public function updateWebPage($web_page_model); 
    
}