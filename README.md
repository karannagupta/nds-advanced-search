# NDS Advanced Search

**License:** GPLv2 or later

**License URI:** http://www.gnu.org/licenses/gpl-2.0.html

**Tags:** WordPress advanced search, autosuggest, auto completion, custom loop, search multiple post types, settings page for post types.

**Requires PHP at least:** 5.6.0

**A WordPress plugin to add an Advanced Search Form with auto-suggest using a shortcode.**

_The intent is to help developers with a boilerplate search plugin with search suggestions that can be customized for advanced scenarios as shown here https://github.com/nuancedesignstudio/nds-advanced-search-demo._

## Description

The plugin adds a custom Search Form using a `shortcode` on any page.
It provides `search suggestions` as you type. The results are displayed on the same page using a flexbox container.
The plugin overrides the `searchform.php` defined by the theme but only on the page where the shortcode is used.
You can control the `custom post types` to include in the search from `Settings->Advanced Search Settings`. By default only posts are included.

## Installation Manually

1. Download the latest archive and extract to a folder
2. Upload `nds-advanced-search` to the `/wp-content/plugins/` directory
3. Activate the plugin through the `Plugin Menu` in WordPress

## Usage

1. Select the post types to include in the search `Dashboard->Settings->Advanced Search Settings`
2. Add the shortcode `[nds-advanced-search]` on the page where you want the search form.

**_Note: You may need to modify the CSS to suit your theme._**

## Features

- Makes uses of WordPress transients to cache the search results. The transient expiry is six hours.
- The AJAX request for search suggestions is also cached to prevent ajax calls when a search term is repeated
- Specify post types to include in search from the `Dashboard->Settings->Advanced Search Settings`
- Does not depend on Theme Page Templates
- Makes use of my `Object Oriented Plugin Bolier Plate` here: https://github.com/nuancedesignstudio/WordPress-Plugin-Boilerplate

## Developer Notes

- Boilerplate files and comments have not been removed.
- To rename the plugin and files refer the steps here: https://github.com/nuancedesignstudio/WordPress-Plugin-Boilerplate
- For an example of how the plugin can be extended with Search Filters using Custom Taxonomies and Advanced Custom Fields, see https://github.com/nuancedesignstudio/nds-advanced-search-demo

### i18n Tools

The Plugin uses a variable to store the text domain used when internationalizing strings throughout the code. To take advantage of this method, there are tools that are recommended for providing correct, translatable files:

- [Poedit](http://www.poedit.net/)
- [makepot](http://i18n.svn.wordpress.org/tools/trunk/)
- [i18n](https://github.com/grappler/i18n)

Any of the above tools should provide you with the proper tooling to internationalize the plugin.

However, if you still face problems translating the strings with an automated tool/process, replace `$this->plugin_text_domain` with the literal string of your plugin's text domain.

## Credits

The plugin boiler plate is a modified version of the `Plugin Boiler Plate` here: https://github.com/DevinVinson/WordPress-Plugin-Boilerplate

## Screenshots

#### 1. Access Plugin Settings in the Dashboard

![Plugin Settings Link](https://karannagupta.com/kg/wp-content/uploads/2019/03/advanced-search-plugin-settings.png "Access plugin settings in the Dashboard")

#### 2. Include the shortcode `[nds-advanced-search]` to add an advanced search form.

![Add Shortcode to load the form](https://karannagupta.com/kg/wp-content/uploads/2019/03/search-page-with-shorcode.png "Shortcode to load the form")

#### 3. Search suggestions as you type!

![Search suggestions as you type](https://karannagupta.com/kg/wp-content/uploads/2019/03/advanced-search-autosuggest-in-action.png "Search suggestions as you type")

#### 4. Search results in a flexbox container

![Search results in a flexbox container](https://karannagupta.com/kg/wp-content/uploads/2019/03/advanced-search-results-flexbox.png "Search results in a flexbox container")

## Flow of Control

#### ![Plugin Flow of Control](https://karannagupta.com/kg/wp-content/uploads/2019/03/advanced-search-plugin-flow-of-control.png "Plugin Flow of Control")

#### ![Plugin Flow of Control Transients](https://karannagupta.com/kg/wp-content/uploads/2019/03/advanced-search-plugin-delete-transients.png "Plugin Flow of Control Transients")
