# ThemePackagerComponent

ThemePackagerComponent (TPC) is a tool to build installable packages from objects and files within a MODX site. Possible uses include:
* Packaging a theme
* Packaging a new component or Extra
* Migrating specific objects from one site/environment to another

## Installation

You can install TPC via the Package Manager in any MODX Revolution site, or download the latest package from: http://modx.com/extras/package/themepackagercomponent/

## Basic Usage

After installation, in the main menu of the Manager go to "Components" -> "ThemePackagerComponent". Specify a Name, Version and Release for your package, select the two checkboxes for "Package All..." and click the "Export Transport Package" button in the top right corner of the screen. You will be prompted to download the resulting package, which will contain all Resources, Elements, Sub-packages and contents of the assets/ folder in the current MODX site.

To install the package in another site, upload it to the {core_path}packages/ directory. Then in Package Management, click the small arrow next to the "Download Extras" button and select "Search Locally for Packages". Click "Yes" in the resulting dialog box, and your package should appear in the grid for installation, like any other MODX Extra or package.

## Documentation

Learn more about TPC in the [Official Documentation](http://rtfm.modx.com/extras/revo/themepackagercomponent).

## Credits

TPC is truly a team effort. Thanks to @opengeek for the Vapor technology, @netprophet for the integration with the legacy [Packman](http://modx.com/extras/package/packman) UI, and @theboxer for ongoing maintenance, integration and improvements, as well as all the beta testers and contributors.

## License

TPC is Copyright (c) MODX, LLC

For the full copyright and license information, please view the [LICENSE](./LICENSE "LICENSE") file that was distributed with this source code.
