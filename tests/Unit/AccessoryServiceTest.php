<?php

namespace Tests\Unit;

use Tests\TestCase;

class AccessoryServiceTest extends TestCase
{
    public function test_get_compatible_with_filters_by_bike_type(): void
    {
        $accessories = [
            ['AccessoryID' => 1, 'Name' => 'Water Bottle', 'CompatibleWith' => ['beach', 'all'], 'StockCount' => 10, 'UnitPrice' => 9.99],
            ['AccessoryID' => 2, 'Name' => 'Helmet', 'CompatibleWith' => ['mountain'], 'StockCount' => 5, 'UnitPrice' => 29.99],
        ];

        $repo = $this->createMock(\AccessoryRepository::class);
        $repo->method('getAll')->willReturn($accessories);

        $service = new \AccessoryService($repo);

        $result = $service->getCompatibleWith('beach');

        $this->assertCount(1, $result);
        $this->assertSame('Water Bottle', $result[0]['Name']);
    }

    public function test_process_order_applies_bundle_discount_and_saves_stock(): void
    {
        $accessories = [
            ['AccessoryID' => 1, 'Name' => 'Water Bottle', 'StockCount' => 5, 'UnitPrice' => 10.00, 'CompatibleWith' => ['beach', 'all']],
            ['AccessoryID' => 3, 'Name' => 'Bike Light', 'StockCount' => 5, 'UnitPrice' => 15.00, 'CompatibleWith' => ['mountain', 'all']],
        ];

        $repo = $this->createMock(\AccessoryRepository::class);
        $repo->method('getAll')->willReturn($accessories);
        $repo->expects($this->once())->method('save')->with($this->callback(function ($savedAccessories) {
            return $savedAccessories[0]['StockCount'] === 4 && $savedAccessories[1]['StockCount'] === 4;
        }));

        $service = new \AccessoryService($repo);
        $result = $service->processOrder([
            ['AccessoryID' => 1, 'Quantity' => 1],
            ['AccessoryID' => 3, 'Quantity' => 1],
        ]);

        $this->assertTrue($result['Success']);
        $this->assertSame(22.50, $result['TotalPrice']);
        $this->assertSame(2.50, $result['DiscountAmount']);
        $this->assertTrue($result['BundleDiscountApplied']);
    }

    public function test_process_order_returns_error_when_stock_is_insufficient(): void
    {
        $accessories = [
            ['AccessoryID' => 2, 'Name' => 'Helmet', 'StockCount' => 1, 'UnitPrice' => 29.99, 'CompatibleWith' => ['mountain']],
        ];

        $repo = $this->createMock(\AccessoryRepository::class);
        $repo->method('getAll')->willReturn($accessories);
        $repo->expects($this->never())->method('save');

        $service = new \AccessoryService($repo);
        $result = $service->processOrder([
            ['AccessoryID' => 2, 'Quantity' => 2],
        ]);

        $this->assertFalse($result['Success']);
        $this->assertStringContainsString('Not enough stock', $result['Message']);
    }
}
