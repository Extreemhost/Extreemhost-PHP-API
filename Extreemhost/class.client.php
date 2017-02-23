<?php
	/*
	* Extreemhost Webservice client
	* File: class.client.php
	* Author: Fabrice Delahaij
	* Website: https://extreemhost.nl 
	*/

	session_start();

	class client
	{
		public function Domeinregister($domein) {
			$sqlString = '';
			foreach($domein as $veld => $waarde) {
				$sqlString .= "&$veld=$waarde";
			}			
			$content='action=register&veld=domeinen'.$sqlString;	
			return $this->xmlclient($content);
		}

		public function Serverregister($id) {
			if($domein['id'] == '') return "Domein id mag niet leeg zijn!";
			$sqlString = '';
			foreach($id as $veld => $waarde) {
				$sqlString .= "&$veld=$waarde";
			}			
			$content='action=register&veld=servers'.$sqlString;
			return $this->xmlclient($content);
		}

		public function Domeincancel($domein, $tld) {
			$content='action=cancel&veld=domeinen&domein='.$domein.'&tld='.$tld;
			return $this->xmlclient($content);
		}

	
		public function servercancel($id) {
			$content='action=cancel&veld=servers&id='.$id;
			return $this->xmlclient($content);
		}


		public function Domeinupdate($domein) {
			$sqlString = '';
			foreach($domein as $veld => $waarde) {
				$sqlString .= "&$veld=$waarde";
			}			
			$content='action=update&veld=domeinen'.$sqlString;
			return $this->xmlclient($content);
		}

		public function Serverupdate($domein)
		{
			if($domein['id'] == '') return "Server id mag niet leeg zijn!";
	
			$sqlString = '';
				foreach($id as $veld => $waarde){
					$sqlString .= "&$veld=$waarde";
			}			
			$content='action=update&veld=servers'.$sqlString;
			return $this->xmlclient($content);
		}


		public function getDomainNames() {
			$content='action=getDomainNames';
			return $this->xmlclient($content);
		}

		public function getDomainName($domein, $tld) {
			$content='action=getDomainName&domein='.$domein.'&tld='.$tld;
			return $this->xmlclient($content);
		}
		public function getAuthCode($domein, $tld)
		{
			$content='action=getAuthCode&domein='.$domein.'&tld='.$tld;
			return $this->xmlclient($content);
		}

		public function getAllTldInfos() {
			$content='action=getAllTldInfos';
			return $this->xmlclient($content);
		}

		public function getIrcNames() {
			$content='action=getIrcNames';
			return $this->xmlclient($content);
		}

		public function getColoNames() {
			$content='action=getColoNames';
			return $this->xmlclient($content);
		}

		public function setNameservers($ns1, $ns2, $ns3, $ns1_ipv4, $ns2_ipv4 ,$ns3_ipv4, $ns1_ipv6, $ns2_ipv6, $ns3_ipv6) {
			$content='action=setNameservers&ns1='.$ns1.'&ns2='.$ns2.'&ns3='.$ns3.'&ns1_ipv4='.$ns1_ipv4.'&ns2_ipv4='.$ns2_ipv4.'&ns3_ipv4='.$ns3_ipv4.'&ns1_ipv6='.$ns1_ipv6.'&ns2_ipv6='.$ns2_ipv6.'&ns3_ipv6='.$ns3_ipv6;
			return $this->xmlclient($content);
		}

		public function getAccountBalance() {
			$content='action=balance';
			return $this->xmlclient($content);
		}

		public function getIP() {
			$content='action=GetIP';
			return $this->xmlclient($content);
		}
		public function checkAvailability($domain, $tld) {
			$content='action=checkAvailability&domain='.$domain.'&tld='.$tld;
			return $this->xmlclient($content);
		}
		public function Authentication($username, $password) {
			$content='action=authentication&username='.$username.'&auth='.$password;
			return $this->xmlclient($content);
		}

		public function ResendValidationEmail($email) {
			$content = 'action=ResendValidationEmail&email='.$email;
			return $this->xmlclient($content);
		}

		private function xmlclient($content) {
			$settings = array(
				'url'	=> 'extreemhost.nl',
				'file'	=> '/webservice/',
				'ssl'	=> 'ssl://extreemhost.nl'
			);
			$content_length = strlen($content);
			//
			$headers .= 'POST '.$settings['file'].' HTTP/1.0 Host: '.$settings['url'].' Content-type: text/html Content-length: '.$content_length.' ' . PHP_EOL;
			$headers = 'POST '.$settings['file'].' HTTP/1.1'."\r\n". 'Host: '.$settings['url']."\r\n".'Content-Type: application/x-www-form-urlencoded'."\r\n".'Content-Length: '.strlen($content)."\r\n";
	 		//
			if($_SESSION['session_id'] != '')
			$headers .= 'Cookie: PHPSESSID='.$_SESSION['session_id'].'' . PHP_EOL;
			$headers .= 'Set-Cookie: PHPSESSID="'.$_SESSION['session_id'].'' . PHP_EOL;
			$headers .= 'Connection: keep-alive' . "\r\n\r\n";
			$fp = fsockopen($settings['ssl'], 443);
			//
			if (!$fp) return false;
			@fputs($fp, $headers . $content);
			$ret = '';
			while(!feof($fp)) {
				$ret .= fgets($fp, 1024);
			}
			fclose($fp);
			$res = explode('Extreemhost 3.0', $ret); 
			if($_SESSION['session_id'] == '') $this->searchsessionid($res[0]);
			return $res[1];
		}

		private function searchsessionid($content) {
			$ervoor = explode('Set-Cookie: PHPSESSID=', $content); 
			$result = explode('; ', $ervoor[1]);
			if($result[0] != '')
			$_SESSION['session_id'] = $result[0];
		}
	}
?>
