<?php
	$array=array();
	$array["hostame"]="Hipersor01";
	$array["macaddress"]="b4:2e:99:06:1f:0f";
	$array["ip"]="192.168.1.72";
	$array["SerialNumber"]="YO LO ARME";
	$array["ProductName"]="B450M DS3H";
	$array["Managment"]="No asignada";
	$vm=array();
	$ips=array(array ("macaddress"=>"b4:2e:99:06:1f:0f",
			    "ip"=>"192.168.1.73"),
				array("macaddress"=>"b4:2e:99:06:1f:0f",
			    "ip"=>"192.168.1.77"));
	$imgs = array(array("img"=>"/kvmImages/vm01.img", "size"=>"70"));
	$vm[]= array('hostname' =>"vm01" , 
				"ips"=>$ips,
			    "VCP"=>8,
			    "maxmemory"=>16000,
			    "memory"=>16000,
				"imgs"=>$imgs,
				"activo"=>"true");

	$ips=array(array ("macaddress"=>"b4:2e:99:06:1f:0f","ip"=>"192.168.1.74"),
			   array ("macaddress"=>"b4:2e:99:06:1f:0f", "ip"=>"192.168.1.77"));
	$imgs = array(array("img"=>"/kvmImages/vm01.img", "size"=>"70"),
				array("img"=>"/kvmImages/vm01_2.img", "size"=>"70"),
				array("img"=>"/kvmImages/vm01_3.img", "size"=>"70")	);
	$vm[]= array('hostname' =>"vm02" , 
				"ips"=>$ips,
			    "VCP"=>8,
			    "maxmemory"=>16000,
			    "memory"=>16000,
			    "imgs"=>$imgs,
				"activo"=>"true");

	$array["VM"]=$vm;
	header('Content-Type: application/json');
	echo json_encode($array);

?>