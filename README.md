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
