<?php

namespace Donquixote\Cf\Schema\Id;

use Donquixote\Cf\Schema\CfSchemaLocalInterface;
use Donquixote\Cf\SchemaBase\CfSchemaBase_AbstractIdInterface;

/**
 * Base interface for schemas where the value is id-like (string or integer).
 *
 * @todo This is not really a schema..
 */
interface CfSchema_IdInterface extends CfSchemaBase_AbstractIdInterface, CfSchemaLocalInterface {

}
