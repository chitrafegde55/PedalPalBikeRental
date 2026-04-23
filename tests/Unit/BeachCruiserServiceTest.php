<?php

namespace Tests\Unit;

use Tests\TestCase;

class BeachCruiserServiceTest extends TestCase
{
    public function test_get_all_returns_repository_data(): void
    {
        $repo = $this->createMock(\BeachCruiserRepository::class);
        $repo->method('getAll')->willReturn([
            ['bike_id' => 1, 'model_name' => 'Sunset', 'color' => 'Coral', 'frame_size' => 'Medium', 'daily_rate' => 14.99, 'is_available' => true],
        ]);

        $service = new \BeachCruiserService($repo);
        $result = $service->getAll();

        $this->assertCount(1, $result);
        $this->assertSame('Sunset', $result[0]['model_name']);
    }

    public function test_rent_bike_marks_available_bike_as_rented(): void
    {
        $bikes = [
            ['bike_id' => 1, 'model_name' => 'Sunset', 'color' => 'Coral', 'frame_size' => 'Medium', 'daily_rate' => 14.99, 'is_available' => true],
        ];

        $repo = $this->createMock(\BeachCruiserRepository::class);
        $repo->method('getAll')->willReturn($bikes);
        $repo->expects($this->once())->method('save')->with($this->callback(function ($savedBikes) {
            return isset($savedBikes[0]['is_available']) && $savedBikes[0]['is_available'] === false;
        }));

        $service = new \BeachCruiserService($repo);

        $this->assertTrue($service->rentBike(1));
    }

    public function test_rent_bike_returns_false_when_bike_is_unavailable(): void
    {
        $bikes = [
            ['bike_id' => 1, 'model_name' => 'Sunset', 'color' => 'Coral', 'frame_size' => 'Medium', 'daily_rate' => 14.99, 'is_available' => false],
        ];

        $repo = $this->createMock(\BeachCruiserRepository::class);
        $repo->method('getAll')->willReturn($bikes);
        $repo->expects($this->never())->method('save');

        $service = new \BeachCruiserService($repo);

        $this->assertFalse($service->rentBike(1));
    }
}
