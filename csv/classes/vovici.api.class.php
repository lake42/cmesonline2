<?php
/**
 * Vovici API
 *
 * a PHP class to interact with the Vovici Web Services API
 *
 * @author        Paul Barrick
 * @copyright     Copyright (c) 2012 Infosurv, Inc.
 * @link          http://www.infosurv.com
 */
 
/**
 * voviciAPI
 *
 * Create our new class called "voviciAPI"
 */
class voviciAPI {
  private $api_url = '';
  private $username = '';
  private $password = '';
  private static $instance;
  private $response_set;

  //survery specific options
  private $pid;
  private $surveyStatus;
  private $datamap;
  private $criteria;
  private $fields;


  /**
   * __construct()
   * 
   * @access   public
   * @param    string
   * @param    string
   * @param    string
   * @return   voviciAPI instance
  */
  //Using Singleton Logic to instantiate
  private function __construct(array $config) 
  {
    $this->api_url            = ($config['api_url'])      ? $config['api_url'] : '';
    $this->username           = ($config['username'])     ? $config['username'] : '';
    $this->pid                = ($config['pid'])          ? $config['pid'] : '';
    $this->password           = ($config['password'])     ? $config['password'] :'';
    $this->response_set       = ($config['response_set']) ? $config['response_set'] : '';
  }
  //inject the dependencies (mutators)
  public function setApiUrl($apiUrl){
    $this->api_url = $apiUrl;
  }
  public function setUser($username){
    $this->username = $username;
  }
  public function setPass($password){
    $this->password = $password;
  }
  //Accessors
  public function getUser(){
    return $this->username;
  }
  public function getPass(){
    return $this->password;
  }
  public function getApiUrl(){
    return $this->apiUrl;
  }
  public function get_response_set(){
    return $this->response_set;  
  }
  public function call($method, $settings){
    $method = array(get_class($this), $method);
    return call_user_func($method, $settings);
  }

  //This ensures we only have 1 vovici object in play at all times to work with
  public static function getInstance($config){
    if(empty(self::$instance)) {
      self::$instance = new voviciAPI($config);
    } 
    return self::$instance;
  }
  public function debug_a($array){
    echo '<pre>';
      print_r($array);
    echo '</pre>';
  }
  /**
   * request()
   *
   * Request data from the API
   *
   * @access    public
   * @param   string, array
   * @return    xml
   */
  public function request($func, array $options) {
    ini_set('max_execution_time', 0);
    try {
      $soap = new SoapClient($this->api_url, array('trace'=>1,'cache_wsdl'=>WSDL_CACHE_NONE ));
      $params = array('userName' => $this->username, 'password' => $this->password );
      $soap->Login($params);    
      $response = $soap->$func($options);
    } catch (Exception $e) {
        echo 'Request Error Caught: ' . $e->getMessage();
      die;      
    }
    $r = $func . 'Result';

    if (!$response) {
      return false;
    } else {
      return $response->$r;//->any;
    }
  }
  
  /**
   * XMLToArray()
   *
   * Returns an array of the XML
   *
   * @access    private
   * @param   SimpleXMLElement
   * @return    array
   */
  private function XMLToArray($xml){
      if ($xml instanceof SimpleXMLElement){
        $children = $xml->children();
      $return = null;
    }
      foreach ($children as $element => $value){ 
        if ($value instanceof SimpleXMLElement){
            $values = (array)$value->children();
      
            if (count($values) > 0){
              $return[$element] = $this->XMLToArray($value); 
            } else { 
              if (!isset($return[$element])) { 
                  $return[$element] = (string)$value; 
              } else { 
                  if (!is_array($return[$element])) { 
                    $return[$element] = array($return[$element], (string)$value); 
                  } else { 
                    $return[$element][] = (string)$value; 
                  } 
              } 
            } 
        } 
      }
      if (is_array($return)) { 
        return $return; 
      } else { 
        return $false; 
      } 
  }
  
  /**
   * getCompleteArray()
   *
   * Performs the "GetSurveyDataPaged" request and returns the completed data in a nicely formatted array
   *
   * @access    public
   * @param   string
   * @param   string
   * @return    array
   */
  public function getCompleteArray(array $options) {
    $this->pid      = ($options['projectId'])       ? $options['projectId'] : null;
    $this->criteria = ($options['criteria'])  ? $options['criteria'] : null;
    $this->datamap  = ($options['datamap'])   ? $options['datamap'] : null;
    $this->fields   = ($options['fields'])    ? $options['fields'] : null;

    //Paging the request to circumvent the 1000 result limit
    $responseCount = $this->request('GetResponseCount', array('projectId' => $this->pid, 'completedOnly' => true));
    $last_iteration = $responseCount % 1000;
    if ($last_iteration == 0) {
      $last_iteration = 1000;
    }
    $num_of_iterations = ceil($responseCount / 1000);

    //set inital state
    $prevRecordId = 0;
    $result = array();
    
    while ($num_of_iterations > 0) {
      $recordCount = 1000;
      if ($num_of_iterations == 1) {
        $recordCount = $last_iteration;
      }
      $o = array(
        'projectId'   => $this->pid,
        'completedOnly' => true,
        'recordCount'   => $recordCount,
        'prevRecordId'  => 0
        );

      if ($this->criteria) {
        $o['filterXml'] = $this->criteria;
      }
      if ($this->datamap) {
        $o['dataMapXml'] = $this->datamap;
      }
      $data = $this->request('GetSurveyDataPaged', $o);
      
      $xml = new SimpleXMLElement($data->any);
      unset($data); // for quicker garbage collection
      $xmlAsArray = $xml->NewDataSet;
      unset($xml);
      foreach ($xmlAsArray->Table1 as $record) {
        $test = $this->XMLToArray($record);
        // if $fields is an array, then we want to return each thing in the array
        if (is_array($fields)) {
          $tempArray = array();
          foreach ($fields as $field) {
            $tempArray[$field] = $test[$field];
          }
          $result[] = $tempArray;
        } elseif ($fields) {
          // if $fields is a single entry, then only return that
          $result[] = $test[$fields];
        } else {
          // if $fields is not set, then return the entire record
          $result[] = $test;
        }
        // set the last record
        $prevRecordId = $test['recordid'];
      }
      unset($xmlAsArray); // for quicker garbage collection
      
      // reduce the number of iterations
      $num_of_iterations--;
    }
    
    /*
    * 1) We need to pass the results and the items we want to another method responsible for processing them
    *
    */
    $this->response_set = $result;
    return $this->response_set;
  }
 
   /**
   * getCompleteCSV()
   *
   * Performs the "GetSurveyDataEx" request and returns the completed data as a comma separated list
   *
   * @access    public
   * @param   string
   * @param   string
   * @return    array
   */
  public function getCompleteCSV($pid, $criteria = null) {
    $data = $this->getCompleteArray($pid, $criteria);
    
    if (!$data){
      return false;
    }
    
    $keys = array_keys($data[0]);
    array_unshift($data, $keys);
    
    $columns = $this->getColumnList($pid);
    
    
    
    $fp = fopen('completes.csv', 'w');
    foreach ($data as $record) {
      fputcsv($fp, $record);
    }
    fclose($fp);
  }
  
  public function getColumnList($pid) {
    $data = $this->request('GetColumnList', array('projectId'=>$pid));
    
    if (!$data){
      return false;
    }
    // parse the XML returned by request()
    $xml = new SimpleXMLElement($data->any);
    foreach ($xml as $field) {
      $result[] = (string) $field['id'];
    }
    return $result;
  }
  
  /**
   * getPreloadData()
   *
   * Performs the "GetSurveyDataPaged" request and returns the completed data in a nicely formatted array
   *
   * @access    public
   * @param   string
   * @param   string
   * @return    array
   */
  public function getPreloadData($pid, $field = null) {
    
      $o = array(
        'projectId'   => $pid,
        'recordCount'   => 1000,
        'surveyStatus'  => 'Any',
        'startRecordId' => 0);
      
      //Make the req
      $data = $this->request('GetParticipantDataPaged', $o);
      $xml = new SimpleXMLElement($data->any);
      unset($data); // for quicker garbage collection
      $result = (array) $xml;
      //debug_a($result);

      return $result['Participant'];
  }
public function getProjectInformation ($pid, $field = null) {
  //Request Params
  $o = array(
    'projectId'   => '331014412',
    'surveyStatus'  => 'Any'
  );
    
  //Make the req
  $data = $this->request('GetProjectInformation', $o);
  $info = $data->any;

  $xml = new SimpleXMLElement($data->any);
    
  unset($data); // for quicker garbage collection
  $reflection = new ReflectionClass($xml);
  //echo '<pre>'.$reflection.'</pre>';
  $xml = (array) $xml;
  extract($xml['@attributes']);

  $project_meta = array(
    "name"=> $info,
    "id" => $id,
    "type" => $type,
    "owner" => $owner,
    "publish" => $publish,
    "status" => $status,
    "source" => $source
  );
  return $project_meta; 
}
public function getPublishedQuestionnaire($pid, $field = null){
  //Request Params
  $o = array(
    'projectId'   => '331014412',
    'surveyStatus'  => 'Any'
  );
  $data = $this->request('GetPublishedQuestionnaire', $o);
  $xml = new SimpleXMLElement($data->any);
  unset($data); // for quicker garbage collection
  $result = (array) $xml;
}

} //Closes the class ?>