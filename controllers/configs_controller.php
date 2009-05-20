<?php
class ConfigsController extends ConfigsAppController {

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
            //$this->Transaction->begin();
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
            //$this->Transaction->commit();
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
            $this->redirect('index');
        }
    }

}
?>