<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationPostRequest;
use App\Http\Resources\ReservationResource;
use App\Jobs\ProcessReservationUpdate;
use App\Models\Reservation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ResourceCollection
    {
        $reservations = Reservation::all();

        return ReservationResource::collection($reservations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReservationPostRequest $request): JsonResource
    {
        /** @var Reservation $reservation */
        $reservation = Reservation::create($request->all());

        return new ReservationResource($reservation->refresh());
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation): JsonResource
    {
        return new ReservationResource($reservation);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ReservationPostRequest $request, Reservation $reservation): JsonResource
    {
        $saved = $reservation->update($request->all());

        ProcessReservationUpdate::dispatchIf($saved, $reservation->refresh())
            ->onQueue('reservations');

        return new ReservationResource($reservation);
    }
}
