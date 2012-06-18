<?php 
$path = $argv[9];
//include_once '/home/dseijts/Zend/workspaces/DefaultWorkspace7/AC_DCA_export/DCAExporter.php';
include_once $path;
/*$block = $this->_getParam('block');
$_REQUEST = $this->_getAllParams();*/
$arguments = count($argv);
foreach($argv as $key => $value) {
	if($key != 0 && $key != 1 && ($key+1) < $arguments) {
		switch ($key) {
			case 2:
				$rank = 'kingdom';
				break;
			case 3:
				$rank = 'phylum';
				break;
			case 4:
				$rank = 'class';
				break;
			case 5:
				$rank = 'order';
				break;
			case 6:
				$rank = 'superfamily';
				break;
			case 7:
				$rank = 'family';
				break;
			case 8:
				$rank = 'genus';
				break;
		}
		if($value == 'EMPTY') {
			$value = '';
		}
		$_REQUEST[$rank] = $value;
	} elseif ($key == 1) {
		//Block level
		$_REQUEST['block'] = $value;
	}
}
$DCAExporter = new DCAExporter($_REQUEST,$_REQUEST['block'],$version);
$errors = $DCAExporter->getStartUpErrors();
if($DCAExporter->archiveExists()) {
	echo "Archive already exists\n";
} elseif (!empty($errors)) {
	foreach($errors as $key => $error) {
		echo $error."\n";
	}
} else {
	echo "Creating archive\n";
	$DCAExporter->createMetaXml();
	$DCAExporter->writeData();
	$DCAExporter->zipArchive();
	echo "done";
}