<?php

class CREST_Request {
	
	private $user = '';
	private $pwd = '';
	protected $token = false;
	protected $token_expires = false;
	
	
	public function __construct( $token = false, $token_expires = false ){
		
		$this->token = $token;
		$this->token_expires = $token_expires;
		
	} // end __construct
	
	
	public function get_token(){ return $this->token; }
	public function get_token_expires() { return $this->token_expires; }
	
	
	
	
	protected function authenticate(){
		
		if ( $this->token ) return true;
		
		$response = $this->get_request_token_response();
		
		if ( $response ){
			
			$token = $this->get_security_token_from_response( $response );
			
			if ( $token ){
				
				$this->token = $token;
				
				return true;
				
			} // end if
			
		} // end if
		
		return false;
		
	} // end authenticate
	
	
	private function get_request_token_response(){
		
		$xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"><soapenv:Header/><soapenv:Body/></soapenv:Envelope>';

		$headers = array(
			'Content-Type: text/xml',
			'Accept-Encoding: gzip,deflate',
			'SOAPAction: "http://rfg.realogy.com/Btt/AuthenticationManagement/Services/2009/05/AuthenticationManagementServiceContract/Authenticate"',
			'Host: ven.auth.ws.realogyfg.com',
			'Connection: Keep-Alive',
			'Cookie: OBBasicAuth=fromDialog; ObSSOCookie=loggedoutcontinue',
			//'Cookie2: $Version=1',
			'Authorization: Basic V1NTSVI4MDAzMTA6UGFzc3dvcmQ1JA==',
		);
		
		$process = curl_init( 'https://ven.auth.ws.realogyfg.com/AuthenticationService/AuthenticationMgmt.svc' );
		curl_setopt($process, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		curl_setopt($process, CURLOPT_POST, 1);
		curl_setopt($process, CURLOPT_POSTFIELDS, $xml);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
		$response = curl_exec($process);
		
		return $response;		
		
	} // end get_request_token_response
	
	
	public function get_security_token_from_response( $response ){
		
		$token = false;
		
		preg_match( '/<a:Token>(.*)<\/a:Token>/', $response, $matches, PREG_OFFSET_CAPTURE );
		
		if ( ! empty( $matches[1][0] ) ){
			
			$token = ( $matches[1][0] );
			
		} // end if
		
		return $token;
		
	} // end get_security_token
	
	
	
} // end CREST_Request