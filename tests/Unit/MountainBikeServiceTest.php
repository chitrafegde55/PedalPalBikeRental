<?php

namespace Tests\Unit;

use Tests\TestCase;

class MountainBikeServiceTest extends TestCase
{
    public function test_get_all_returns_repository_data(): void
    {
        $repo = $this->createMock(\MountainBikeRepository::class);
        $repo->method('getAll')->willReturn([
            ['BikeID' => 101, 'ModelName' => 'TrailBlazer', 'Brand' => 'ApexRide', 'GearCount' => 21, 'SuspensionType' => 'Full', 'FrameMaterial' => 'Aluminum', 'DailyRate' => 24.99, 'IsAvailable' => true, 'Terrain' => 'All-Mountain', 'WeightKg' => 13.5],
        ]);

        $service = new \MountainBikeService($repo);
        $result = $service->getAll();

        $this->assertCount(1, $result);
        $this->assertSame('TrailBlazer', $result[0]['ModelName']);
    }

    public function test_rent_bike_marks_available_bike_as_rented(): void
    {
        $bikes = [
            ['BikeID' => 101, 'ModelName' => 'TrailBlazer', 'Brand' => 'ApexRide', 'GearCount' => 21, 'SuspensionType' => 'Full', 'FrameMaterial' => 'Aluminum', 'DailyRate' => 24.99, 'IsAvailable' => true, 'Terrain' => 'All-Mountain', 'WeightKg' => 13.5],
        ];

        $repo = $this->createMock(\MountainBikeRepository::class);
        $repo->method('getAll')->willReturn($bikes);
        $repo->expects($this->once())->method('save')->with($this->callback(function ($savedBikes) {
            return isset($savedBikes[0]['IsAvailable']) && $savedBikes[0]['IsAvailable'] === false;
        }));

        $service = new \MountainBikeService($repo);

        $this->assertTrue($service->rentBike(101));
    }

    public function test_rent_bike_returns_false_when_bike_is_unavailable(): void
    {
        $bikes = [
            ['BikeID' => 101, 'ModelName' => 'TrailBlazer', 'Brand' => 'ApexRide', 'GearCount' => 21, 'SuspensionType' => 'Full', 'FrameMaterial' => 'Aluminum', 'DailyRate' => 24.99, 'IsAvailable' => false, 'Terrain' => 'All-Mountain', 'WeightKg' => 13.5],
        ];

        $repo = $this->createMock(\MountainBikeRepository::class);
        $repo->method('getAll')->willReturn($bikes);
        $repo->expects($this->never())->method('save');

        $service = new \MountainBikeService($repo);

        $this->assertFalse($service->rentBike(101));
    }
}
