<?php
namespace TerrariZ;
error_reporting(E_ALL);
ini_set("display_errors", 1);

use TerrariZ\Internal\Packet;
use TerrariZ\Player;

class Server
{
    private string $serverPassword;
    private array $players = []; // Stores Player instances by UID

    function __construct(string $serverPassword = "abc")
    {
        $this->serverPassword = $serverPassword;
    }

    public function run()
    {
        $serverSocket = stream_socket_server(
            "tcp://0.0.0.0:7777",
            $errno,
            $errstr
        );

        if (!$serverSocket) {
            die("Stream socket creation failed: $errstr ($errno)");
        }

        echo "Terraria Server Online\n";

        while (true) {
            $clientSocket = stream_socket_accept($serverSocket);
            if ($clientSocket) {
                $packet = Packet::readPacket($clientSocket);
                if (!$packet) {
                    echo "No packet received, waiting...\n";
                    usleep(50000); // Short delay to prevent CPU overload
                    continue;
                } else {
                    echo "Checking for packet ID: " . $packet["id"] . PHP_EOL;

                    switch ($packet["id"]) {
                        case 1:
                            echo "Received packet ID 1\n";
                            usleep(100000);
                            echo "Sending Password Packet (ID 37) to client\n";
                            Packet::writePacket($clientSocket, 37, chr(1));
							//Packet::writePacket($clientSocket, 3,0);

                        case 38:
                            if (!is_resource($clientSocket)) {
                                echo "Client socket is invalid or disconnected!\n";
                                return;
                            }

                            $receivedPacket = Packet::readPacket($clientSocket);
                            echo "Received Password\n";
                            if (!$receivedPacket) {
                                echo "Failed to read packet 38\n";
                                return;
                            }

                            $receivedPassword = ltrim(
                                $receivedPacket["data"],
                                "\x03"
                            );
                            $expectedPassword = $this->getServerPassword();

                            if ($receivedPassword !== $expectedPassword) {
                                Packet::writePacket($clientSocket, 2, chr(1));
                                echo "Invalid Password \n";
                            } else {
                                echo "Correct Password \n";
                                Packet::writePacket($clientSocket, 3, 0);
                            }

                        case 4:
                            echo "Waiting for Player Info Packet...\n";
                            $playerPacket = Packet::readPacket($clientSocket); // Read NEW packet
                            if (!$playerPacket || $playerPacket["id"] !== 4) {
                                echo "Unexpected packet received instead of Player Info!\n";
                                return;
                            }
                            echo "Player Packet 4 Raw Packet DATA: \n";
                            var_dump($playerPacket);
                            if (!isset($playerPacket["data"])) {
                                echo "Player packet data is missing!\n";
                                return;
                            }
							$playerData = $this->parsePlayerPacket([
							"id" => 4,
							"data" => $playerPacket["data"]
							]);
							$this->addPlayer($clientSocket, $playerData);
                    }
                }
            }
        }
    } // <== Closing bracket was missing before!

    public function kickPlayer($clientSocket)
    {
        socket_close($clientSocket);
    }

    public function stopServer($serverSocket)
    {
        socket_close($serverSocket);
    }

    public function getServerPassword(): string
    {
        return $this->serverPassword;
    }

    public function listPlayers()
    {
        foreach ($this->players as $player) {
            print_r($player->getPlayerInfo());
        }
    }

    public function removePlayer(int $uid)
    {
        if (isset($this->players[$uid])) {
            echo "Player {$this->players[$uid]->name} (UID: $uid) has left the server.\n";
            unset($this->players[$uid]);
        }
    }

    public function getPlayer(int $uid): ?Player
    {
        return $this->players[$uid] ?? null;
    }

    public function addPlayer($clientSocket, array $playerData)
    {
		
        $uid = $playerData["uid"]; // Unique Player ID
        $this->players[$uid] = new Player(
            $uid,
            $playerData["skinVariant"],
            $playerData["hair"],
            $playerData["name"],

            $playerData["hairDye"],
            $playerData["hideVisuals"],
            $playerData["hideVisuals2"],
            $playerData["hideMisc"],
            $playerData["hairColor"],
            $playerData["skinColor"],
            $playerData["eyeColor"],
            $playerData["shirtColor"],
            $playerData["undershirtColor"],
            $playerData["pantsColor"],
            $playerData["shoeColor"],
            $playerData["difficultyFlags"],
            $playerData["flags2"],
            $playerData["flags3"]
        );
		error_log("Parsed Player Name: " . $playerData["name"]);

        echo "Player {$playerData["name"]} (UID: $uid) has joined the server.\n";
    }
public function parsePlayerPacket($rawPacket) {
    // Force raw data to be a string.
    $rawData = (string)$rawPacket["data"];

    // Remove null bytes from the raw data.
    $cleanData = str_replace("\0", "", $rawData);
    
    // Debug logging: log the cleaned string and hex dump for comparison.
    error_log("Cleaned packet data: " . var_export($cleanData, true));
    error_log("Cleaned data (hex): " . bin2hex($cleanData));
    error_log("Cleaned data length: " . strlen($cleanData));
    
    // Extract the first two characters as the name length.
    $nameLengthStr = substr($cleanData, 0, 2);
    $nameLength = (int)$nameLengthStr;
    error_log("Name length string from packet: " . var_export($nameLengthStr, true));
    error_log("Name length (from packet): " . $nameLength);
    
    // Extract the player name, starting at offset 3 (after the two-byte length and one tab).
    $playerName = trim(substr($cleanData, 3, $nameLength));
    error_log("Player name extracted: '" . $playerName . "'");
    
    return [
        "uid" => $rawPacket["id"],
        "name" => $playerName,
        "skinVariant" => 1,  // Placeholder values; update as needed.
        "hair" => 2,
        "hairDye" => 0,
        "hideVisuals" => 0,
        "hideVisuals2" => 0,
        "hideMisc" => 0,
        "hairColor" => "#000000",
        "skinColor" => "#FFDAB9",
        "eyeColor" => "#0000FF",
        "shirtColor" => "#FF0000",
        "undershirtColor" => "#00FF00",
        "pantsColor" => "#000000",
        "shoeColor" => "#FFFFFF",
        "difficultyFlags" => 0,
        "flags2" => 0,
        "flags3" => 0
    ];
}





    public function setServerPassword(string $newPassword): bool
    {
        if ($newPassword === $this->serverPassword) {
            throw new \Exception(
                "Cannot Set Password: The given password is the same as the current password"
            );
        }
        $this->serverPassword = $newPassword;
        return true;
    }
}
?>
