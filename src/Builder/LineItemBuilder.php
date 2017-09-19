<?php
/**
 * Created by PhpStorm.
 * Date: 9/15/17
 */

namespace OK\Builder;


use OK\Model\Amount;
use OK\Model\Cash\LineItem;

/**
 * Class LineItemBuilder
 * @package OK\Builder
 *
 * @api
 * @method LineItemBuilder setQuantity(int $qty)
 * @method LineItemBuilder setProductCode(string $code)
 * @method LineItemBuilder setDescription(string $description)
 * @method LineItemBuilder setAmount(Amount $amount)
 * @method LineItemBuilder setVat(int $vat)
 * @method LineItemBuilder setCurrency(string $currency)
 *
 * @method LineItem build()
 */
class LineItemBuilder extends GenericBuilder
{
    function getObject() {
        return new LineItem;
    }
}