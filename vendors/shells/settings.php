<?php
/**
 * Settings Shell File
 *
 * Copyright (c) 2007-2009 David Persson
 *
 * Distributed under the terms of the MIT License.
 * Redistributions of files must retain the above copyright notice.
 *
 * PHP version 5
 * CakePHP version 1.2
 *
 * @package    media
 * @subpackage media.shells
 * @copyright  2007-2009 David Persson <davidpersson@gmx.de>
 * @license    http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link       http://github.com/davidpersson/media
 */
/**
 * Media Shell Class
 *
 * @package    media
 * @subpackage media.shells
 */
class SettingsShell extends Shell {
/**
 * Verbose mode
 *
 * @var boolean
 * @access public
 */
	var $verbose = false;
/**
 * Quiet mode
 *
 * @var boolean
 * @access public
 */
	var $quiet = false;
/**
 * Contains models to load and instantiate
 *
 * @var array
 * @access public
 */
	var $uses = array('Config', 'Route', 'Translation');
/**
 * Width of shell in number of characters per line
 *
 * @var integer
 */
	var $width = 80;
/**
 * Startup
 *
 * @access public
 * @return void
 */
	 function startup() {
		$this->verbose = isset($this->params['verbose']);
		$this->quiet = isset($this->params['quiet']);
		parent::startup();
	}
/**
 * Welcome
 *
 * @access protected
 * @return void
 */
	function _welcome() {
		$this->hr();
		$this->out('Settings Shell');
		$this->hr();
	}
/**
 * Main
 *
 * @access public
 * @return void
 */
	 function main() {
		$this->out('[C]onfig settings');
		$this->out('[R]outes');
		$this->out('[T]ranslations');
		$this->out('[H]elp');
		$this->out('[Q]uit');

		$action = strtoupper($this->in(__('What would you like to import?', true),
										array('C', 'R', 'Q', 'H', 'T'),'q'));

		switch ($action) {
			case 'C':
				$this->configs();
				break;
			case 'R':
				$this->routes();
				break;
			case 'T':
				$this->translations();
				break;
			case 'H':
				$this->help();
				break;
			case 'Q':
				$this->_stop();
		}
		$this->main();
	}
/**
 * Displays help contents
 *
 * @access public
 */
	function help() {
		// 63 chars ===============================================================
		$this->out("NAME");
		$this->out("\settings -- the 1st shell");
		$this->out("\n");
		$this->out("SYNOPSIS");
		$this->out("\tcake settings <command>");
		$this->out("\n");
		$this->out("COMMANDS");
		$this->out("\tconfig");
		$this->out("\t\tImports the config settings from bootstrap.php into the db");
		$this->out("\n");
		$this->out("\troutes");
		$this->out("\t\tImports the route settings from routes.php into the db");
		$this->out("\n");
		$this->out("\translations");
		$this->out("\t\tImports the translations from app/locale/eng/LC_MESSAGES into the db");
		$this->out("\n");
		$this->out("\thelp");
		$this->out("\t\tShows this help message.");
		$this->out("\n");
		$this->out("OPTIONS");
		$this->out("\t-verbose");
		$this->out("\t-quiet");
		$this->out("\n");
	}
/**
 * Reads from the bootsrap.php and imports all the values into the db. Ideally to be used
 * once development is finished and the settings should be quickly changeable via db.
 * 
 * We don't really need to write to the file, as if they want to make any changes the db save
 * will write the file.
 */
 	function configs() {
		$file = new File(APP . 'config' . DS . 'bootstrap.php');
		$contents = $file->read();
		// Get every instance of Configure::write('App.siteName', 'Zaditen');
		$preg = '@Configure::write\(\'([^\']+)\',\s?\'([^\']+)\'\);@';
		preg_match_all($preg, $contents, $matches);
		foreach ($matches[1] as $i => $name) {
			$value = $matches[2][$i];
			$this->Config->create();
			if ( $config = $this->Config->findByName($name) ) {
				$this->Config->id = $config['Config']['id'];
			}
			$this->Config->save(compact('name', 'value'));
		}
 	}
/**
 * Reads from the routes.php and imports all the values into the db. Ideally to be used
 * once development is finished and the settings should be quickly changeable via db.
 * 
 * We don't really need to write to the file, as if they want to make any changes the db save
 * will write the file.
 * 
 * @todo Support extra field.
 */
 	function routes() {
 		
		$file = new File(APP . 'config' . DS . 'routes.php');
		$contents = $file->read();
		// Get every instance of Router::connect('/test', array('controller' => 'locator', 'action' => 'find', 'plugin' => 'baked_simple'), array('display'));
		$lines = explode("\n", $contents);
		foreach ($lines as $line)
		{
			if ( strpos($line, 'Router::connect') === false ) {
				continue;
			}
			
			// Get route url
			preg_match('@Router::connect\(\'(/[^\']*)\'@', $line, $matches);
			if ( empty($matches[1]) ) {
				continue;
			}
			$url = $matches[1];
			
			// Get controller
			preg_match("@controller'\s*=>\s*'([^']+)@", $line, $matches);
			if ( empty($matches[1]) ) {
				continue;
			}
			$controller = $matches[1];
			
			// Get action
			preg_match("@action'\s*=>\s*'([^']+)@", $line, $matches);
			if ( empty($matches[1]) ) {
				continue;
			}
			$action = $matches[1];
			
			// Save
			$this->Route->create();
			if ( $route = $this->Route->findByUrl($url) ) {
				$this->Route->id = $route['Route']['id'];
			}
			$this->Route->save(compact('url', 'controller', 'action'));
		}
 	}
 /**
 * Reads from the app/locale/default.pot and creates a record for every lanuage
 * (obtained from folders in app/locale) and msgid. Ideally to be used once development 
 * is finished and the translations should be quickly changeable via db.
 * 
 * All strings picked up will go in the default domain.
 * 
 * We don't really need to write to the file, as if they want to make any changes the db save
 * will write the file.
 * 
 */
 	function translations()
 	{
 		// Always use default domain.
 		$domain = 'default';
 		
 		// Get list of languages.
		$folder = new Folder(APP . 'locale');
		$ls = $folder->ls(true);
		$languages = $ls[0];
		
		// Read default.pot
		$file = new File(APP . 'locale' . DS . 'default.pot');
		$lines = explode("\n", $file->read());
		$totalLines = count($lines);
		for ( $i = 0; $i < $totalLines; $i++ )
		{
			$line = $lines[$i];
			if ( strpos($line, 'msgid') === false ) {
				continue;
			}
			
			// Get term
			$str = preg_match('@msgid "([^"]+)"@', $line, $matches);
			if ( empty($matches[1]) ) {
				continue;
			}
			$name = $matches[1];
			
			// Save
			foreach ($languages as $language) {
				$this->Translation->create();
				$conditions = compact('name', 'language', 'domain');
				if ( $translation = $this->Translation->find('first', compact('conditions')) ) {
					$this->Translation->id = $translation['Translation']['id'];
				}
				$this->Translation->save(compact('name', 'language', 'domain'));
			}
		}
    }
}
?>