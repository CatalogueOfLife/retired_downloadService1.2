<?php

class IndexController extends BaseController
{

    public function indexAction ()
    {
        // START HERE
    }
    
    public function retrieveurlAction ()
    {
    	$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);
    	include_once Bootstrap::instance()->getOption('includePaths.AC_DCA_Exporter') . '/DCAExporter.php';
    	//Check for registration key
    	$key = $this->_getParam('key');
    	//Get version
    	$version = $this->_getParam('version');
    	$version = $this->_checkVersionAction($version);
    	
    	$block = $this->_getParam('block');
//    	$_REQUEST = $this->_getAllParams();
        $_REQUEST = $this->_getAllParams();
        if(!$block) {
            $block = 1;
            $_REQUEST['block'] = $block;
        }
        if(!$this->_getParam('kingdom') && !$this->_getParam('phylum') && !$this->_getParam('class') &&
            !$this->_getParam('order') && !$this->_getParam('superfamily') && !$this->_getParam('family') &&
            !$this->_getParam('genus')) {
            die(json_encode(array('error' => 'no rank given')));
        }
    	
    	$DCAExporter = new DCAExporter($_REQUEST,$block);
    	$zipArchiveName = Bootstrap::instance()->getOption('includePaths.AC_DCA_ExporterBaseUrl') . '/zip/' . $DCAExporter->getZipArchiveName();
    	$keyExists = false;
    	if($DCAExporter->archiveExists()) {
    		$keyExists = true;
    	}
    	unset($DCAExporter);
    	$output = array(
    		'url' => $zipArchiveName,
    		'urlExists' => $keyExists
    	);
    	$frontController = Zend_Controller_Front::getInstance();
		$frontController->setParam('disableOutputBuffering', true);
    	include_once Bootstrap::instance()->getOption('includePaths.AC_DCA_Exporter') . '/includes/library.php';
    	alwaysFlush();
    	
    	echo json_encode($output);
    	//Start background
    	$phpLocation = Bootstrap::instance()->getOption('includePaths.PHPLocation');
    	$bastPath = Bootstrap::instance()->getOption('includePaths.basePath');
    	$newUrl = $bastPath.'/scripts/createDCAExport.php';
    	foreach($this->_getAllParams() as $key => $value) {
    		if($key != 'controller' && $key != 'action' && $key != 'module') {
    			//Does not force order...
    			if($value == '') {
    				$value = 'EMPTY';
    			}
	    		$newUrl .= " $value";
    		}
    	}
    	$command = "$phpLocation $newUrl > /dev/null &";
		exec( "$command", $arrOutput );
    }
    
    public function getVersionsAction ()
    {
    	$versions = array (
    		'bs_v19',
			'deze_bestaaat_niet_echt'
    	);
    	return json_encode($versions);
    }
    
    private function _checkVersionAction ($version)
    {
    	//Check if the given version exists.
    	if($version) {
	    	return $version;
    	} else {
    		return null;
    	}
    }
    
    public function generateKeyAction ()
    {
        $domain = trim($this->_param('domain', null));
        if (substr($domain, 0, 7) === 'http://') {
            $domain = substr($domain, 7);
        }
        $keyStore = new api_models_dao_KeyStore();
        if(!$keyStore->load($domain)) {
            $key = md5($domain . '||' . time());
            $keyStore->setEmail($this->_param('email',null));
            $keyStore->setServiceKey($key);
            $keyStore->save();
        }
        $this->_response->setBody($keyStore->getServiceKey());
        $this->_response->sendResponse();
        exit;
    }

}

