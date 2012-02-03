<?php 
include_once '/home/dseijts/Zend/workspaces/DefaultWorkspace7/AC_DCA_export/DCAExporter.php';
/*$block = $this->_getParam('block');
$_REQUEST = $this->_getAllParams();*/
$arguments = count($argv);
foreach($argv as $key => $value) {
	if($key != 0 && ($key+1) < $arguments) {
		switch ($key) {
			case 1:
				$rank = 'kingdom';
				break;
			case 2:
				$rank = 'phylum';
				break;
			case 3:
				$rank = 'class';
				break;
			case 4:
				$rank = 'order';
				break;
			case 5:
				$rank = 'superfamily';
				break;
			case 6:
				$rank = 'family';
				break;
			case 7:
				$rank = 'genus';
				break;
		}
		if($value == 'EMPTY') {
			$value = '';
		}
		$_REQUEST[$rank] = $value;
	} elseif (($key+1) == $arguments) {
		$_REQUEST['block'] = $value;
	}
}
$DCAExporter = new DCAExporter($_REQUEST,$_REQUEST['block']);
$errors = $DCAExporter->getStartUpErrors();
if($DCAExporter->archiveExists()) {
	echo "Archive already exists\n";
} elseif (!empty($errors)) {
	foreach($errors as $key => $error) {
		echo $error."\n";
	}
} else {
	$DCAExporter->createMetaXml();
	$DCAExporter->writeData();
	$DCAExporter->zipArchive();
}