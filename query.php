<?php

	require 'vendor/autoload.php';

	$client = new Elasticsearch\Client();

	if(isset($_GET['searchText']) && trim($_GET['searchText']) != ''){
		$searchTxt=  $_GET['searchText'];
		$params['index'] = 'jdbc';
		$params['type']  = 'jdbc';
		$filter_string='';
		
		
/**
		Keyword Search
*/
		if($searchTxt!== '*'){
			$string_query_match='{
				    "match" : {
				        "information" : {
				            "query" : "'.$searchTxt.'",
				            "minimum_should_match": "30%"
				        }
				    }
				}';
			$query_format = '
				"query" : 
					'.$string_query_match.'	
					
			';				
			}else{
				$query_format='';	
			}


/**
		Dick Size Equal, Range, Less than, Greater than. 
*/
		$sep_com='';
		if(!empty($query_format)){
			$sep_com=',';
		}
		if(isset($_GET['d_typ']) && trim($_GET['d_typ']) != '' ){
			$d_typ=$_GET['d_typ'];
			switch ($d_typ) {
				case 'equal':
					if(isset($_GET['start']) && trim($_GET['start']) != '' ){
						$start=$_GET['start'];
						$filter_string='
							"filter" : {
						        "term" : {
						            "dick_size" : '.$start.'
						        }
						    }'.$sep_com.'
						';
					}
					break;
				case 'less':
					if(isset($_GET['start']) && trim($_GET['start']) != '' ){
						$start=$_GET['start'];
						$filter_string='"filter" : {
								"range" : {
									"dick_size" : {
										"lt": '.$start.'
									}
								}
							}'.$sep_com.'';
					}

					break;
				case 'greater':
					if(isset($_GET['start']) && trim($_GET['start']) != '' ){
						$start=$_GET['start'];
						$filter["range"]["dick_size"]["gte"]= $start;
						$filter_string='"filter" : {
								"range" : {
									"dick_size" : {
										"gt": '.$start.'
									}
								}
							}'.$sep_com;

					}
					break;
				case 'range':
					if(isset($_GET['start']) && trim($_GET['start']) != '' && isset($_GET['end']) && trim($_GET['end']) != '' ){
						$start=$_GET['start'];
						$end=$_GET['end'];
				 		$filter_string='"filter" : {
					            "range" : {
					                "dick_size" : {
					                    "gte": '.$start.',
										"lte": '.$end.'					                    
					                }
					            }
					        }'.$sep_com;
					}
					break;
				default:
					break;
			}
		}

		
		
		$overall_query = '{  "from" : 0, "size" : 260,
			"query" : {
				"filtered" : {
				    '.$filter_string.'
				    '.$query_format.'
				}	
			}	
		}';
		$params['body']  = $overall_query;
		$results = $client->search($params);
		$dataset=$results['hits']['hits'];
		
		$data_json=array();
		$data_json_1=array();
		$i=0;
		foreach ($dataset as $key => $value) 
		{
			/*
			$data_test['superhero']=$dataset[$key]['_source']['superhero']; 
			$data_test['information']=$dataset[$key]['_source']['information']; 
			$data_test['dick_size']=$dataset[$key]['_source']['dick_size'];
			if (in_array($data_test, $data_json_2)) 
			{
			    continue;
			}
			$data_json_2[]=$data_test;
			*/
			
			$data_json['superhero']=$dataset[$key]['_source']['superhero']; 
			$data_json['information']=$dataset[$key]['_source']['information']; 
			$data_json['dick_size']=$dataset[$key]['_source']['dick_size'];
			$data_json['score']=$dataset[$key]['_score'];
			if (in_array($data_json, $data_json_1)) 
			{
			    continue;
			}
			$data_json_1[]=$data_json;
		}
		echo json_encode($data_json_1, 128);
	}


?>