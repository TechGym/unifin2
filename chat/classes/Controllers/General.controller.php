<?php
class GeneralController extends General
{
    private $username;
    private $id;

    public function __construct($username, $id)
    {
        $this->username = $username;
        $this->id = $id;
    }
}
