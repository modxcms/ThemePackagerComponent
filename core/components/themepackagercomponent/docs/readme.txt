----------------------------
Extension: ThemePackagerComponent
----------------------------
Version: 1.3.0-dev1
Since: September, 2013
Author: Mike Schell <mike@modx.com>, John Peca <john@modx.com>
Contributors: Jason Coward <jason@modx.com>, YJ Tso <yj@modx.com>
License: GNU GPLv2 (or later at your option)

ThemePackagerComponent (TPC) is a tool to build installable packages from objects and files within a MODX site. Possible uses include:
* Packaging a theme
* Packaging a new component or Extra
* Migrating specific objects from one site/environment to another

## Installation

You can install TPC via the Package Manager in any MODX Revolution site, or download the latest package from: http://modx.com/extras/package/themepackagercomponent/

## Important!

TPC is a -dev release. This means it's still in development and not bug-free. In fact there are known issues with uninstalling some packages made with TPC. While TPC works very well for a narrow set of tested use cases, there are a potentially unlimited number of use cases in which it has not been tested at all, and in some cases may break your site, or the site in which the resulting package is installed into. 

For mission-critical applications, and for those with the know-how, it's recommended to manually author build scripts.

## Basic Usage

After installation, in the main menu of the Manager go to "Components" -> "ThemePackagerComponent". Specify a Name, Version and Release for your package, select the two checkboxes for "Package All..." and click the "Export Transport Package" button in the top right corner of the screen. You will be prompted to download the resulting package, which will contain all Resources, Elements, Sub-packages and contents of the assets/ folder in the current MODX site.

To install the package in another site, upload it to the {core_path}packages/ directory. Then in Package Management, click the small arrow next to the "Download Extras" button and select "Search Locally for Packages". Click "Yes" in the resulting dialog box, and your package should appear in the grid for installation, like any other MODX Extra or package.

## Documentation

Learn more about TPC in the [Official Documentation](http://rtfm.modx.com/extras/revo/themepackagercomponent).

## Contribution

Help make TPC better by reporting [issues](https://github.com/modxcms/ThemePackagerComponent/issues) and submitting [solutions](https://github.com/modxcms/ThemePackagerComponent/pulls)

## Credits

TPC is truly a team effort. Thanks to @opengeek for the Vapor technology, @netprophet for the integration with the legacy [Packman](http://modx.com/extras/package/packman) UI, and @theboxer for ongoing maintenance, integration and improvements, as well as all the beta testers and contributors.
