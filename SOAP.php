<?php
$soap_url = "http://".$_SERVER['HTTP_HOST']."/admin/projekt/SOAP.php";
$wsdl_funktionen = array(
  "getMe" => array(
    "parameter" => array(
    ),
    "return" => "array",
  ),
  "getRechte" => array(
    "parameter" => array(
      "modul_name" => "string",
    ),
    "return" => "array",
  ),
);

if(isset($_GET["wsdl"])){
  echo "<?xml version ='1.0' encoding ='UTF-8' ?>
  <definitions name='SelfService'
    targetNamespace='http://".$_SERVER['HTTP_HOST']."/PROJEKT_SOAP'
    xmlns:tns='http://".$_SERVER['HTTP_HOST']."/PROJEKT_SOAP'
    xmlns:soap='http://schemas.xmlsoap.org/wsdl/soap/'
    xmlns:xsd='http://www.w3.org/2001/XMLSchema'
    xmlns:soapenc='http://schemas.xmlsoap.org/soap/encoding/'
    xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'
    xmlns='http://schemas.xmlsoap.org/wsdl/'>";

  foreach($wsdl_funktionen as $fkt => $val){
    echo "<message name='".$fkt."Request'>";
    foreach($val["parameter"] as $name => $type) echo "<part name='$name' type='xsd:$type'/>";
    echo "</message>";

    echo "<message name='".$fkt."Response'>
            <part name='Result' type='xsd:array'/>
          </message>";

  
    echo "<portType name='".$fkt."PortType'>
            <operation name='".$fkt."'>
              <input message='tns:".$fkt."Request'/>
              <output message='tns:".$fkt."Response'/>
            </operation>
          </portType>";
  
    echo "<binding name='".$fkt."Binding' type='tns:".$fkt."PortType'>
            <soap:binding style='rpc' transport='http://schemas.xmlsoap.org/soap/http'/>
            <operation name='".$fkt."'>
              <soap:operation soapAction='urn:PROJEKT_SOAP#".$fkt."'/>
              <input>
                <soap:body use='encoded' namespace='urn:PROJEKT_SOAP' encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'/>
              </input>
              <output>
                <soap:body use='encoded' namespace='urn:PROJEKT_SOAP' encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'/>
              </output>
            </operation>
          </binding>";
  
    echo "<service name='".$fkt."Service'>
            <port name='".$fkt."Port' binding='".$fkt."Binding'>
              <soap:address location='$soap_url'/>
            </port>
          </service>";
  }
  echo "</definitions>";
}else{
  include_once("../../global.php");
  
  $logged_in = false;
  $user_id = 0;
  
  if(isset($_SERVER['PHP_AUTH_USER'])){
    $id = $DB->query_one("SELECT id FROM user WHERE nick = '".mysql_real_escape_string($_SERVER['PHP_AUTH_USER'])."' AND passwort = '".md5($_SERVER['PHP_AUTH_PW'])."' LIMIT 1");
    if($id){
      $user_id = $id;
      $logged_in = true;
    }
  }
  
  if(!$logged_in){
    header('WWW-Authenticate: Basic realm="maxlan Projekt SOAP"');
    header('HTTP/1.0 401 Unauthorized');
    echo "Don't Panic!";
    exit;
  }else{
    ####
    # Gibt Infos zu dem angemeldeten User zurueck
    ####
    function getMe(){
      global $DB, $user_id;
      return $DB->query_first("SELECT nick, vorname, nachname FROM user WHERE id = '".$user_id."' LIMIT 1");
    }
      
    ####
    # Gibt die Rechte des angemeldeten Users fuer das angegebene Modul zurueck
    ####
    function getRechte($modul_name){
      global $DB, $user_id;

      $rechte = array();
      $query = $DB->query("SELECT * FROM project_rights_rights WHERE bereich = '".$modul_name."'");
      while($row = $DB->fetch_array($query)){
        $query2 = $DB->query("SELECT * FROM project_rights_user_rights WHERE user_id = '".$user_id."' AND right_id = '".$row["id"]."' LIMIT 1");
        if($DB->num_rows($query2) < 1) $rechte[$row["recht"]] = false;
        else $rechte[$row["recht"]] = true;
      }
      return $rechte;
    }

    ## Funktionen registrieren
    $server = new SoapServer($soap_url."?wsdl");
    $server->addFunction("getMe");
    $server->addFunction("getRechte");
    $server->handle();
  }
}
?>
