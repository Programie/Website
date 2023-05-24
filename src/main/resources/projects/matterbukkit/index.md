## Introduction

MatterBukkit allows you to relay chat messages between your Minecraft Server and [Matterbridge](https://github.com/42wim/matterbridge).

In that way, it is possible to relay your ingame chat messages to any protocol supported by Matterbridge.

## Installation

* Download the latest release and put the Jar file into your plugins folder
* Start your Minecraft server to let the plugin generate the initial configuration

Configure the plugin:

* Set `url` to the URL where you're running MatterBridge, with port 4242 or whatever you'd like to use (if you're running it on the same server, you can use `http://localhost:4242`)
* Set `gateway` to the name of your MatterBridge gateway
* Set a `token` to secure the API (This is optional, but heavily recommended)
* Configure all other settings to your liking

Configure Matterbridge:

Add an API protocol and gateway to `matterbridge.toml`. Here is an example:

```toml
[api]
[api.minecraft]
Token="pasteTokenHere"
# Set BindAddress to "0.0.0.0:port" if your Minecraft server is running on a different server, and you're not using a reverse proxy
BindAddress="127.0.0.1:4242"
Buffer=1000
RemoteNickFormat="[{PROTOCOL}] {NICK}"

[[gateway.inout]]
account="api.minecraft"
channel="api"
```

Add any other protocols and gateways you would like to use, following [MatterBridge's documentation](https://github.com/42wim/matterbridge/wiki/How-to-create-your-config).

Instructions how to manually build the project can be found on [GitLab](https://gitlab.com/Programie/MatterBukkit).

## Permissions

There are no permissions to set.

## Commands

Currently, there are no commands available.

## Events

Additionally to chat messages, MatterBukkit is also able to send messages to Matterbridge for the following events:

* `death` - A player died
* `advancement` - A player reached an advancement
* `level-up` - A player leveled up (can be configured to, for example, only send a message every 5 levels or only starting with a specific level)
* `join` - A player joined the server
* `quit` - A player left the server

Whether to send a message for a specific event and what should be contained in the message can be customized for each event type in the config file.