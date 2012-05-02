<?php
class RoutesController extends AppController {

	var $name = 'Routes';
	var $components = array('RequestHandler');

    function admin_index() 
    {
    	// Get select options
    	$controllers = $this->_controllers();
		$controllers = array_combine($controllers, $controllers);
        $actions = array();

        // Find routes and transform to Form will find it
        $routes = $this->Route->find('all', array('order' => 'Route.url ASC'));
        $data = array('Route' => array());
        $i = 1;
        foreach ($routes as &$route) {
        	$data['Route'][$i] = $route['Route'];
			$route['Route']['actions'] = $this->_actions($route['Route']['controller']);
			$i++;
        }
    	$this->request->data = $data;
    	
    	// Go
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
        if ($this->Route->delete($id)) {
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
		$this->set('_serialize', array('actions'));
    }

    function _write()
    {
    	// Load library
    	App::uses('File', 'Utility');
    	
    	// Read routes
		$routes = $this->Route->find('all');
		$file = new File(CACHE . 'settings' . DS . 'routes.php', true, 0777);
		$lines = array('<?php');
		foreach ($routes as $route) {
			$controller = strtolower(Inflector::underscore(str_replace('Controller', '', $route['Route']['controller'])));
			if ( empty($route['Route']['extra']) ) {
				$extra = ')';
			}
			else {
				$extra = $route['Route']['extra'];
			}
			$lines[] = "Router::connect('{$route['Route']['url']}', array('controller' => '" . $controller . "', 'action' => '{$route['Route']['action']}'{$extra});";
		}
		$lines[] = '?>';
		$file->write(implode("\n", $lines));
    }

    function _controllers()
    {
    	$controllers = App::objects('controller');
    	unset($controllers[array_search('App', $controllers)]); // removes App
		return $controllers;
    }

    function _actions($controller)
    {
		App::import('Controller', str_replace('Controller', '', $controller));
		$actions = get_class_methods($controller);
		$parent_actions = get_class_methods(get_parent_class($controller));
		$actions = array_diff($actions, $parent_actions);
		return $actions;
    }
}
?>