<?php
namespace TerrariZ\Server;

class Server {

public $serverpass = "test"

public __construct($serverpass){
$this->serverPassword = $serverpass;
}

public function getServerPassword(){
return $this->serverPassword;
}

protected function setServerPassword($new): bool {
	
if ($new == $this->serverPassword){
throw new exception("Cannot Set Password: The Password given is the same as the current Password");
return false;
}
else {
$this->serverPassword = $new;
return true;
}

}

  private function readPacket($client) {
        $lengthBytes = socket_read($client, 2);
        if (!$lengthBytes) return null;

        $length = unpack('v', $lengthBytes)[1];
        $packet = socket_read($client, $length);
        if (!$packet) return null;

        return [
            'id' => ord($packet[0]),
            'data' => substr($packet, 1)
        ];
    }
} // class Bracket

?>
