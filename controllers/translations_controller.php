<?php
class TranslationsController extends SettingsAppController {

    var $name = 'Translations';
    var $helpers = array('Html', 'Form');
    var $layout = 'app';

    function admin_index() {
        $this->set('translations', $this->Translation->find('all',array(
            'order' => 'Translation.name ASC'
            )
        ));
        $languages = $this->_languages();
        $languages = array_combine($languages, $languages);
        $this->set(compact('languages'));
    }

    function _languages() {
		$folder = new Folder(APP . 'locale');
		$ls = $folder->ls(true);
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
        if ($this->Translation->del($id)) {
            $this->Session->setFlash(__('Translation deleted', true));
            $this->_write();
            $this->redirect('index');
        }
    }

    function _write()
    {
		$translations = $this->Translation->find('all');
		$trunced = array();
		foreach ($translations as $t) {
			$t = $t['Translation'];
			$lang = $t['language'];
			$file = APP . 'locale' . DS . $lang . DS . 'LC_MESSAGES' . DS . 'default.po';
			$file = new File($file);
			if ( !isset($trunced[$lang]) ) {
				$file->open('w');
				$trunced[$lang] = true;
				$file->close();
			}

			$append = 'msgid "' . str_replace('"', '\\"', $t['name']) . "\"\n";
			$append .= 'msgstr "' . str_replace('"', '\\"', $t['value']) . "\"\n\n";
			$file->write($append, 'a');
		}
    }
}
?>