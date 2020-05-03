## Introduction

MatterBukkit allows you to relay chat messages between your Minecraft Server and [Matterbridge](https://github.com/42wim/matterbridge).

In that way, it is possible to relay your ingame chat messages to any protocol supported by Matterbridge.

## Installation

* Download the latest release and put the Jar file into your plugins folder
* Start your Minecraft server to let the plugin generate the initial configuration

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