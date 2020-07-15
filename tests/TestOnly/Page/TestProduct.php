<?php

namespace Dynamic\Foxy\Inventory\Test\TestOnly\Page;

use Dynamic\Foxy\Extension\Purchasable;
use Dynamic\Foxy\Inventory\Extension\ProductExpirationManager;
use Dynamic\Foxy\Inventory\Extension\ProductInventoryManager;
use SilverStripe\Dev\TestOnly;

class TestProduct extends \Page implements TestOnly
{
    /**
     * @var array
     */
    private static $extensions = [
        Purchasable::class,
        ProductInventoryManager::class,
        ProductExpirationManager::class,
    ];
}
