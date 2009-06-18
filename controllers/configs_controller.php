<?php
class ConfigsController extends SettingsAppController {

    var $name = 'Configs';
    var $helpers = array('Html', 'Form');
    var $layout = 'app';

    function admin_index() {
        $this->set('configs', $this->Config->find('all',array(
            'order' => 'Config.name ASC'
            )
        ));
    }

    function admin_save() {

        if (empty($this->data)) {
            $this->Session->setFlash(__('Invalid Config', true));
            $this->redirect(array('action'=>'index'));
        }

        if (!empty($this->data)) {
            foreach($this->data['Config'] as $config)
            {
                if ( strlen($config['name']) == 0 ) continue;
                $this->Config->create();
                if (!$this->Config->save($config))
                {
                    $this->Session->setFlash(__('The Config could not be saved. Please, try again.', true));
                    //$this->Transaction->rollback();
                    $this->redirect('index');
                }
            }
            $this->_write();
            $this->Session->setFlash(__('The Config has been saved', true));
            $this->redirect('index');
        }
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for Config', true));
            $this->redirect('index');
        }
        if ($this->Config->del($id)) {
            $this->Session->setFlash(__('Config deleted', true));
            $this->_write();
            $this->redirect('index');
        }
    }

    function _write()
    {
		$configs = $this->Config->find('all');
		$file = new File(CACHE . 'config.php', true, 0777);
		$lines = array('<?php');
		foreach ($configs as $config) {
			$value = str_replace("'", "\\'", $config['Config']['value']);
			$lines[] = "Configure::write('{$config['Config']['name']}', '{$value}');";
		}
		$lines[] = '?>';
		$file->write(implode("\n", $lines));
    }
}
?>