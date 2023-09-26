<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use SKAgarwal\GoogleApi\Exceptions\InvalidRequestException;
use Symfony\Component\HttpFoundation\Response;

class GooglePlaceExists implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (empty($value)) {
            $value = '';
        }

        try {
            app('GooglePlaces')->placeDetails($value);
        } catch (InvalidRequestException $e) {
            return false;
        } catch (\Exception $e) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.place_id');
    }
}
