<?php
declare(strict_types=1);

/**
 * @author Volodymyr Artjukh
 * @copyright 2023 Volodymyr Artjukh (vladimir.artjukh@gmail.com)
 */

namespace Modules\API\V1\Tasks\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ApiFormRequest extends FormRequest
{
    /**
     * @param  Validator  $validator
     *
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        // all errors
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(response()->json([
            'errors' => $errors
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }

    /**
     * @return mixed
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rulesDefault()
    {
        return [
            'columns' => 'string|min:1',
            'order_by_feel' => 'string|min:2|max:191',
            'order_by_direction' => 'string|in:asc,desc',
            'search_value' => 'string|min:2|max:191',
            'search_feel' => 'string|min:2|max:191',
            'paginate' => 'integer|min:1',
            'limit' => 'integer|min:1'
        ];
    }


}
