## Introduction

Screenshot Manager provides a simple frontend to view and annotate (draw lines, circles and rectangles) your recent screenshots.

## Installation

* Download the latest release
* Install the required Python modules using pip: `pip3 install -r requirements.txt`
* Start the application (`screenshot-manager.py`)
* On the first start, Screenshot Manager will ask for the location of your screenshots. It can be a folder or a file with a list of file paths.

## Known issues

### Application theme is different from other applications

When installing PySide2 using pip, it's possible that the application does not use the native desktop theme. In that case, simply install PySide2 using your package manager.

In case of Debian based Linux distributions (e.g. Ubuntu, Mint, etc.), install PySide2 using the following command:

```
sudo apt-get install python3-pyside2.qtcore python3-pyside2.qtgui python3-pyside2.qtwidgets
```

After that, you should remove PySide2 which has been installed by pip using `pip3 uninstall PySide2`. Otherwise, the application continues to use PySide installed with pip.