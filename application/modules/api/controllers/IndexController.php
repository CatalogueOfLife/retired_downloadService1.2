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
    	$block = $this->_getParam('block');
    	$_REQUEST = $this->_getAllParams();
    	$DCAExporter = new DCAExporter($_REQUEST,$block);
    	$zipArchiveName = Bootstrap::instance()->getOption('includePaths.AC_DCA_ExporterBaseUrl') . '/' . $DCAExporter->getZipArchiveName();
    	$keyExists = false;
    	if($DCAExporter->archiveExists()) {
    		$keyExists = true;
    	}
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
	    		$newUrl .= " $value";
    		}
    	}
    	$command = "$phpLocation $newUrl &";
    	die($command);
		exec( "$command", $arrOutput );
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

