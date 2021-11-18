<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleDealerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'dealer_zone' => 'required',
                    'dealer_area' => 'required',
                    'dealer_dlr' => 'required',
                    'dealer_name' => 'required',
                    'dealer_vip' => 'required',
                    'dealer_press' => 'required',
                    'dealer_weekday' => 'required',
                    'dealer_weekend' => 'required'
                ];
            }
            default:
                break;
        }
    }
}
