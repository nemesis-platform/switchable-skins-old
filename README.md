# symfony-switchable-theme
Switchable theme for twig templates

## Usage

### Commons


### Bare themes (no user configuration)


```
{# YourBundle::base.twig.html #}
{% extends theme_registry.template('theme_identifier','layout')
```

### Configurable themes
```
{# YourBundle::base.twig.html #}
{% extends theme_registry.template('some_theme','layout', themeInstance)
```
