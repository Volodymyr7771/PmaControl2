<?php

namespace App\Controller;

use Glial\Synapse\Controller;
use \Glial\Sgbd\Sgbd;

class System extends Controller {

    function timeOut($params) {
        $this->view = false;

        $controller = $params[0];
        $action = $params[1];
        $param = $params[2];


        \Glial\Synapse\FactoryController::rootNode($controller, $action, $param);
    }

}
