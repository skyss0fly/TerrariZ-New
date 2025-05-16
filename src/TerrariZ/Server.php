<?php
namespace TerrariZ;

class Server {
    private string $serverPassword;

    public function __construct(string $serverPassword = "abc") {
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
					echo "Checking for packet ID: " . $packet['id'] . PHP_EOL;
                    switch ($packet['id']) {
						

                        case 1:
                            echo "Received packet ID 1\n";
                            usleep(100000);
                            echo "Sending Password Packet (ID 37) to client\n";
                            $this->writePacket($clientSocket, 37, chr(1));
                            
                        case 38:
						if (!is_resource($clientSocket)) {
    echo "Client socket is invalid or disconnected!\n";
    return;
}

                            echo "Received Password\n";
							$receivedPacket = $this->readPacket($clientSocket);
if (!$receivedPacket) {
    echo "Failed to read packet 38\n";
    return;
}
var_dump($receivedPacket);
$receivedPassword = ltrim($receivedPacket['data'], "\x03");

$expectedPassword = $this->getServerPassword(); // Get stored password
var_dump(bin2hex($receivedPassword), bin2hex($expectedPassword));


if ($receivedPassword !== $expectedPassword) {
    $this->writePacket($clientSocket, 2, chr(1)); // Invalid password response
    echo "Invalid Password \n";
} else {
    echo "Correct Password \n";
	 echo "sending Player Appearance Packe \n";
// send player packet for 3 and then also player appearance packet
	$this->sendPlayerAppearance($clientSocket, [
    'slot' => 0,
    'hairStyle' => 1,
    'gender' => 1,
    'hairColor' => ['r' => 255, 'g' => 0, 'b' => 0],
    'skinColor' => ['r' => 255, 'g' => 224, 'b' => 189],
    'eyeColor' => ['r' => 0, 'g' => 128, 'b' => 255],
    'shirtColor' => ['r' => 0, 'g' => 255, 'b' => 0],
    'undershirtColor' => ['r' => 0, 'g' => 200, 'b' => 200],
    'pantsColor' => ['r' => 50, 'g' => 50, 'b' => 50],
    'shoeColor' => ['r' => 80, 'g' => 40, 'b' => 0],
    'difficulty' => 0,
    'playerName' => 'test'
]);
}


                    }
                }
            }
        }
    }
	private function sendPlayerAppearance($clientSocket, array $appearanceData) {
    $payload = '';

    $payload .= chr($appearanceData['slot']);                  // Offset 0
    $payload .= chr($appearanceData['hairStyle']);             // Offset 1
    $payload .= chr($appearanceData['gender']);                // Offset 2
    $payload .= $this->packColor($appearanceData['hairColor']);         // Offset 3
    $payload .= $this->packColor($appearanceData['skinColor']);         // Offset 6
    $payload .= $this->packColor($appearanceData['eyeColor']);          // Offset 9
    $payload .= $this->packColor($appearanceData['shirtColor']);        // Offset 12
    $payload .= $this->packColor($appearanceData['undershirtColor']);   // Offset 15
    $payload .= $this->packColor($appearanceData['pantsColor']);        // Offset 18
    $payload .= $this->packColor($appearanceData['shoeColor']);         // Offset 21
    $payload .= chr($appearanceData['difficulty']);            // Offset 24
    $payload .= $this->packString($appearanceData['playerName']);       // Offset 25+

    $this->writePacket($clientSocket, 4, $payload);
}

private function packColor(array $color): string {
    return chr($color['r']) . chr($color['g']) . chr($color['b']);
}

private function packString(string $str): string {
    return chr(strlen($str)) . $str;
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
    if (!$lengthBytes) {
        echo "No data received from client!\n";
        return null;
    }

    $length = unpack('v', $lengthBytes)[1];
    $packet = fread($client, $length);

    if (!$packet) {
        echo "Packet read failed!\n";
        return null;
    }

    var_dump($packet);

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

