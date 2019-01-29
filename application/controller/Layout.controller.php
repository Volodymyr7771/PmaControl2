<?php

use \Glial\Synapse\Controller;
use \App\Library\Ariane;
use \App\Library\Debug;

class Layout extends Controller {

    function header($title) {
        $this->set('GLIALE_TITLE', $title);
    }

    function footer() {
        
    }

    function headerPma($param) {

        $title = $param[0];

        $data['auth'] = $this->di['auth']->getAccess();

        $this->set('data', $data);
        $this->set('GLIALE_TITLE', $title);
    }

    function footerPma() {


        $data['auth'] = $this->di['auth']->getAccess();

        if ($data['auth'] !== 1) {
            $user = $this->di['auth']->getuser();
            $data['name'] = $user->firstname . " " . $user->name . " (" . $user->email . ")";
        }
        $this->set('data', $data);
    }

    public function ariane($param) {
        $db = $this->di['db']->sql(DB_DEFAULT);

        $title = array();
        $root = array();
        if (!empty($param[1])) {
            $title = array($param[1]);
        }

        $ariane = new Ariane($db);
        $body = $ariane->buildAriane($this->getMethod());

        $data['ariane'] = $this->buildHtml($body);

        $this->set('data', $data);
    }

    private function getMethod() {
        $elems = explode("/", $_GET['glial_path']);

        $class = '';
        if (!empty($elems[1])) {
            $class = $elems[1];
        }
        $method = '';
        if (!empty($elems[2])) {
            $method = $elems[2];
        }

        return $class . "::" . $method;
    }

    private function buildHtml($arr) {
        return implode(" > ", $arr);
    }

    private function replaceIndex($method) {

        $elems = explode("::", $method);

        $elems[1] = "index";

        return implode("::", $elems);
    }

    public function title($params) {

        $param = \Glial\Synapse\FactoryController::GetRootNode();

        $controller = $param[0];
        $method = $param[1];

        $this->view = false;

        $db = $this->di['db']->sql(DB_DEFAULT);

        $sql = "SELECT * FROM menu where `class`='" . $controller . "' AND `method` = '" . $method . "' ORDER BY group_id ASC LIMIT 1";


        $res = $db->sql_query($sql);

        while ($data['title'] = $db->sql_fetch_array($res, MYSQLI_ASSOC)) {


            return $data['title']['icon'] . " " . $data['title']['title'];
        }

        echo $data['title']['icon'] . " " . $data['title']['title'];
    }
}
