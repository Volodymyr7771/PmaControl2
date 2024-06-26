<?php

namespace App\Controller;

use \Glial\Synapse\Controller;
use \Glial\Sgbd\Sgbd;


class Replication extends Controller {

    public function index() {
        $this->layout_name = 'default';
        $this->title = __("Replication");
        $this->ariane = " > " . $this->title;

        //$this->javascript = array("");
    }

    public function status() {
        $this->layout_name = 'default';
        $this->title = __("Status");


        $this->ariane = " > " . __("Replication") . " > " . $this->title;

        //$this->javascript = array("");
    }

    public function event() {
        $this->title = __("Events");
        $this->ariane = " > " . __("Replication") . " > " . $this->title;
    }

}
