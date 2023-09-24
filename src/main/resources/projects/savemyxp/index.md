## Introduction

Did you ever reach the moment where you have too many XP on your player and wanted to just keep them somewhere save before losing them in the Nether?

Then this plugin is the right one for you! It allows you to place an unlimited amount of signs securely storing your XPs by just hitting it with your empty hand.

## Installation

* Download the latest release and put the Jar file into your plugins folder
* Start your Minecraft server to let the plugin generate the initial configuration

Instructions how to manually build the project can be found on [GitHub](https://github.com/Programie/SaveMyXP).

## Permissions

The following permission nodes are available:

* `savemyxp.create` - Allow creating (and using) your own XP signs (Default: everyone)
* `savemyxp.destroy-any` - Allow to destroy any XP signs (not just your own) (Default: op)
* `savemyxp.*` - Allow access to all features (Default: op)

## Commands

There are no commands.

## How to use it?

Just create a sign with the text `[SaveMyXP]` on the first line.

Once successfully placing the sign, your player name will appear on the second line.

Now just hit the sign with your empty hand (i.e. do not carry a block or tool), and it will transfer your XP to the sign.

To retrieve the XP from the sign again, just interact with the sign (i.e. right click on it).

You may also sneak while hitting the sign or interact with it to transfer only the configured amount to the sign or withdraw the whole XP from it.