## Introduction

Note: This plugin is a remake of the [MobTalk](https://dev.bukkit.org/projects/mobtalk) plugin. Most of the older mob messages are copied from MobTalk. Messages for mobs not present at the last release of the MobTalk plugin still have to be added this plugin.

It is simple: It lets mobs talk to the player.

For example if you attack a mob, a random message will be sent to the attacking player.

## Installation

* Download the latest release and put the Jar file into your plugins folder
* Start your Minecraft server to let the plugin generate the initial configuration files
* Optional: Customize the configuration to fit your needs (use `/talkingmobs reload` to reload)

You may also download the latest release from [Modrinth](https://modrinth.com/plugin/talkingmobs).

Instructions how to manually build the project can be found on [GitLab](https://gitlab.com/Programie/TalkingMobs).

## Permissions

TalkingMobs knows the following permissions:

* `talkingmobs` - Required to access the /talkingmobs command (Default: everyone)
* `talkingmobs.receive` - Allow to receive messages from mobs (Default: everyone)
* `talkingmobs.reload` - Allow to reload the configuration (Default: op)
* `talkingmobs.*` - Allow access to all features (Default: op)


## Commands

The `/talkingmobs` command is the one and only command provided by this plugin.

**Usage:** `/talkingmobs [subcommand] [arguments]`

The following sub commands are currently available:

* `help` - Show the help of the plugin
* `reload` - Reload the configuration
* `toggle` - Toggle messages sent by mobs
* `version` - Show the version of the plugin


## Event types

The following event types are currently available and can be used for the type in the `/talkingmobs toggle <type>` command and in the configuration files.

* `attacked` - Mob has been attacked by a player
* `idle` - Mob is idle, for example the mob is just standing/walking/running around (Currently unused)
* `interacted` - Player interacted with the mob (Right click on mob)
* `killed_player` - Mob has been killed by a player
* `killed_other` - Mob has been killed by something else (not a player)
* `looking` - Mob is looking at the player (and player is looking at the mob)
* `spawn` - Mob has been spawned (Mob spawner, egg or another plugin by default, but can be configured in config.yml)
* `tamed` - Mob has been tamed

Additional to those event types, the `killed` event type can be used in the messages configuration to define a message for both types (player and other).


## Configuration

The main configuration can be changed by editing the config.yml file.

All messages can be customized in the messages.yml file.

After changing the configuration, reload it using `/talkingmobs reload`.