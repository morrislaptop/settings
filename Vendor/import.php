<?php
function __ConfigsImport() {
    if (true || defined('CORE_UPDATED')) {
        App::import('Core','ConnectionManager');
        $db =& ConnectionManager::getDataSource('default');
        $query = "SELECT name,value FROM configs;";
        $results = call_user_func_array(array(&$db,'query'),$query);
        foreach($results as $row) {
            Configure::write($row['configs']['name'],$row['configs']['value']);
        }
        define('CONFIGSCONFIG_RUN',true);
    }
}

__ConfigsImport();
?>