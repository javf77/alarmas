<?php
	class classHostInterFaces {
		private $_dns="";
		private $_ip="";
	    public function __construct($dns=null,$ip=null){
	    	$this->_dns=$dns;
	    	$this->_ip=$ip;
	    }
	    public function getDns (){
	    	return $this->_dns;
	    }
	    public function setDns ($dns){
	    	$this->_dns=$dns;
	    }
	    public function getIp (){
	    	return $this->_ip;

	    }
	    public function setIp ($ip){
	    	$this->_ip=$ip;
	    }

	}
	class classHost{
		private $_hostid=0;
		private $_host="";
		private $_status=0;
		private $_name="";
		private $_ips=null;
		public function __construct($hostid=null,$host=null,$status=null,$name=null,$ips=null){
			$this->_hostid=$hostid;
			$this->_host=$host;
			$this->_status=$status;
			$this->_name=$name
			if ($ips=null)
				$this->_ips=array();
			else
				$this->_ips=$ips;
		}
		public function setHostId ($hostid)
		{
			$this->_hostid=$hostid;
		}
		public function getHostId (){
			return $this->_hostid;
		}
		public function setHost ($host){
			$this->_host=$host;
		}
		public function getHost(){
			return $this->_host;
		}
		public function setStatus ($status){
			$this->_status=$status;
		}
		public function getStatus (){
			return $this->_status;
		}
		public function setName ($name){
			$this->_name=$name;
		}
		public function getName (){
			return $this->_name;
		}
	}
	
	function perform_http_request($method, $url, $data = false)
	{
    	$curl = curl_init();
    	$payload=json_encode($data);
    	switch ($method)
    	{
        	case "POST":
            	curl_setopt($curl, CURLOPT_POST, 1);
            break;
        	case "PUT":
            	curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        	default:
            	if ($data)
                	$url = sprintf("%s?%s", $url, http_build_query($data));
    	}	

    	curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
    	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    	curl_setopt($curl, CURLOPT_URL, $url);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    	$result = curl_exec($curl);

    	curl_close($curl);

    	return json_decode($result);
	}
	function getArrayHostInterface ($params=null, $auth){
		return array("jsonrpc"=>"2.0","method"=>"hostinterface.get","params"=>$params,"id"=>1,"auth"=>$auth);
	}
	function getArrayHost ($params=null, $auth){
		return array("jsonrpc"=>"2.0","method"=>"host.get","params"=>$params,"id"=>1,"auth"=>$auth);
	}
	function getArrayLogin ($usuario,$password){
		//{"jsonrpc": "2.0","method": "user.login","params": {"user": "Admin","password": "zabbix"},"id": 1, "auth": null}
		return array("jsonrpc"=>"2.0","method"=>"user.login","params"=>array("user"=>$usuario,"password"=>$password),"id"=>1,"auth"=>null);
	}
	function getArrayVersionZabbix (){
		return array("jsonrpc"=>"2.0","method"=>"apiinfo.version","params"=>array(),"id"=>1);
	}
	function authZabbix  ($user, $password){ 
    	$result = perform_http_request("POST","http://10.216.16.109/zabbix/api_jsonrpc.php",getArrayLogin($user,$password));
    	if (!isset($result->error))
    		return $result->result;
    	else
    		 throw new Exception($result->error->message." ".$result->error->data);	
	}
    function getVersion (){ 

    	$result = perform_http_request("POST","http://10.216.16.109/zabbix/api_jsonrpc.php",getArrayVersionZabbix());
    	if (!isset($result->error))
    		return $result->result;
    	else
    		throw new Exception($result->error->message." ".$result->error->data);		
	}
	function getHost ($params,$auth){
		$arrParam = getArrayHost($params,$auth);
		$result = perform_http_request("POST","http://10.216.16.109/zabbix/api_jsonrpc.php",$arrParam);
		if (!isset($result->error)){
			$hosts=array();
			foreach ($result->result as $item){
				$
			}
			return $result->result;
		}
		else
    		throw new Exception($result->error->message." ".$result->error->data);
	}
	function getAllHost ($auth){
		 $params=array('output' =>"extend", "selectHosts"=>"extend");	
		 return getHost($params,$auth);
	}
	function getHostById ($hostid,$auth){
		$params=array('output' =>"extend", "selectHosts"=>"extend","filter"=>array ("hostid"=>$hostid));
		return getHost($params,$auth);	
	}
	function getHostInterfaces ($hostid,$auth){
		$params=array('output' =>["ip","dns"], "selectHost"=>"hosts","filter"=>array ("hostid"=>$hostid));
		$arrParam=getArrayHostInterface($params,$auth);
		$result = perform_http_request("POST","http://10.216.16.109/zabbix/api_jsonrpc.php",$arrParam);
		if (!isset($result->error)){
			return $result->result;
		}
		else
    		throw new Exception($result->error->message." ".$result->error->data);	
	}
	try {
    	echo "version:".getVersion();
    	$auth=authZabbix("Admin","zabbix");
    	echo "\n";
    	print_r($auth);
    	echo "\n"; 
		//$allHost=getAllHost($auth);
		//print_r($allHost);
		$host=getHostById("10395",$auth);
		$interfacs=getHostInterfaces("10395",$auth);
		#$host=getHostByName("kqbrmhiper02",$auth);
		print_r($host);
		echo "\n";    
		print_r($interfacs);
		echo "\n";    
	}
	catch (Exception $e) {
    	echo 'Excepción capturada: ',  $e->getMessage(), "\n";
	}
?>