<?php

class LinkModel
{
    public $id = NULL;
    public $parent_page = NULL;
    public $href = NULL;
    public $nodeValue = NULL;
    public $textContent = NULL;
    public $attributes = array();
    public $type = NULL;
}


class LinkAttributeModel
{
    public $name = NULL;
    public $value = NULL;
}