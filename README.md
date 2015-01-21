# symfony-switchable-theme
Switchable theme for twig templates

## Usage

### Commons

To use theme swtich you should simply extend a template name, returned by ```theme_regsitry->getTemplate(type,layout)``` method. This method returns a string representing really extended template (i.e. ```SomeTheme::some_layout.html.twig```) or null

```
{# YourBundle::base.twig.html #}
{% extends theme_registry.template('theme_identifier','layout')
```

### Fallback layout

To use theme switch in case you are not shure, that template exists you can use multi-extends twig clause and supply it with fallback template, i.e.

```
{# YourBundle::base.twig.html #}
{% extends [theme_registry.template('theme_identifier','layout'), 'YourBundle::fallback.html.twig'] %}
```

### Configurable themes

To use theme with theme configurations (theme instances) you should provide ```ThemeInstance``` object for the first argument to getTemplate

```
{# YourBundle::base.twig.html #}
{% extends theme_registry.template(themeInstance,'layout')
```
