## Introduction

Are you annoyed to lose all your XP just because you didn't heard a Creeper behind you?

RetainXP allows you to keep a part of your XP after you die. For example, you can configure it to keep 50% of your XP on death instead of losing all of it.

## Installation

* Download the latest release and put the Jar file into your plugins folder
* Start your Minecraft server to let the plugin generate the initial configuration

Instructions how to manually build the project can be found on [GitLab](https://gitlab.com/Programie/RetainXP).

## Permissions

Currently, there is only one permission available: `retainxp.retain` (Default: everyone)

## How does it work?

On player death (and if the player has the `retainxp.retain` permission) the current XP level gets multiplied with the `multiplier` value specified in the config file (which defaults to 1.0). On respawn, the new player level will be the calculated value.

Examples:

* If the multiplier is set to 1.0 and you die on XP level 60, you will be respawned with XP level 60 (i.e. just like you set the `keepInventory` game rule but without keeping the inventory content).
* If the multiplier is set to 0.5 and you die on XP level 60, you will be respawned with XP level 30.