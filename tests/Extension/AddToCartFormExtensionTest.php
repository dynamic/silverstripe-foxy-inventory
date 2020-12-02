<?php

namespace Dynamic\Foxy\Inventory\Test\Extension;

use Dynamic\Foxy\Extension\Purchasable;
use Dynamic\Foxy\Extension\PurchasableExtension;
use Dynamic\Foxy\Form\AddToCartForm;
use Dynamic\Foxy\Inventory\Extension\AddToCartFormExtension;
use Dynamic\Foxy\Inventory\Extension\ProductExpirationManager;
use Dynamic\Foxy\Inventory\Extension\ProductInventoryManager;
use Dynamic\Foxy\Inventory\Test\TestOnly\Form\TestAddToCartForm;
use Dynamic\Foxy\Inventory\Test\TestOnly\Page\TestProduct;
use Dynamic\Foxy\Inventory\Test\TestOnly\Page\TestProductController;
use Dynamic\Foxy\Model\Variation;
use SilverStripe\Core\Config\Config;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\FieldList;

class AddToCartFormExtensionTest extends SapphireTest
{
    /**
     * @var string
     */
    protected static $fixture_file = '../fixtures.yml';

    /**
     * @var array
     */
    protected static $extra_dataobjects = [
        TestProduct::class,
    ];

    /**
     * @var array
     */
    protected static $required_extensions = [
        AddToCartForm::class => [
            AddToCartFormExtension::class,
        ],
        TestProduct::class => [
            Purchasable::class,
            ProductInventoryManager::class,
            ProductExpirationManager::class,
        ],
        TestProductController::class => [
            PurchasableExtension::class,
        ]
    ];

    /**
     * @var array
     */
    protected static $extra_controllers = [
        TestProductController::class,
    ];

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();

        Config::modify()->set('Dynamic\\Foxy\\SingleSignOn\\Client\\CustomerClient', 'foxy_sso_enabled', false);
        Config::modify()->set(Variation::class, 'has_one', ['TestProduct' => TestProduct::class]);
    }

    /**
     *
     */
    public function testUpdateProductFields()
    {
        $object = singleton(TestProduct::class);
        $controller = TestProductController::create($object);
        $form = AddToCartForm::create($controller, __FUNCTION__, null, null, null, $controller->data());
        $fields = $form->Fields();
        $this->assertInstanceOf(FieldList::class, $fields);
        //$this->assertNotNull($fields->dataFieldByName('expires'));

        // todo: add assertions to cover isOutOfStock() check via fixtures
    }

    /**
     *
     */
    public function testUpdateProductActions()
    {
        $object = singleton(TestProduct::class);
        $object->Available = 1;
        $controller = TestProductController::create($object);
        $form = $controller->AddToCartForm();
        $fields = $form->Actions();
        $this->assertInstanceOf(FieldList::class, $fields);

        // todo: add assertions to cover isOutOfStock() check via fixtures
    }

    /**
     *
     */
    public function testIsOutOfStock()
    {
        $this->markTestSkipped();
        // todo: write test to test out of stock via fixtures
    }
}
