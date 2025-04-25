<?php

 
class HP_DGA
{
    
	// start register
	public static function getRegister($file ='',$attach_path ='' ,$type ='' ,$certificate_no = ''){ // ,$attachment = ''

		try {
                     $config = HP::getConfig();
                     
                if($type == 3){ // ห้องปฏิบัติการ
                    $TemplateID =   $config->digital_signing_lab;
                }else if($type == 2){ // หน่วยตรวจสอบ
                    $TemplateID =   $config->digital_signing_ib;
                }else if($type == 1){ // ห้องหน่วยรับรอง
                    $TemplateID =   $config->digital_signing_cb;
                }
                    $url            =  $config->digital_signing_api_document_id;

           

		      $byteArray = file_get_contents($file);
 
         
		    if (!isset($byteArray) || empty($byteArray)) {
			  throw new Exception("File Size Zero.");
		    } else { 	
			  $apiurl 	= $url."PdfA=true&Timestamp=true&TemplateID=".$TemplateID;
       
			  $token 	=  self::getToken($config->digital_signing_consumer_key, $config->digital_signing_consumer_secret, $config->digital_signing_agent_id ,$config->digital_signing_api_token);
             
			  $postArray = array(
				'Content'   	=> $byteArray,
				'Page' 	        => '1',
                'Left' 	        => '50',
			    'Bottom' 	    => '75'
			    );

              $i = 1;
              start:
              if($i <= 3){
                
                        $json 	                        = self::callServicePUT($apiurl, $config->digital_signing_consumer_key, $token, $postArray);
                        $data 	                        = json_decode($json);
                        $object 					    = (object)[]; 
                        $object->DocumentID 			= $data->DocumentID;
                       
                        $organization 					= self::getOrganization($data->DocumentID, $config->digital_signing_consumer_key ,$token);
                        
                    if(!empty($organization->SignatureID)){
                        $object->SignatureID 			=  $organization->SignatureID   ;
        
                        $file_name 					    =   self::getDownlaodPDFigned($data->DocumentID , $config->digital_signing_consumer_key, $token,$attach_path,$certificate_no);

                        // dd($file_name);

                        $object->Certificate_newfile 	= !empty($file_name) ? $file_name : null  ;

                        // if($attachment != '' && is_file($attachment)){
                        //         $url_attachment =  $config->digital_signing_api_attachment;
                        //         $post_array = array(
                        //                      'DocumentID'  =>     $object->DocumentID,
                        //                      'Content'     =>      file_get_contents($attachment) 
                        //          );
                        //          self::callServicePUT($url_attachment, $config->digital_signing_consumer_key, $token,$post_array); 
                        // }

                        goto end;

                    }
			   }else{
                    $i ++;
                    goto start;
               }

			   end:
      
			   return  $object;
		    }
		} catch (Exception $ex) {
			  $object 					= (object)[]; 
			  $object->Message 			=  $ex->getMessage();
			  return  $object;
  	  	}
	}
      // end register


    // start ลงลายมือชื่ออิเลกทรอนิกส์แบบองค์กร
	public static function getOrganization($DocumentID,$ConsumerKey,$token){

		// header("Access-Control-Allow-Origin: *");
		// header("Content-Type: application/json; charset=UTF-8");
		// header("Access-Control-Allow-Methods: POST");
		// header("Access-Control-Max-Age: 3600");
		// header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $config           = HP::getConfig();
        $apiurl 		 =  $config->digital_signing_api_esgnatures; 
    
		try {
          
				$postdata = array(
                                    'DocumentID'		=> $DocumentID,
                                    'Signature'		=>  array('Page'=>'1','Left'=>'100','Bottom'=>'80','Width'=>'140','Height'=>'60','Image'=>null)
				     		    );   
 
		 $postdata=json_encode($postdata,true);
		 if (!isset($postdata) || empty($postdata)) {
			  throw new Exception("File Size Zero.");
		  } else {  

			  $json 			=  self::callServicePOST($apiurl, $ConsumerKey, $token,$postdata,null);
     
			  $data 			= json_decode($json);
         
			  $object 			= (object)[]; 
			  $object->SignatureID 	=  $data->SignatureID;
			  return  $object;
 
		    }
		} catch (Exception $ex) {
			$object 					= (object)[]; 
			$object->Message 			=  $ex->getMessage();
			return  $object;
	   }
	}
      // end ลงลายมือชื่ออิเลกทรอนิกส์แบบองค์กร


	// start  API Downlaod PDF Signed
 

	public static function getDownlaodPDFigned($DocumentID,$ConsumerKey,$token,$search_path = '',$certificate_no){
		try {

		    $config = HP::getConfig();
			$api_downlaod_signed 	=  $config->digital_signing_api_downlaod_signed;

			$apiurl 	            = $api_downlaod_signed."DocumentID=".$DocumentID;
    
			// Create a stream
			$opts  = array( 
			  'http'=>array(
			 'method'=>'GET',
			 'header'=> "Consumer-Key: ".$ConsumerKey."\r\n"
					. "Token: ".$token."\r\n"
			  ),
			);
			$context 	= stream_context_create($opts);
			$file 	= file_get_contents($apiurl, false, $context);
    
 

			$certificate_no = str_replace("	", "", $certificate_no);
			$certificate_no = str_replace(" ", "", $certificate_no);
            $certificate_no = str_replace(":", "_", $certificate_no);
			$certificate_no = str_replace(')', '_', $certificate_no);
			$certificate_no = str_replace('/', '_', $certificate_no);
            $certificate_no = str_replace('-', '_', $certificate_no);
			$file_name 	=   $certificate_no.'_'.date('Ymd_hms').'.pdf';
			// put file pdf
		    (file_put_contents($search_path.'/'.$file_name,$file, FILE_APPEND));
            //   Storage::put($search_path.'/'.$file_name, $file);
			return 	$file_name;
 
	 
		} catch (Exception $ex) {
			$object 					= (object)[]; 
			$object->Message 			=  $ex->getMessage();
			return  $object;
	   }
	}
      // end  API Downlaod PDF Signed


    // start  เพิกถอนการใช้งานเอกสาร
	public static function getRevoked($DocumentID,$Reason){
		
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		header("Access-Control-Allow-Methods: POST");
		header("Access-Control-Max-Age: 3600");
		header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $config = HP::getConfig();

		$apiurl 			=  $config->digital_signing_api_revoked;

		$api_token 			=  $config->digital_signing_api_token;
	 
		$AgentID 			=  $config->digital_signing_agent_id;
	
		$SecretKey 			=  $config->digital_signing_consumer_secret;

		$ConsumerKey 		=  $config->digital_signing_consumer_key;

		try {
	 
		    $postdata = array(
						'DocumentID'	=> $DocumentID,
                        'Reason'        => $Reason
				   	  );    
		    $postdata=json_encode($postdata,true);
		    if (!isset($postdata) || empty($postdata)) {
			  throw new Exception("File Size Zero.");
		    } else {  
			  $token 			=  self::getToken($ConsumerKey, $SecretKey, $AgentID ,$api_token);
			  $json 			=  self::callServiceRevokedPOST($apiurl, $ConsumerKey, $token,$postdata);
			  $data 			= json_decode($json);
			  if(!empty($data)){
				$object 		    = (object)[]; 
				$object->Result 	=  $data->Result ;
				return  $object;
			  } 
		    }
		} catch (Exception $ex) {
			$object 			= (object)[]; 
			$object->Message 		=  $ex->getMessage();
			return  $object;
	     }
	}
      // end  เพิกถอนการใช้งานเอกสาร

      public static  function getToken($ConsumerKey, $ConsumerSecret, $AgentID ,$api_token)
      {
          try {
              $ch = curl_init();
              $headers = array();
              $headers[] = 'Content-Type:application/json'; // set content type
              $headers[] = 'Consumer-Key:' . $ConsumerKey; // set consumer key replace %s
              // set request url
              curl_setopt($ch, CURLOPT_URL, $api_token."ConsumerSecret=" . $ConsumerSecret . "&AgentID=" . $AgentID); // set ConsumerSecret and AgentID
              // set header
              curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
              // return header when response
              curl_setopt($ch, CURLOPT_HEADER, true);
  
              // return the response
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
              // send the request and store the response to $data
              $data = curl_exec($ch);
              // get httpcode 
              $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  
              if ($httpcode == 200) { // if response ok
                  // separate header and body
                  $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                  $header = substr($data, 0, $header_size);
                  $body = substr($data, $header_size);
  
                  // convert json to array or object
                  $result = json_decode($body);
  
                  // access to token value
                  $token = $result->Result;
              } else {
                  $token = "No Found Token";
              }
              // end session
  
              return $token;
          } catch (Exception $ex) {
              return $ex->getMessage();
          }
          curl_close($ch);
      }
  
      public static function callServicePUT($URL, $ConsumerKey, $Token, $postArr)
      {
          $ch = curl_init();
          try {
              $headers = array();
              $headers[] = 'Content-Type:multipart/form-data;'; // set content type
              $headers[] = 'Consumer-Key:' . $ConsumerKey; // set consumer key replace %s
              $headers[] = 'Token:' . $Token; // set access token replace %s
              // set request url
              curl_setopt($ch, CURLOPT_URL, $URL); // set CitizenID replace %s
              // set header
              curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
              // return header when response
              curl_setopt($ch, CURLOPT_HEADER, true);
              curl_setopt($ch, CURLOPT_VERBOSE, true);
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              // return the response
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt( $ch, CURLOPT_POSTFIELDS, $postArr);
              curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
              
              
              // send the request and store the response to $data
              $data = curl_exec($ch);
              $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
              if ($httpcode == 200) { // if response ok
                  // separate header and body
                  $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                  $header = substr($data, 0, $header_size);
                  $body = substr($data, $header_size);
              } else {
                  $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                  $header = substr($data, 0, $header_size);
                  $body = substr($data, $header_size);
              }
              // end session
              return $body;
              curl_close($ch);
          } catch (Exception $ex) {
              return $ex->getMessage();
          }
      }


      public static  function callServicePOST($URL, $ConsumerKey, $Token, $postdata, $file)
      {
          $ch = curl_init();
          try {
              $headers = array();
              $headers[] = 'Content-Type:application/json'; // set content type
              $headers[] = 'Consumer-Key:' . $ConsumerKey; // set consumer key replace %s
              $headers[] = 'Token:' . $Token; // set access token replace %s
              // set request url
              curl_setopt($ch, CURLOPT_URL, $URL); // set CitizenID replace %s
              // set header
              curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
              // return header when response
              curl_setopt($ch, CURLOPT_HEADER, true);
              //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
              //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              // return the response
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              //POST Method
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
              // send the request and store the response to $data
              $data = curl_exec($ch);
              //echo $data;
              // get httpcode 
              $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
              //echo $httpcode;exit();
              if ($httpcode == 200) { // if response ok
                  // separate header and body
                  $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                  $header = substr($data, 0, $header_size);
                  $body = substr($data, $header_size);
              } else {
                  $body = "";
              }
              // end session
              return $body;
              curl_close($ch);
          } catch (Exception $ex) {
              return $ex->getMessage();
          }
      }

      public static function callServiceRevokedPOST($URL, $ConsumerKey, $Token, $postdata)
      {
          $ch = curl_init();
          try {
              $headers = array();
              $headers[] = 'Content-Type:application/pdf'; // set content type //Content-Type: multipart/form-data
              $headers[] = 'Consumer-Key:' . $ConsumerKey; // set consumer key replace %s
              $headers[] = 'Token:' . $Token; // set access token replace %s
              // set request url
              curl_setopt($ch, CURLOPT_URL, $URL); // set CitizenID replace %s
              // set header
              curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
              // return header when response
              curl_setopt($ch, CURLOPT_HEADER, true);
              curl_setopt($ch, CURLOPT_VERBOSE, true);
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              // return the response
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              //POST Method
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
              //curl_setopt($ch, CURLOPT_FILE, $file);
              
              // send the request and store the response to $data
              $data = curl_exec($ch);
              $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
              if ($httpcode == 200) { // if response ok
                  // separate header and body
                  $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                  $header = substr($data, 0, $header_size);
                  $body = substr($data, $header_size);
              } else {
                  $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                  $header = substr($data, 0, $header_size);
                  $body = substr($data, $header_size);
              }
              // end session
              return $body;
              curl_close($ch);
          } catch (Exception $ex) {
              return $ex->getMessage();
          }
      }


}
