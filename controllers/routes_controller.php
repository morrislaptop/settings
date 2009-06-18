<?php
class RoutesController extends AppController {

	var $name = 'Routes';
	
    function admin_index() {
        $routes = $this->Route->find('all', array('order' => 'Route.url ASC'));
        $controllers = $this->_controllers();
        foreach ($routes as &$route) {
			$route['Route']['actions'] = $this->_actions($route['Route']['controller']);	
        }
        $controllers = array_combine($controllers, $controllers);
        $actions = array();
        $this->set(compact('controllers', 'actions', 'routes'));
    }
    
    function admin_save() 
    {
        if (empty($this->data)) {
            $this->Session->setFlash(__('Invalid Route', true));
            $this->redirect(array('action'=>'index'));
        }

        if ( !empty($this->data) )
        {
            foreach($this->data['Route'] as $route)
            {
                if ( strlen($route['url']) == 0 ) continue;
                $this->Route->create();
                if ( !$this->Route->save($route) ) {
                    $this->Session->setFlash(__('The routes could not be saved. Please, try again.', true));
                    $this->redirect(array('action' => 'index'));
                }
            }
            $this->_write();
            $this->Session->setFlash(__('The routes have been saved', true));
            $this->redirect(array('action' => 'index'));
        }
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for route', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->Route->del($id)) {
            $this->Session->setFlash(__('Route deleted', true));
            $this->_write();
            $this->redirect(array('action' => 'index'));
        }
    }
    
    function admin_actions() {
		$controller = $this->params['url']['controller'];
		$actions = $this->_actions($controller);
		$actions = array_combine($actions, $actions);
		$this->set(compact('actions'));		
    }
    
    function _write()
    {
		$routes = $this->Route->find('all');
		$file = new File(CACHE . 'routes.php', true, 0777);
		$lines = array('<?php');
		foreach ($routes as $route) {
			$lines[] = "Router::connect('{$route['Route']['url']}', array('controller' => '" . low($route['Route']['controller']) . "', 'action' => '{$route['Route']['action']}'));";
		}
		$lines[] = '?>';
		$file->write(implode("\n", $lines));
    }
    
    function _controllers()
    {
		$folder = new Folder(APP . 'controllers');
		$ls = $folder->ls(true);
		$controllers = array();
		foreach ($ls[1] as $file) {
			$controllers[] = ucwords(str_replace('_controller.php', '', $file));
		}
		return $controllers;
    }
    
    function _actions($controller)
    {
		$className = $controller . 'Controller';
		App::import('Controller', $controller);
		$actions = get_class_methods($className);
		$parent_actions = get_class_methods(get_parent_class($className));
		$actions = array_diff($actions, $parent_actions);
		return $actions;
    }
}
?>