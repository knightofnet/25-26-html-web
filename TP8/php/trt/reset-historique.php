<?php

require_once('../config.php');

require_once(PROJECT_ROOT . '/php/funcs/base_func.php');
require_once(PROJECT_ROOT . '/php/services/vols_services.php');


resetVolsEnregistres();

redirectTo(BASE_URL . '/recherchesPrecedentes.php');