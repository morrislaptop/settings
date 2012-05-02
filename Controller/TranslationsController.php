<?php
class TranslationsController extends SettingsAppController {

    var $name = 'Translations';
    var $helpers = array('Html', 'Form');
    var $layout = 'app';

    function admin_index() 
    {
    	$order = 'Translation.name ASC';
        $translations = $this->Translation->find('all', compact('order'));
        
        // Transform data so form will find it.
    	$data = array('Translation' => array());
    	$i = 1;
    	foreach ($translations as $translation) {
			$data['Translation'][$i] = $translation['Translation'];
			$i++;
    	}
    	$this->data = $data;
        
        // Go
        $languages = $this->_languages();
        $languages = array_combine($languages, $languages);
        $this->set(compact('languages', 'translations'));
    }

    function _languages() {
    	// Load library
    	App::uses('Folder', 'Utility');
		$folder = new Folder(APP . 'Locale');
		$ls = $folder->read(true);
		return $ls[0];
    }

    function admin_save() {

        if (empty($this->data)) {
            $this->Session->setFlash(__('Invalid Translation', true));
            $this->redirect(array('action'=>'index'));
        }

        if (!empty($this->data)) {
            //$this->Transaction->begin();
            foreach($this->data['Translation'] as $translation)
            {
                if ( strlen($translation['name']) == 0 ) continue;
                $this->Translation->create();
                if (!$this->Translation->save($translation))
                {
                    $this->Session->setFlash(__('The translations could not be saved. Please, try again.', true));
                    //$this->Transaction->rollback();
                    $this->redirect('index');
                }
            }
            //$this->Transaction->commit();
            $this->_write();
            $this->Session->setFlash(__('The translations have been saved', true));
            $this->redirect('index');
        }
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for translation', true));
            $this->redirect('index');
        }
        if ($this->Translation->delete($id)) {
            $this->Session->setFlash(__('Translation deleted', true));
            $this->_write();
            $this->redirect('index');
        }
    }

    function _write()
    {
    	// Load library
    	App::uses('File', 'Utility');
    	
		$translations = $this->Translation->find('all');
		$trunced = array();
		foreach ($translations as $t) 
		{
			$t = $t['Translation'];
			if ( empty($t['value']) ) {
				continue;
			}
			
			$lang = $t['language'];
			$domain = $t['domain'];
			$filePath = APP . 'Locale' . DS . $lang . DS . 'LC_MESSAGES' . DS . $domain . '.po';
			$file = new File($filePath);
			if ( !isset($trunced[$filePath]) ) {
				$file->open('w');
				$trunced[$filePath] = true;
				$file->close();
			}

			$append = 'msgid "' . str_replace('"', '\\"', $t['name']) . "\"\n";
			$append .= 'msgstr "' . str_replace('"', '\\"', $t['value']) . "\"\n\n";
			$file->write($append, 'a');
		}
    }
}
?>