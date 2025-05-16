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
                            // Example: Assuming first byte is UID
                            $uid = ord($playerPacket["data"][0]); // Extract UID
                            $playerName = trim(
                                substr($playerPacket["data"], 1)
                            ); // Extract player name
                        /* 
$playerData = [
    "uid" => ord($playerPacket["data"][0]), // Unique Player ID (U8)
    "skinVariant" => ord($playerPacket["data"][1]), // Skin Variant (U8)
    "hair" => ord($playerPacket["data"][2]), // Hair Style (U8)
    "name" => trim(substr($playerPacket["data"], 3)), // Player Name (String)
    "hairDye" => ord($playerPacket["data"][strlen($playerData["name"]) + 3]), // Hair Dye (U8)
    "hideVisuals" => ord($playerPacket["data"][strlen($playerData["name"]) + 4]), // Hide Visuals (U8)
    "hideVisuals2" => ord($playerPacket["data"][strlen($playerData["name"]) + 5]), // Hide Visuals 2 (U8)
    "hideMisc" => ord($playerPacket["data"][strlen($playerData["name"]) + 6]), // Hide Miscellaneous (U8)

    // Extract RGB Color Data
    "hairColor" => [
        'r' => ord($playerPacket["data"][strlen($playerData["name"]) + 7]),
        'g' => ord($playerPacket["data"][strlen($playerData["name"]) + 8]),
        'b' => ord($playerPacket["data"][strlen($playerData["name"]) + 9])
    ],
    "skinColor" => [
        'r' => ord($playerPacket["data"][strlen($playerData["name"]) + 10]),
        'g' => ord($playerPacket["data"][strlen($playerData["name"]) + 11]),
        'b' => ord($playerPacket["data"][strlen($playerData["name"]) + 12])
    ],
    "eyeColor" => [
        'r' => ord($playerPacket["data"][strlen($playerData["name"]) + 13]),
        'g' => ord($playerPacket["data"][strlen($playerData["name"]) + 14]),
        'b' => ord($playerPacket["data"][strlen($playerData["name"]) + 15])
    ],
    "shirtColor" => [
        'r' => ord($playerPacket["data"][strlen($playerData["name"]) + 16]),
        'g' => ord($playerPacket["data"][strlen($playerData["name"]) + 17]),
        'b' => ord($playerPacket["data"][strlen($playerData["name"]) + 18])
    ],
    "undershirtColor" => [
        'r' => ord($playerPacket["data"][strlen($playerData["name"]) + 19]),
        'g' => ord($playerPacket["data"][strlen($playerData["name"]) + 20]),
        'b' => ord($playerPacket["data"][strlen($playerData["name"]) + 21])
    ],
    "pantsColor" => [
        'r' => ord($playerPacket["data"][strlen($playerData["name"]) + 22]),
        'g' => ord($playerPacket["data"][strlen($playerData["name"]) + 23]),
        'b' => ord($playerPacket["data"][strlen($playerData["name"]) + 24])
    ],
    "shoeColor" => [
        'r' => ord($playerPacket["data"][strlen($playerData["name"]) + 25]),
        'g' => ord($playerPacket["data"][strlen($playerData["name"]) + 26]),
        'b' => ord($playerPacket["data"][strlen($playerData["name"]) + 27])
    ],

    "difficultyFlags" => ord($playerPacket["data"][strlen($playerData["name"]) + 28]), // Difficulty Flags (U8)
    "flags2" => ord($playerPacket["data"][strlen($playerData["name"]) + 29]), // Additional Flags (U8)
    "flags3" => ord($playerPacket["data"][strlen($playerData["name"]) + 30]) // More Flags (U8)
];

$this->addPlayer($clientSocket, $playerData); */
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

        echo "Player {$playerData["name"]} (UID: $uid) has joined the server.\n";
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
