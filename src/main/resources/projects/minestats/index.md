## Introduction

MineStats allows your Minecraft Server to collect some useful statistics about the players on it.

For storage, it uses an InfluxDB instance. So you might also use applications like [Grafana](https://grafana.com) to visualize the collected statistics.

## Installation

* Download the latest release and put the Jar file into your plugins folder
* Start your Minecraft server to let the plugin generate the initial configuration

Instructions how to manually build the project can be found on [GitHub](https://github.com/Programie/MineStats).

## Permissions

* `minestats.stats` - Allow to show current levels and highscore (default: `true`)

## Commands

You can use the `/stats` command to display the current levels and highscore.

## Collected data

The following data is collected per player:

* XP (total earned XP points since the last death)
* Current level
* Health
* Food/Hunger

The player name as well as the UUID of the player is added to the collected metrics.

By default, the data is collected every 1200 ticks (about every 60 seconds), but it can be changed in the config file using the `interval` option.