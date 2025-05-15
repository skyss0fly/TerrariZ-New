<?php
namespace TerrariZ;

class Server {
    private string $serverPassword;

    public function __construct(string $serverPassword = "test") {
        $this->serverPassword = $serverPassword;
    }

    public function run() {
       $serverSocket = stream_socket_server("tcp://0.0.0.0:7777", $errno, $errstr);
if (!$serverSocket) {
    die("Stream socket creation failed: $errstr ($errno)");
}

       
      

        echo "Terraria Server Online\n";

        while (true) {
            $clientSocket = stream_socket_accept($serverSocket);
            if ($clientSocket) {
                $packet = $this->readPacket($clientSocket);
                if ($packet) {
                    switch ($packet['id']) {
                        case 1:
                            echo "Received packet ID 1\n";
                            usleep(100000);
                            echo "Sending Password Packet (ID 37) to client\n";
                            $this->writePacket($clientSocket, 37, chr(1));
                            break;
                        case 38:
                            echo "Received Password\n";
							if (!$password === $this->readPacket($clientSocket)){
								$this->writePacket($clientSocket, 2, chr(1));
								echo 'Invalid Password';
								
							}
							else {
								echo 'Correct Password';
							}
							
                            break;
                    }
                }
            }
        }
    }

    public function kickPlayer($clientSocket) {
        socket_close($clientSocket);
    }

    public function stopServer($serverSocket) {
        socket_close($serverSocket);
    }

    public function getServerPassword(): string {
        return $this->serverPassword;
    }

    public function setServerPassword(string $newPassword): bool {
        if ($newPassword === $this->serverPassword) {
            throw new \Exception("Cannot Set Password: The given password is the same as the current password");
        }
        $this->serverPassword = $newPassword;
        return true;
    }

    private function readPacket($client) {
        $lengthBytes = fread($client, 2);
        if (!$lengthBytes) return null;

        $length = unpack('v', $lengthBytes)[1];
        $packet = fread($client, $length);
        if (!$packet) return null;

        return [
            'id' => ord($packet[0]),
            'data' => substr($packet, 1)
        ];
    }

    private function writePacket($client, int $packetId, string $payload = '') {
		/*
		if ($bytesWritten = socket_write($client, $packetId) === false) {
    echo "Socket write failed: " . socket_strerror(socket_last_error($client)) . PHP_EOL;
}
*/
      stream_set_blocking($client,true);
      $length = strlen($payload) + 3;
	  $packet = pack('v',$length) . chr($packetId) . $payload;
	  fwrite($client, $packet);
	  echo "packet";
	  var_dump($packet);
	  echo "payload";
	  var_dump($payload);
	  
	 echo "length";
	 var_dump($length);
	 fflush($client);
}

}
?>

