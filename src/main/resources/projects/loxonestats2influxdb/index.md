## Introduction

This script reads the statistics collected by the Loxone Miniserver and writes them to an InfluxDB instance.

The idea for the script started in a [thread in the Loxforum](https://www.loxforum.com/forum/german/software-konfiguration-programm-und-visualisierung/126152-beispiel-loxone-mit-node-red-auf-rpi-3-influxdb-und-grafana-visu).

## Requirements

* Python 3 with the following additional modules:
   * [Argcomplete](https://pypi.python.org/pypi/argcomplete) (optional) : `pip3 install argcomplete`
   * [InfluxDB-Python](https://github.com/influxdata/influxdb-python): `pip3 install influxdb`
   * [Requests](https://github.com/requests/requests): `pip3 install requests`
   * [terminaltables](https://github.com/Robpol86/terminaltables) (optional): `pip3 install terminaltables`
* Loxone Miniserver
* InfluxDB

## Usage

This script requires a small configuration file to define the connection to your InfluxDB instance, your Loxone Miniserver as well as which stats should be imported into which measurements.

Just copy `config.sample.json` to `config.json` and edit it to match your requirements.

The `influxdb` and `miniserver` configuration should be self explaining: Those options configure the connection to the InfluxDB and your Loxone Miniserver.

In the `stats_map` you have to map each UUID of the statistics to a measurement and (optional) tags.

As you might not know the UUIDs of the stats, you can list all available statistics including their UUID and name. To do that just execute `python3 import.py --list`.

Without any arguments, the script will start to import the statistics configured in the configuration file: Just execute `python3 import.py`