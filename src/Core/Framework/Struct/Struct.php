<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Struct;

/**
 * @category  Shopware\Core
 *
 * @copyright Copyright (c) shopware AG (http://www.shopware.de)
 */
abstract class Struct implements \JsonSerializable, ExtendableInterface
{
    //allows to clone full struct with all references
    use CloneTrait;

    //allows json_encode and to decode object via json serializer
    use JsonSerializableTrait;

    //allows to assign array data to this object
    use AssignArrayTrait;

    //allows to add values to an internal attribute storage
    use ExtendableTrait;

    //allows to create a new instance with all data of the provided object
    use CreateFromTrait;

    public function toArray()
    {
        $jsonArray = [];
        foreach (get_object_vars($this) as $key => $value) {
            $jsonArray[$key] = $value;
        }

        return $jsonArray;
    }
}
