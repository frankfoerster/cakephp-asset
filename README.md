# cakephp-asset
[![Build Status](https://travis-ci.org/frankfoerster/cakephp-asset.svg?branch=master)](https://travis-ci.org/frankfoerster/cakephp-asset)

Provides a CakePHP 3.x AssetHelper to selectively add last modified timestamps to css and js assets.
CakePHP's implementation of Asset timestamps does not allow you to apply the behavior selectively to single files. Therefore I created this little Helper.

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

Run the following command
```sh
composer require frankfoerster/cakephp-asset
 ```

## Configuration

You can load the plugin using the shell command:

```
bin/cake plugin load FrankFoerster/Asset
```

Or you can manually add the loading statement in the **config/boostrap.php** file of your application:

```php
Plugin::load('FrankFoerster/Asset');
```

## Load the Helper

Load the AssetHelper in the **src/View/AppView.php** file of your application.

```php
...
public function initialize()
{
    $this->loadHelper('Asset', [
        'className' => 'FrankFoerster/Asset.Asset'
    ]);
}
...
```

## Use the AssetHelper in your Layout.

The last modified time of the asset files is automatically appended.

The methods provided are ``AssetHelper::css()`` and ``AssetHelper::js()``. Both take up to three arguments:

1. ``$path`` - The path to the asset relative to the webroot of your app or plugin.
2. ``$plugin`` - Either false to link to an app asset, or the name of a plugin. (default false)
3. ``$appendTimestamp`` - Whether to append a last modified timestamp to the url. (default true)

### CSS

Linking the CSS file **your_app/webroot/css/app.css**

```php
echo $this->Asset->css('css/app.css');
```

produces the following output:

```html
<link rel="stylesheet" type="text/css" href="css/app.css?t=1460443221">
```

### JS

Linking the JS file **your_app/webroot/js/app.js**

```php
echo $this->Asset->js('js/app.js');
```

produces the following output:

```html
<script type="text/javascript" src="js/app.js?t=1460443221"></script>
```

### Linking Plugin Assets

Linking the JS file **MyPluginRootPath/webroot/js/plugin.js**

```php
echo $this->Asset->js('js/plugin.js', 'MyPlugin');
```

produces the following output:

```html
<script type="text/javascript" src="my_plugin/js/plugin.js?t=1460443221"></script>
```
