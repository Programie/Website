## Introduction

Creepers explode, but what about exploding sheep, cows or pigs? This plugin makes any mob explode once they die.

It is possible to configure the plugin to just have explosive mobs if they are spawned using the `/spawnexplosivemob` command or simply every time a mob dies.

You can also define per mob configurations. For example, you only want to have explosive sheep, but any other mob should just die on death without any explosion.

And the funny part: Players with the correct permissions can even spawn explosive mobs in front of other players!

**Note:** If you are using some damage restore plugin like [CreeperHeal](https://www.spigotmc.org/resources/creeperheal.13346), make sure to configure it to also restore custom explosions (i.e. explosions caused by other plugins). Otherwise, explosions caused by this plugin **are not reverted**. Keep that in mind to not accidentally mess your server.

## Installation

* Download the latest release and put the Jar file into your plugins folder
* Start your Minecraft server to let the plugin generate the initial configuration

You may also download the latest release from [CurseForge](https://www.curseforge.com/minecraft/bukkit-plugins/explosivemobs).

Instructions how to manually build the project can be found on [GitLab](https://gitlab.com/Programie/ExplosiveMobs).

## Permissions

ExplosiveMobs knows the following permissions:

  * `explosivemobs.spawn` - Allows the player to spawn an explosive mob
  * `explosivemobs.spawn.target` - Allows the player to spawn an explosive mob in front of another player
  * `explosivemobs.*` - Allow access to all features (Default: op)

## Commands

You can use the `/spawnexplosivemob` command to spawn an explosive mob.

Just execute it without any arguments to see the command usage.