# Drupal 7 Configurator API

This module mostly exists as an API for `cfrplugin`.

The heart of the API is the interface [`ConfiguratorInterface`](src/Configurator/ConfiguratorInterface.php).

A configurator does three things:

1. It can create a business object or value based on a configuration (array or value).
  See [`ConfToValueInterface::confGetForm()`](src/ConfToValue/ConfToValueInterface.php).
  The configuration can come from a file, from the database, or from user input, using the form below. This means, it can generally not be trusted.
  On the other hand, the resulting object or value will be safe to use.
2. It can provide a form to edit a configuration array that can later be used for (1.).
  See [`ConfToFormInterface::confGetForm()`](src/ConfToForm/ConfToFormInterface.php).
3. It can generate a summary for a configuration array.
  See [`ConfToSummaryInterface::confGetSummary()`](src/ConfToSummary/ConfToSummaryInterface.php).

The idea is that for a particular configurator, the configuration in all three methods should be compatible or, one could say, use the same schema, interpret it the same way.

It would not make sense to freely combine a random ConfToValue with another random ConfToValue.  

For instance, there could be a configurator for "entity".

- The configuration would be an array like `$conf = ['type' => 'node', 'etid' => '55']`.
- `$configurator->confGetForm($conf);` would generate a form where a user can choose both the type and the entity id.
- `$configurator->confGetSummary($conf);` would return e.g. "Node: Lorem Ipsum".
- `$configurator->confGetValue($conf);` would return the loaded entity object.

There could be another configurator where the configuration is as above, but the value is just the same array as the configuration.
In this case, the `$configurator->confGetValue()` would only do some validation and sanitization.

It is important to note that the configuration is never stored in the configurator object, but only passed as an argument.


## Nesting and composition

Configurators are designed for composition.

For instance, a "message configurator" could have one inner configurator for the title, and another inner configurator for the message body.
In each of the three methods (form, summary, value), the outer configurator would call the same method of each of the inner configurators, and then build a combined result.

Or, with the entity example, the configurator could be implemented as an outer configurator using two inner configurators: One for the entity type, the other for the entity id.
Of course, the form to choose the entity id would depend on the entity type, because it needs to validate which entity ids exist.
This means that the outer configurator needs to dynamically generate or provide a different inner configurator for the etid, for each entity type. 


