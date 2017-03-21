<?php 

require_once 'class-crest-request.php';

class CREST_Request_People extends CREST_Request {
	
	public function get_people(){
		
		$this->authenticate();
		
		$cookie = explode( '=' , $this->token );
		
		$soap_client = new SoapClient( 'http://ven.solows.realogyfg.com/V1.3/BrokerageRW/OfficeStaffService.Svc?wsdl', array('trace' => 1) );
		
		$soap_client->__setCookie ( $cookie[0], $cookie[1] );
		
		$params = new stdClass();
		$params->SearchCriteriaPerson = new stdClass();
		$params->SearchCriteriaPerson->City = 'Chicago';
		$params->SearchCriteriaPerson->StateName = 'Illinois';
		
		$response = $soap_client->OfficeStaffSearch( $params );
		
		if ( isset( $response->SearchResultPersons->SearchResultPerson ) && is_array( $response->SearchResultPersons->SearchResultPerson ) ){
			
			return $response->SearchResultPersons->SearchResultPerson;
			
		} // end if
		
		return array();
		
	} // end get_people
	
	
	public function get_person_detail( $pid ){
		

		
		$this->authenticate();
		
		$cookie = explode( '=' , $this->token );
		
		$soap_client = new SoapClient( 'http://ven.solows.realogyfg.com/V1.3/BrokerageRW/OfficeStaffService.Svc?wsdl', array('trace' => 1) );
		//$soap_client = new DummySoapClient( 'http://ven.solows.realogyfg.com/V1.3/BrokerageRW/OfficeStaffService.Svc?wsdl', array('trace' => 1) );
		
		$soap_client->__setCookie ( $cookie[0], $cookie[1] );
		
		//var_dump( $soap_client->__getFunctions () );
		
		$params = new stdClass();
		
		$params->PersonIds = new stdClass();
		$params->PersonIds->guid = $pid;
		
		$response = $soap_client->PersonDetailGet( $params );
		
		return $response;
		
	} // end get_person_detail
	
} // end CREST_Request_People


class DummySoapClient extends SoapClient {
    function __construct($wsdl, $options) {
        parent::__construct($wsdl, $options);
    }
    function __doRequest($request, $location, $action, $version, $one_way = 0) {
		var_dump( $request );
        return $request;
    }
}