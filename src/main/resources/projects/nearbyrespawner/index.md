## Introduction

Have you ever moved away a large distance from your world spawn or spawn bed just to get killed by those annoying creepers and finding yourself respawning too far away from where you died? Then this Bukkit plugin has just been made for you!

This plugin tries to find a safe location around your latest death location and respawns you at the new safe location. As getting respawned next to the death location would be too easy, the plugin searches for a random location in a configurable radius around the death location (default: max 1000 blocks).

Searching for a safe location is done by checking the highest block of a random location. So you won't be randomly spawned unarmed in a cave next to a cave spider spawner. The plugin also tries to prevent respawning yourself in lava lakes and big oceans.

In case your normal respawn location (e.g. your spawn bed or world spawn) would be near your death location (default: max 1000 blocks), the plugin simply uses the normal respawn behavior letting you respawn in your safe house or world spawn.

## Installation

* Download the latest release and put the Jar file into your plugins folder
* Start your Minecraft server to let the plugin generate the initial configuration

You may also download the latest release from [CurseForge](https://www.curseforge.com/minecraft/bukkit-plugins/nearbyrespawner).

Instructions how to manually build the project can be found on [GitLab](https://gitlab.com/Programie/NearbyRespawner).

## Permissions

* `nearbyrespawner.random-respawn` - Allow players to get respawned at a random location (default: `true`)

## Commands

Currently, there are no commands available.