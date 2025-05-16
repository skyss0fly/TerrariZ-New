> [!NOTE]
> This Server Software is Currently Still In Development!

# CURRENT FEATURES IMPLEMENTED:
- Client Connects to Server
- Server Sends Password Request Packet (37)
- Client Recieves Packet 37 and sends Password Packet (38)
- Server sends Continue Joining Packet (3)
- Client sends Player Information Packet (4)
- Server Has a password Authentication system 


# FEATURES LEFT TO IMPLEMENT BEFORE 1.0.0 RELEASE:
- Server configuration
- Multi thread workers
- garbage collection
- Server recieving Packet 4 and storing it as Player Data
- The rest of the Server -> Client -> Server Initial Handshake
- Write a whole new Terraria Protocol Library from scratch to handle EACH and INDIVIDUAL Packet for items, events, entities, tiles.
- Proper Messsages for the initial handshake such as if invalid password, tell the client the reason


Latest Stable Test Console Log:
<code>
Terraria Server Online
string(13) "
            Terraria279"
Checking for packet ID: 1
Received packet ID 1
Sending Password Packet (ID 37) to client
packetstring(4) "%"
payloadstring(1) ""
lengthint(4)
string(5) "&abc"
Received Password
Correct Password
packetstring(4) "0"
payloadstring(1) "0"
lengthint(4)
Waiting for Player Info Packet...
string(44) "09  skyss0fly
p�{ �j��B��G��'��G("
Player Packet 4 Raw Packet DATA:
array(2) {
  ["id"]=>
  int(4)
  ["data"]=>
  string(43) "09        skyss0fly
p�{ �j��B��G��'��G("
}

</code>
