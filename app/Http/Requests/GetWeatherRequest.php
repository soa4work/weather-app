<?php

namespace App\Http\Requests;

use App\DTO\GeoLocation;
use App\Enums\Units;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use JetBrains\PhpStorm\ArrayShape;
use Illuminate\Validation\Rules\In;
use JetBrains\PhpStorm\Pure;

class GetWeatherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[Pure] #[ArrayShape(['city' => "string", 'longitude' => "string", 'latitude' => "string", 'units' => In::class])]
    public function rules(): array
    {
        return [
            'city' => 'required_without:longitude,latitude|max:30',
            'longitude' => 'required_with:latitude|required_without:city|numeric|between:-180,180',
            'latitude' => 'required_with:longitude|required_without:city|numeric|between:-90,90',
            'units' => [new Enum(Units::class)],
        ];
    }

    public function getLocationParams(): ?GeoLocation
    {
        $locationParams = $this->safe()->only(['longitude', 'latitude']);
        if ($locationParams) {
            return new GeoLocation($locationParams['longitude'], $locationParams['latitude']);
        }
        return null;
    }
}
