## Introduction

SignWarper allows players to place signs to teleport between them by simply right clicking on them.

By default, teleports have a cost of one ender pearl which must be in the players hand while interacting with the sign, but it can also be disabled in the configuration.

## Installation

* Download the latest release and put the Jar file into your plugins folder
* Start your Minecraft server to let the plugin generate the initial configuration files

Instructions how to manually build the project can be found on [GitLab](https://gitlab.com/Programie/SignWarper).

## Permissions

SignWarper knows the following permissions:

* `signwarper.create` - Allow to create and destroy warp signs (Default: op)
* `signwarper.use` - Allow to use warp signs (Default: everyone)
* `signwarper.*` - Allow access to all features (Default: op)

## Commands

There are no commands. Warps are defined by placing signs and used by interacting with those signs.

## How to use it?

First, place a sign at the location you want to warp to with the following content:

* First line: `[WarpTarget]`
* Second line: The name you want to use

This will create a warp target sign which defines the location a player is getting warped to.

After creating the warp target sign, create one or more warp signs from which you want to be able to warp to the target sign. This is done by placing a sign with the following content:

* First line: `[Warp]`
* Second line: The same name as used on the warp target sign

Note: The warp target sign must exist before creating a warp sign!

Once you've created both signs, you are able to right click with the `use-item` in your hand (defaults to ender pearl). Each warp will cost the number of items configured in `use-cost` (defaults to 1).

You can remove the `use-item` option in the config.yml or set it to "none" to allow any item to be used without actually using the item (i.e. each warp is free to use).

## Dynmap markers

SignWarper has built-in support for showing warp targets as markers in Dynmap.

All you need to do is install Dynmap and enable the markers using the `dynmap.enable-markers` option in the config.yml file (set the option to `true`). After that, reload/restart your server and warps will appear as markers on the map provided by Dynmap.

## Know issues

A warp won't be removed if the block is removed on which the warp target sign has been placed. The warp will continue to function, but it can only be removed by manually editing the config.yml and reloading/restarting the server.