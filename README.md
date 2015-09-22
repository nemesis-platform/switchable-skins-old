# scaytrase/symfony-switchable-theme

Runtime choosing for Twig `{% extends %}` clause
--

[![Packagist](https://img.shields.io/packagist/dd/scaytrase/symfony-switchable-theme.svg)]()
[![Packagist](https://img.shields.io/packagist/dm/scaytrase/symfony-switchable-theme.svg)]()
[![Packagist](https://img.shields.io/packagist/dt/scaytrase/symfony-switchable-theme.svg)]()

[![Packagist](https://img.shields.io/packagist/v/scaytrase/symfony-switchable-theme.svg)]()
[![Packagist](https://img.shields.io/packagist/l/scaytrase/symfony-switchable-theme.svg)]()

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/344dac6d-0e27-4b59-84bb-6d0ed28980c0/big.png)](https://insight.sensiolabs.com/projects/344dac6d-0e27-4b59-84bb-6d0ed28980c0)

Run-time switchable and user-configurable twig layouts

Originally developed to use with [BraincraftedBootstrapBundle](http://bootstrap.braincrafted.com/) theme switcher allows you to dynamically define twig extended templates at runtime. Also you can compile assets for your theme if you need.
Current version supports theme configuration, so themes can be preconfigured (and have multiple configuration instances for single theme) as same as each configuration can be compiled separately.

## Usage

### Basic example

See [tests](src/ScayTrase/SwitchableThemeBundle/Tests/Core/ThemeTest.php) for basic usage example.  You can find simple `ThemeInterface` implementation and logic example.

### Commons

To use theme switch you should simply extend a template name, returned by ``` ThemeRegistry::getTemplate(theme,layout) ``` method. This method returns a string representing really extended template (i.e. ```SomeTheme::some_layout.html.twig```) or null

```php
class MyTheme implements ThemeInterface {
    public function get($layout = 'base') {return 'MyBundle:MyTheme:base.html.twig';}
    public function all() {return array('base' => 'MyBundle:MyTheme:base.html.twig');}
    public function getType() {return 'my_theme';}
}
```

```twig
{# MyBundle:MyTheme:base.html.twig #}
Here your theme basic template goes.
```

```twig
{# MyBundle::base.twig.html #}
{% extends theme_registry.template('my_theme','base') %}
```

### Fallback layout

To use theme switch in case you are not shure, that template exists you can use multi-extends twig clause and supply it with fallback template, i.e.

```twig
{# MyBundle::base.twig.html #}
{% extends [theme_registry.template('my_theme','base'), 'MyBundle::fallback.html.twig'] %}
```

### Configurable themes

To use theme with theme configurations (theme instances) you should provide ```ThemeInstance``` object for the first argument to getTemplate

```twig
{# MyBundle::base.twig.html #}
{% extends theme_registry.template(themeInstance,'layout') %}
```

### Compilable themes

**TBD**
