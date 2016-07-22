<?php

/*
 * @file
 * Contains socket to the Asterisk server and
 * parser of Command action with 'sip show peers' parameter.
 */
 
//Defimes timeout of 3 seconds 
$timeout = 3;
//Connet to asterisk server
//Replace ip of your Asterisk server with 192.168.43.10
$sock = fsockopen("192.168.43.10", 5038, $errno, $errstr, $timeout);
//Send Login action to Asterisk Manage Interface.
//Please replace your credentials defined in manager.conf or manager_custom.conf 
fputs($sock, "Action: Login\r\n");
fputs($sock, "UserName: iman\r\n");
fputs($sock, "Secret: 123456\r\n\r\n");

fputs($sock, "Action: Command\r\n");
fputs($sock, "Command: sip show peers\r\n\r\n");

//Parse output of 'Command: sip show peers'.
$line = "";	
while ($line != "--END COMMAND--\r\n\r\n") {    	
  if(strstr(fgets($sock), "Name/username")) {
    $peers = array();
    while(true) {
      $line = fgets($sock);
	  if(strstr($line, "sip peers")) {
	    break;
	  }
	  $peer = split("[ ]+", $line);
	  $keyed_peer = array(
	    "name" => $peer[0],
		"status" => $peer[6],
	  );				
	  $peers[] = $keyed_peer;				
	}//end inner while			
	print json_encode($peers);
  }//end if
  if(strstr($line, "sip peers")) {
	break;
  }
}//end outer while
fclose($sock);
?>
