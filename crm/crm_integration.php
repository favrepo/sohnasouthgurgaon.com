<?php 
	require_once("nusoap.php");
	function create_connection() {
    
    	$client = new nusoap_client(CRM_WSDL_URL, true);
    	$err = $client->getError();
		global $log;
    	if ($err) {
			$log->LogError("Constructor error ".$err." ".htmlspecialchars($client->getDebug(), ENT_QUOTES));
    		return;
    	}
    
    	return $client;
    }
	
	
	
	function get_user_id($client,$session_id){
    
    	$user_id = $client->call('get_user_id', array('session' => $session_id));
    
    	return $user_id;
    
    }
	
	function update_lead($client, $session_id,$name_value_list,$user_id){
    	$client->call('update_lead',array('session'=>$session_id,'user_id'=>$user_id,'name_value_list'=>$name_value_list));
    }
    
    function login_favista_crm($client) {
    
    	$crm_user = '';
    	$crm_password = '';
    
    	$result = $client->call('login', array('user_auth' => array('user_name' => 'website@favista.in', 'password' => md5('w$b&&cu#e@'), 'version' => '.01'), 'application_name' => 'SoapTest'));
    	
		global $log;
		
        if ($client->fault) {
    		$log->LogError('Fault (Expect - The request contains an invalid SOAP body)');
    		return;
			//throw new Exception('Fault (Expect - The request contains an invalid SOAP body)');
    	} else {
    		$err = $client->getError();
    		if ($err) {
    			$log->LogError('Error 2'.$err." found ".$client->getDebug());
    			return;
			//	throw new Exception('Error 2'.$err." found ".debug_backtrace());
    		} else {
    		}
    	}
    
    	$session_id = $result['id'];
    	return $session_id;
    }
	
	function updateLead($columnArr,$user_id){
    	 
    	$client = create_connection();
    	$session_id = login_favista_crm($client);
    	$name_value_list = array();
    	foreach($columnArr as $col=>$val){
    		$name_value_pair['name'] = $col;
    		$name_value_pair['value'] = $val;
    		$name_value_list[] = $name_value_pair;
    	}
    	
    	
    	update_lead($client, $session_id, $name_value_list,$user_id);
    
    }
	
	
    function insertCrmInterest($user_id,$property_id,$controller){
    
    	$client = create_connection();
    	$session_id = login_favista_crm($client);
    	 
    	add_to_crm_interest($client, $session_id, $user_id,$property_id,$controller);
    
    }
    
    function insertCrmPropertyTask($user_id,$property_id,$description){
    	$client = create_connection();
    	$session_id = login_favista_crm($client);
    	$client->call('create_task_against_visit',array('session'=>$session_id,'user_id'=>$user_id,'property_id'=>$property_id,'description'=>$description));
    }
    
    
    function add_to_crm_requirements($user_id,$postData,$searchName){
    	$client = create_connection();
    	$session_id = login_favista_crm($client);
    	$user_search_array = array();
    	$cityArr = array();
    	$locationIdArr = array();
    	$amenityArr = array();
    	$search_array = unserialize($postData);
    	
    	$log->LogError("data is ".$postData);
    	
    	$search_array = $search_array['data']['SearchForm'];
    	 
    	$columnMapping['category'] = 'category';
    	$columnMapping['property_class_id'] = 'property_type';
    	$columnMapping['property_on'] = 'req_type';
    	$columnMapping['super_area_min'] = 'min_super_area';
    	$columnMapping['super_area_max'] = 'max_super_area';
    	$columnMapping['city_id'] = 'city_string';
    	$columnMapping['builtup_area_min'] = 'min_builtup_area';
    	$columnMapping['builtup_area_max'] = 'max_builtup_area';
    	$columnMapping['bathrooms_min'] = 'min_bath';
    	$columnMapping['bathrooms_max'] = 'max_bath';
    	$columnMapping['balconies_min'] = 'min_balcony';
    	$columnMapping['balconies_max'] = 'max_balcony';
    	$columnMapping['possession_date_min'] = 'possess_from';
    	$columnMapping['possession_date_max'] = 'possess_to';
    	$columnMapping['property_ownership'] = 'ownership';
    	$columnMapping['furnishing'] = 'furnish_state';
    	$columnMapping['facing'] = 'main_entrance_facing';
    	 
    	$columnMapping['age_of_construction'] = 'age_construction';
    	$columnMapping['publishing_date'] = 'to_show_postings_in';
    	$columnMapping['servant_rooms'] = 'servant_room';
    	$columnMapping['is_sold'] = 'show_recently_sold';
    	$columnMapping['floor_no'] = 'floors_limit';
    	 
    	 
    	if(!empty($search_array) && sizeof($search_array) > 0){
    		foreach($search_array as $key=>$values){
    			if($key == 'city_id'){
    				$cityArr = $values;
    			}
    			if($key == 'location_id'){
    				$locationIdArr = $values;
    			}
    			if($key == 'amenity_name'){
    				$amenityArr = $values;
    			}
    			$f_name = $columnMapping[$key];
    			if(!isset($f_name) || empty($f_name)){
    				$f_name = $key;
    			}
    			 
    			if($f_name == 'property_on'){
    				$val = ($values == "1")?"Rent":"Buy";
    			}else{
    				$val = $values;
    			}
    			 
    			if($f_name == 'location_id' || $f_name == 'amenity_name'){
    				continue;
    			}
    			$name_value_pair['name'] = $f_name;
    			$name_value_pair['value'] = $val;
    			 
    			$user_search_array[] = $name_value_pair;
    			 
    			 
    		}
    		
    
    		$name_value_pair['name'] = 'location_id';
    		if(!empty($locationIdArr) && sizeof($locationIdArr) > 0){
    			$name_value_pair['value'] = implode(",", $locationIdArr);
    			$user_search_array[] = $name_value_pair;
    		}
    
    		$name_value_pair['name'] = 'amenities';
    		if(!empty($amenityArr) && sizeof($amenityArr) > 0){
    			$name_value_pair['value'] = implode(",", $amenityArr);
    			$user_search_array[] = $name_value_pair;
    		}
    		 
    	}
        	
    	$result = $client->call('create_requirement_against_search',array('session'=>$session_id,'user_id'=>$user_id, 'search_name'=>$searchName,'user_session_search'=>$user_search_array));

    	
    }
    
    function add_to_crm_interest($client, $session_id,$user_id,$property_id,$controller){

    	$query_search = "select user_session_id, field_name, field_value from fv_session_search_fields 
    	where fv_session_search_fields.user_session_id = (select max(fv_user_sessions.id) from fv_user_sessions where action='search' and user_id = '".$user_id."')";
    	$search_array = $controller->UserSession->query($query_search,"master");
    	$user_search_array = array();
    	$cityArr = array();
    	$locationIdArr = array();
    	$amenityArr = array();
    	
    	$columnMapping['category'] = 'category';
    	$columnMapping['property_class_id'] = 'property_type';
    	$columnMapping['property_on'] = 'req_type';
    	$columnMapping['super_area_min'] = 'min_super_area';
    	$columnMapping['super_area_max'] = 'max_super_area';
    	$columnMapping['builtup_area_min'] = 'min_builtup_area';
    	$columnMapping['builtup_area_max'] = 'max_builtup_area';
    	$columnMapping['bathrooms_min'] = 'min_bath';
    	$columnMapping['bathrooms_min'] = 'max_bath';
    	$columnMapping['balconies_min'] = 'min_balcony';
    	$columnMapping['balconies_max'] = 'max_balcony';
    	$columnMapping['possession_date_min'] = 'possess_from';
    	$columnMapping['possession_date_max'] = 'possess_to';
    	$columnMapping['property_ownership'] = 'ownership';
    	$columnMapping['furnishing'] = 'furnish_state';
    	$columnMapping['facing'] = 'main_entrance_facing';
    	
    	$columnMapping['age_of_construction'] = 'age_construction';
    	$columnMapping['publishing_date'] = 'to_show_postings_in';
    	$columnMapping['servant_rooms'] = 'servant_room';
    	$columnMapping['is_sold'] = 'show_recently_sold';
    	$columnMapping['floor_no'] = 'floors_limit';
    	
    	
    	if(!empty($search_array) && sizeof($search_array) > 0){
    		foreach($search_array as $key=>$values){
    			if($values['fv_session_search_fields']['field_name'] == 'city_id'){
    				$cityArr[] = $values['fv_session_search_fields']['field_value'];
    			}
    			if($values['fv_session_search_fields']['field_name'] == 'location_id'){
    				$locationIdArr[] = $values['fv_session_search_fields']['field_value'];
    			}
    			if($values['fv_session_search_fields']['field_name'] == 'amenity_name'){
    				$amenityArr[] = $values['fv_session_search_fields']['field_value'];
    			}
    			$f_name = $columnMapping[$values['fv_session_search_fields']['field_name']];
    			if(!isset($f_name)){
    				$f_name = $values['fv_session_search_fields']['field_name'];
    			}
    			
    			if($f_name == 'property_on'){
    				$val = ($values['fv_session_search_fields']['field_value'] == "1")?"Rent":"Buy";
    			}else{
    				$val = $values['fv_session_search_fields']['field_value'];
    			}
    			
    			if($f_name == 'city_id' || $f_name == 'location_id' || $f_name == 'amenity_name'){
    				continue;
    			}
    			$name_value_pair['name'] = $f_name;
    			$name_value_pair['value'] = $val;
    			
    			$user_search_array[] = $name_value_pair;
    			
    			
    		}
    		$name_value_pair['name'] = 'city_string';
    		if(!empty($cityArr) && sizeof($cityArr) > 0){
	    		$name_value_pair['value'] = implode(",", $cityArr);
	    		$user_search_array[] = $name_value_pair;
    		}
    		
    		$name_value_pair['name'] = 'location_id';
    		if(!empty($locationIdArr) && sizeof($locationIdArr) > 0){
	    		$name_value_pair['value'] = implode(",", $locationIdArr);
	    		$user_search_array[] = $name_value_pair;
    		}
    		
    		$name_value_pair['name'] = 'amenities';
    		if(!empty($amenityArr) && sizeof($amenityArr) > 0){
	    		$name_value_pair['value'] = implode(",", $amenityArr);
	    		$user_search_array[] = $name_value_pair;
    		}
    	
    	}
    	
    	$result = $client->call('create_interest_against_favorite',array('session'=>$session_id,'user_id'=>$user_id, 'property_id'=>$property_id, 'user_session_search'=>$user_search_array));
    }
	
	
	function insertLead($columnArr,$user_id,$project_id=-1,$referer_url,$landing_url,$leadpage_url){
    	
    	$client = create_connection();
        
    	$session_id = login_favista_crm($client);
    	$name_value_list = array();
    	foreach($columnArr as $col=>$val){
    		$name_value_pair['name'] = $col;
    		$name_value_pair['value'] = $val; 
    		$name_value_list[] = $name_value_pair;
    	}

    	insert_lead($client, $session_id, $name_value_list,$user_id,$project_id,$referer_url,$landing_url,$leadpage_url);
    
    }
	function insert_lead($client, $session_id,$name_value_list,$user_id,$project_id=-1,$referer_url,$landing_url,$leadpage_url){
        array_push($name_value_list,array("name"=>'referer_url',"value"=>$referer_url));
        array_push($name_value_list,array("name"=>'landing_url',"value"=>$landing_url));
        array_push($name_value_list,array("name"=>'leadpage_url',"value"=>$leadpage_url));
        //$name_value_list['landing_url']=$landing_url;
        //$name_value_list['leadpage_url']=$leadpage_url;
        echo  '<pre>';
         print_r($name_value_list);
        // die;
    	$result = $client->call('set_entry',array('session'=>$session_id,'module_name'=>'Leads', 'name_value_list'=>$name_value_list, 'project_id'=>$project_id));
    	print_r($result);
        $id = $result['id'];
    	$result = $client->call('update_user_lead',array('session'=>$session_id,'user_id'=>$user_id,'lead_id'=>$id));
    }
	
    ?>