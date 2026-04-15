<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateLoaiDichVuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tenloai'=>'required|string|max:255'
        ];
    }
    public function messages()
    {
        return [
            'tenloai.required'=>'Tên loại dịch vụ không được để trống!',
            'tenloai.string'=>'Tên loại dịch vụ phải là chuỗi ký tự!',
            'tenloai.max'=>'Tên loại dịch vụ quá dài! Tên loại dịch vụ không được vượt quá 255 ký tự!',
        ];
    }
}
