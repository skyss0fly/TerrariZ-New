> [!NOTE]
> This Server Software is Currently Still In Development!
> If you struggle to understand the [Installation Instructions](https://github.com/skyss0fly/TerrariZ-New?tab=readme-ov-file#how-to-install-and-run-terrariz-you-will-need-administrative-permissions-on-your-device), simply copy the text into chatgpt and AI will walk you through the instructions at your Own Pace. 

# CURRENT FEATURES IMPLEMENTED:
- Client Connects to Server
- Server Sends Password Request Packet (37)
- Client Recieves Packet 37 and sends Password Packet (38)
- Server sends Continue Joining Packet (3)
- Client sends Player Information Packet (4)
- Server Has a password Authentication system
- Server Recieves Packet 4 and stores it as Player Data


# FEATURES LEFT TO IMPLEMENT BEFORE 1.0.0 RELEASE:
- Server configuration
- Multi thread workers
- garbage collection
- Server recieving Packet 4 and storing it as Player Data [DONE]
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
Cleaned packet data: '09        skyss0fly
p�{ �j��B��G��\'��G('
Cleaned data (hex): 303909736b79737330666c790a7002b47b20fc7f6affce42ffc747ffbd27fbbd471028
Cleaned data length: 35
Name length string from packet: '09'
Name length (from packet): 9
Player name extracted: 'skyss0fly'
Parsed Player Name: skyss0fly
Player skyss0fly (UID: 4) has joined the server.
</code>

# How to Install and Run TerrariZ (you will need Administrative permissions on your device):
First of all, you need to install a php Binary for your device, if you are using Windows, head to [this website](https://windows.php.net/download) and Download **A Thread SAFE version** Not The source code, If your Device is 64 bit, you will need the x64 flavour of php. if your device is 32 bit, you will need the x86 flavour of php.
if you use any other device other then windows, head to [this website](https://www.php.net/downloads.php) to see How to install it for your device.
the next thing you do after downloading your PHP Prebuilt Binary is extract it into your `C:` Drive under the folder name php (windows Only)
for other versions other then Windows, you will see in the above website link on how to extract it to your device.
Finally after you extract it, Press windows key, type environment, click the one that says "edit the system environment variables"
you will see a page for the system environment, click the Advanced tab, click environment variables on that tab under system variables, double click path. you will see a new window, press the browse button, then when it pops up the file explorer, select your `c:php` folder. press okay. then press ok until the whole environment variables screen is closed. then press Win + r . type in cmd and type php -v to see if its installed correctly.

Now Head to the Source code download for this Server software [here](https://github.com/skyss0fly/TerrariZ-New/releases) for the selected terraria version you want.
extract it. then press either start.cmd / start.sh or manually open up the server by opening up terminal and setting the directory to the directory that **contains** start.php
then simply type up:
```php start.php```
if all goes well, you should now have TerrariZ Up and running!



