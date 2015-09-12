<?php
session_start();
#
class client
{
	# Register
	public function DomainRegister($domain)
	{
		$sqlString = '';
		foreach($domain as $veld => $waarde)
		{
			$sqlString .= "&".$veld."=".$waarde;
		}			
		$content='action=register&veld=domeinen'.$sqlString;
		return $this->xmlclient($content);
	}
	# Cancel
	public function Domaincancel($domain, $tld)
	{
		$content='action=cancel&veld=domeinen&domein='.$domain.'&tld='.$tld;
		return $this->xmlclient($content);
	}
	# Update
	public function Domainupdate($domain)
	{
		$sqlString = '';
		foreach($domain as $veld => $waarde)
		{
			$sqlString .= "&".$veld."=".$waarde;
		}			
		$content='action=update&veld=domeinen'.$sqlString;
		return $this->xmlclient($content);
	}
	# Login
	public function login($username, $password) {
		$content = 'action=login&username='.$username.'&password='.$password;
		return $this->xmlclient($content);
	}
	# Secure code could be create at: https://hcp.extreemhost.nl/klantenpaneel/account/api
	public function securecode($securecode) {
		$content = 'action=setsecurecode&securecode='.$securecode;
		return $this->xmlclient($content);
	}
	# Connection with PHP/XML/SSL
	private function xmlclient($content) {
		$api['website']	= 'extreemhost.nl';
		$api['bestand']	= '/webservice/';
		$content_length = strlen($content);
		# Connection with headers
		$headers .= 'POST '.$api['bestand'].' HTTP/1.0 Host: '.$api['website'].' Content-type: text/html Content-length: '.$content_length.' ' . PHP_EOL;
		$headers = 'POST '.$api['bestand'].' HTTP/1.1'."\r\n". 'Host: '.$api['website']."\r\n".'Content-Type: application/x-www-form-urlencoded'."\r\n".'Content-Length: '.strlen($content)."\r\n";
		# Session ID may not be emtpy
		if($_SESSION['session_id'] != '')
		$headers .= 'Cookie: PHPSESSID='.$_SESSION['session_id'].'' . PHP_EOL;
		$headers .= 'Set-Cookie: PHPSESSID="'.$_SESSION['session_id'].'' . PHP_EOL;
		$headers .= 'Connection: Close' . "\r\n\r\n";
	
		$fp = fsockopen('ssl://extreemhost.nl', 443);
		#
		if (!$fp) return false;
		@fputs($fp, $headers . $content);
		$ret = '';
		while(!feof($fp)) {
			$ret .= fgets($fp, 1024);
		}
		fclose($fp);
		$res = explode('Extreemhost 4.0 BETA', $ret); 
		if($_SESSION['session_id'] == '') $this->findsessionid($res[0]);
		return $res[1];
	}
	private function findsessionid($head)
	{
		$ervoor = explode('Set-Cookie: PHPSESSID=', $head); 
		$result = explode('; ', $ervoor[1]);
		if($result[0] != '')
		$_SESSION['session_id'] = $result[0];
	}
}
?>
