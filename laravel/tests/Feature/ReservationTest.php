<?php

namespace Tests\Feature;

use App\Models\Reservation;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    const RESERVATION_JSON_STRUCTURE = [
        'id',
        'date_arrival',
        'date_departure',
        'time_arrival',
        'time_departure',
        'status',
        'created_at',
        'updated_at'
    ];

    const BASE_DATA_STRUCTURE = [
        'data' => self::RESERVATION_JSON_STRUCTURE
    ];
    const BASE_DATA = [
        'date_arrival' => '2023-03-19',
        'date_departure' => '2023-03-20',
        'time_arrival' => '18:00',
        'time_departure' => '12:00',
    ];

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_get_reservation(): void
    {
        $reservationId = Reservation::get()->random()->id;
        $this->get('/api/reservations/' . $reservationId)
            ->assertStatus(200)
            ->assertJsonStructure(self::BASE_DATA_STRUCTURE);
    }

    public function test_create_reservation(): void
    {
        $this->post('/api/reservations/', self::BASE_DATA)->assertStatus(201)
            ->assertJsonStructure(self::BASE_DATA_STRUCTURE);
    }

    public function test_get_all_reservations(): void
    {
        $this->get('/api/reservations/')
            ->assertStatus(200)
            ->assertJsonStructure([
            'data' => [
                '*' => self::RESERVATION_JSON_STRUCTURE,
            ],
        ]);
    }

    public function test_update_reservation(): void
    {
        $reservationId = Reservation::get()->random()->id;
        $this->patch('/api/reservations/' . $reservationId, [
            ...self::BASE_DATA, ['status' => 'paid']
        ])
            ->assertStatus(200)
            ->assertJsonStructure(self::BASE_DATA_STRUCTURE);
    }
}
