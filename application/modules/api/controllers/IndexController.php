<?php

class IndexController extends BaseController
{

    public function indexAction ()
    {
        // START HERE
    }
    
    public function partialdownloadserviceAction ()
    {
    	$this->_helper->layout->disableLayout();
//		$this->_helper->viewRenderer->setNoRender(TRUE);
    	include_once Bootstrap::instance()->getOption('includePaths.AC_DCA_Exporter') . '/DCAExporter.php';
    	
    	//Turn all Params into variabels;
        $_REQUEST = $this->_getAllParams();
    	$key = $this->_getParam('key');
    	$version = $this->_getParam('version');
    	$block = $this->_getParam('block');
    	$fieldSet = $this->_getParam('field_set');
    	$kingdom = $this->_getParam('kingdom');
    	$phylum = $this->_getParam('phylum');
    	$class = $this->_getParam('class');
    	$order = $this->_getParam('order');
    	$superfamily = $this->_getParam('superfamily');
    	$family = $this->_getParam('family');
    	$genus = $this->_getParam('genus');
    	$this->view->params = $this->_getAllParams();
    	$this->view->version = $version;
		$this->view->date = $this->_getDate();
    	$this->view->xmlheader = '<?xml version="1.0" encoding="UTF-8"?>';
    	
    	//Check for registration key
    	if(!isset($key)) {
    		$this->view->error = 'no key given';
    		return;
    	}
    	//Check if the key exists
    	if(!$this->_checkKeyExists($key)) {
    		$this->view->error = 'the given key does not exists';
    		return;
    	}
    	
    	//Get version
    	$version = $this->_checkVersionAction($version);
    	
//    	$_REQUEST = $this->_getAllParams();
		if($fieldSet) {
			switch ($fieldSet) {
				case 'classification_only':
				default:
					$block = 1;
					break;
				case 'limited_data':
					$block = 2;
					break;
				case 'complete_data':
					$block = 3;
					break;
			}
			$_REQUEST['block'] = $block;
		}
        if(!$block) {
            $block = 1;
            $_REQUEST['block'] = $block;
        }
        if(!$kingdom && !$phylum && !$class && $order && !$superfamily && $family && $genus) {
//            die(json_encode(array('error' => 'no rank given')));
			$this->view->error = 'no rank given';
			return;
        }
    	
    	$DCAExporter = new DCAExporter($_REQUEST,$block);
    	$zipArchiveName = Bootstrap::instance()->getOption('includePaths.AC_DCA_ExporterBaseUrl') . '/zip/' . $DCAExporter->getZipArchiveName();
    	$zipExists = 'no';
    	if($DCAExporter->archiveExists()) {
    		$zipExists = 'yes';
    	}
    	unset($DCAExporter);
/*    	$output = array(
    		'url' => $zipArchiveName,
    		'urlExists' => $keyExists
    	);*/
    	$this->view->url = $zipArchiveName;
    	$this->view->urlExists = $zipExists;
/*    	$frontController = Zend_Controller_Front::getInstance();
		$frontController->setParam('disableOutputBuffering', true);
    	include_once Bootstrap::instance()->getOption('includePaths.AC_DCA_Exporter') . '/includes/library.php';
    	alwaysFlush();*/
    	
//    	echo json_encode($output);
    	//Start background
    	$phpLocation = Bootstrap::instance()->getOption('includePaths.PHPLocation');
    	$basePath = Bootstrap::instance()->getOption('includePaths.basePath');
    	$newUrl = $basePath.'/scripts/createDCAExport.php';
    	/*foreach($this->_getAllParams() as $key => $value) {
    		if($key != 'controller' && $key != 'action' && $key != 'module') {
    			//Does not force order...
    			if($value == '') {
    				$value = 'EMPTY';
    			}
	    		$newUrl .= " $value";
    		}
    	}*/
//    	$newUrl .= $this->_getValue($version);
    	$newUrl .= $this->_getValue($block);
    	$newUrl .= $this->_getValue($kingdom);
    	$newUrl .= $this->_getValue($phylum);
        $newUrl .= $this->_getValue($class);
        $newUrl .= $this->_getValue($order);
        $newUrl .= $this->_getValue($superfamily);
        $newUrl .= $this->_getValue($family);
        $newUrl .= $this->_getValue($genus);
        
    	$command = "$phpLocation $newUrl > /dev/null &";
		exec( "$command", $arrOutput );
    }
    
    public function completedownloadserviceAction ()
    {
    	$this->_helper->layout->disableLayout();
//		$this->_helper->viewRenderer->setNoRender(TRUE);
    	include_once Bootstrap::instance()->getOption('includePaths.AC_DCA_Exporter') . '/DCAExporter.php';
    	
    	//Turn all Params into variabels;
        $_REQUEST = $this->_getAllParams();
    	$key = $this->_getParam('key');
    	$version = $this->_getParam('version');
/*    	$block = $this->_getParam('block');
    	$fieldSet = $this->_getParam('field_set');*/

    	$this->view->params = $this->_getAllParams();
    	$this->view->version = $version;
    	$this->view->date = $this->_getDate();
    	$this->view->xmlheader = "<?xml version='1.0' encoding='utf-8'?>";
    	
    	//Check for registration key
    	if(!isset($key)) {
    		$this->view->error = 'no key given';
    		return;
    	}
    	//Check if the key exists
    	if(!$this->_checkKeyExists($key)) {
    		$this->view->error = 'the given key does not exist';
    		return;
    	}
    	
//    	$_REQUEST = $this->_getAllParams();
/*		if($fieldSet) {
			switch ($fieldSet) {
				case 'classification_only':
				default:
					$block = 1;
					break;
				case 'limited_data':
					$block = 2;
					break;
				case 'complete_data':
					$block = 3;
					break;
			}
			$_REQUEST['block'] = $block;
		}
        if(!$block) {
            $block = 1;
            $_REQUEST['block'] = $block;
        }*/

    	$versions = $this->_getVersions();
    	$versionExist = false;
    	$versionSortByDate = array();
    	foreach ($versions as $value) {
    		if($value['edition'] == $version) {
    			$this->view->url = $value['url'] = Bootstrap::instance()->getOption('includePaths.AC_DCA_ExporterBaseUrl') . '/' . $value['url'];
    			$this->view->size = $value['size'];
    			$versionExist = true;
    		}
    		$versionSortByDate[strtotime($value['edition'])] = $value;
    	}
    	ksort($versionSortByDate);
    	if($versionExist == false){
			foreach($versionSortByDate as $value) {
		        $this->view->url = $value['url'] = Bootstrap::instance()->getOption('includePaths.AC_DCA_ExporterBaseUrl') . '/' . $value['url'];
		    	$this->view->size = $value['size'];
			}
    	}
    	
/*    	$frontController = Zend_Controller_Front::getInstance();
		$frontController->setParam('disableOutputBuffering', true);
    	include_once Bootstrap::instance()->getOption('includePaths.AC_DCA_Exporter') . '/includes/library.php';
    	alwaysFlush();*/
    	
    }
    	
    public function listreleasesAction ()
    {
    	$this->_helper->layout->disableLayout();
    	$this->view->params = $this->_getAllParams();
    	$this->view->date = $this->_getDate();
    	$this->view->xmlheader = "<?xml version='1.0' encoding='utf-8'?>";

    	$key = $this->_getParam('key');
    	//Check for registration key
    	if(!isset($key)) {
    		$this->view->error = 'no key given';
    		return;
    	}
    	//Check if the key exists
    	if(!$this->_checkKeyExists($key)) {
    		$this->view->error = 'the given key does not exist';
    		return;
    	}
    	
/*    	include_once Bootstrap::instance()->getOption('includePaths.AC_DCA_Exporter') . '/DCAExporter.php';
    	
    	$versions = DCAExporter::getPreviousEditions();*/
    	$versions = $this->_getVersions();
    	foreach($versions as $key => $version) {
    		$versions[$key]['url'] = Bootstrap::instance()->getOption('includePaths.AC_DCA_ExporterBaseUrl') . '/' . $versions[$key]['url'];
    	}
    	$this->view->versions = $versions;
    }
    
    private function _getDate () {
		$gmt = ' GMT'.(substr(date('O'),2,1) == 0 ? '' : substr(date('O'),0,1).substr(date('O'),2,1));
    	return date('d M Y H:i:s') . $gmt;
    }
    
    private function _getVersions () {
    	include_once Bootstrap::instance()->getOption('includePaths.AC_DCA_Exporter') . '/DCAExporter.php';
    	return DCAExporter::getPreviousEditions();
    }
    
    private function _getValue($value)
    {
    	if(isset($value)) {
    		if($value == '') {
    			return ' EMPTY';
    		} else {
	    		return ' ' . $value;
    		}
    	}
    	return ' EMPTY';
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
    
    private function _checkKeyExists ($key) {
    	//Needs a real check
    	if($key == $key) {
			$resource = Bootstrap::instance()->getPluginResource('multidb');
			$this->_db = $resource->getDb('db1');
			$sql = " SELECT COUNT(*) FROM key_store WHERE service_key = '" . $key . "' ";
			if((int)$this->_db->fetchOne($sql)) {
				return true;
			} else {
				return false;
			}
    	} else {
    		return false;
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

