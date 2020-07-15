<?php

namespace Dynamic\Foxy\Inventory\Test\TestOnly\Model;

use Dynamic\Foxy\Inventory\Extension\ProductVariationInventoryManager;
use Dynamic\Foxy\Model\Variation;
use SilverStripe\Dev\TestOnly;

class TestVariation extends Variation implements TestOnly
{
    /**
     * @var array
     */
    private static $extensions = [
        ProductVariationInventoryManager::class,
    ];
}
