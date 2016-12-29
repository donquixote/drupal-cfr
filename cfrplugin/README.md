

## Plugin types

In cfrplugin, every interface is automatically a plugin type.

## Plugins

A plugin of a type is an id (machine name) and label associated with one of the following

- a class that implements the plugin type's interface
- a static method that returns an object implementing the type's interface.
- a class that implements `Drupal\cfrapi\Configurator\ConfiguratorInterface`, with a `->confGetValue()` method that returns an instance of the plugin type's interface.
- a static method that returns such a configurator object. Typically the class that holds this static method should implement the plugin type's interface, so that cfrplugindiscovery can determine the plugin type.

Plugins are registered by implementing `hook_cfrplugin_info()`, and returning a nested associative array of plugin definitions keyed by the plugin type (interface name) and the plugin id.

It is recommended to organize your classes with PSR-4, annotate the plugins (class or static method) with `@CfrPlugin(..)` in the doc comment, and then call `return cfrplugindiscovery()->moduleFileScanPsr4(__FILE__);` from within `hook_cfrplugin_info()`.

Have a look how other modules do it, e.g. [renderkit](https://drupal.org/project/renderkit).

More details below, "how plugins are defined".

## Configurator objects

No matter which of the 4 methods you use, cfrplugin will always create an object that implements `ConfiguratorInterface`.

This object has four methods: `confGetForm()`, `confGetSummary()`, `confGetValue()` and `confGetPhp()`. Each of them expects a `$conf` argument, which is typically an array or a scalar - something that can be configured in a form, and stored/serialized in the database.

The `confGetValue()` method returns the behavior object that will do the actual business work, and that implements the interface specified in the plugin type. E.g. in renderkit, this could be an implementation of `EntityDisplayInterface`, or `ImageProcessorInterface`, etc. The classes of these objects are typically agnostic of cfrplugin, and will NOT have a copy of the `$conf` array/scalar.

### No need for "plugin managers"

Unlike with Drupal 8 core plugins, a "plugin type" in cfrplugin does not require a dedicated "plugin manager" class. All you need is the interface.

However, modules like [entdisp](https://drupal.org/project/entdisp) or [listformat](https://drupal.org/project/listformat) do provide something like plugin managers for convenience.

### Drilldown configurators

The collection of available plugins / configurators for a given plugin type is called "configurator family" or "plugin family".

Cfrplugin provides a higher-level configurator, which implements the same `ConfiguratorInterface` as the other configurators, to allow to choose a plugin of the given type, and the options for the specific plugin.

The `$conf` array for this family configurator has the format `array('id' => $id, 'options' => $pluginConf)`, where `$id` is the plugin id, and `$pluginConf` is the configuration that will be passed to the plugin's own configurator.


## How plugins are defined

From above:

> No matter which of the 4 methods you use, cfrplugin will always create an object that implements `ConfiguratorInterface`.

There are 4 ways to do this.

### Custom configurator class

First, you can write your own configurator class, and annotate it.


    /**
     * @CfrPlugin(
     *   id = "myplugin",
     *   label = @t("My plugin")
     * )
     */
    class MyConfigurator implements ConfiguratorInterface {
      [..]
      
      /**
       * @return \Drupal\renderkit\EntityDisplay\EntityDisplayInterface
       */
      function confGetValue($conf) {
        [..]
        return new MyEntityDisplay(..);
      }
    }


Make sure to put a @return tag on the confGetValue() method, because this will be used to determine the plugin type (the interface).

Parameters of the constructor are considered to be "contextual parameters". The parameter name of these is really important. E.g. a configurator that requires an `$entityType` parameter will get the entity type name (string) from the context in which the plugin is configured. If the context does not provide an entity type name, the plugin will not be available.

Drupal 8: An idea is that parameters can be separately annotated to receive either services OR contextual values.

### Configurator factory (static method)

Instead of writing your own configurator class, you provide and annotate a static method that builds a configurator from existing, generic configurator classes.

The static method needs a `@return` tag specifying that it returns a configurator object.

The plugin type is determined from the interfaces implemented by the class that holds the static method. This may seem weird, but it allows to have the plugin type/interface be understood by the IDE. And it allows to have this method on the same class as the actual business object.

Parameters of this static method are, again, filled with contextual values based on the parameter name.

Drupal 8: An idea is that parameters can be separately annotated to receive either services OR contextual values.

### Handler class

Provide a class implementing the plugin type interface, e.g. `EntityDisplayInterface` (renderkit) and annotate it.
cfrplugin will automatically create a configurator that will instantiate this class in the confToValue() method.

Parameters of the constructor are considered to be configuration options. Based on the type hint on each parameter, cfrplugin will show a sub-form to choose a plugin of this type to fill the parameter.

Drupal 8: An idea is that parameters can be separately annotated to receive either services OR configuration values OR contextual values.

### Handler factory (static method)

An annotated static method that returns e.g. an implementation of the plugin type interface, e.g. `EntityDisplayInterface`.

The plugin type is determined from the `@return` tag.

Parameters of the static method are considered to be configuration options, as above.

Drupal 8: An idea is that parameters can be separately annotated to receive either services OR configuration values OR contextual values.
