<?php

namespace App\Http\Requests;

use App\Enums\ReservationStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ReservationPostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'date_arrival' => ['required', 'date_format:Y-m-d'],
            'date_departure' => ['required', 'date_format:Y-m-d', 'after_or_equal:date_arrival'],
            'time_arrival' => ['required', 'date_format:H:i'],
            'time_departure' => ['required', 'date_format:H:i'],
            'status' => [new Enum(ReservationStatus::class)],
        ];
    }
}
