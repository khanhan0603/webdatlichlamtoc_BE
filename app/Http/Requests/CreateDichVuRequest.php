<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDichVuRequest extends FormRequest
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
            'tendichvu'=>'required|string|max:100',
            'dongia'=>'required',
            'mota'=>'required',
            'id_loaidichvu'=>'required|exists:loai_dich_vus,id'
        ];
    }
    public function messages() 
    {
        return [
            'tendichvu.required'=>'Tên dịch vụ không được để trống!',
            'tendichvu.string'=>'Tên dịch vụ phải là chuỗi ký tự!',
            'tendichvu.max'=>'Tên dịch vụ quá dài! Tên dịch vụ không được vượt quá 100 ký tự!',

            'dongia.required'=>'Giá dịch vụ không được để trống!',

            'mota.required'=>'Mô tả dịch vụ là bắt buộc',

            'id_loaidichvu.required'=>'Vui lòng chọn lớp dịch vụ!',
            'id_loaidichvu.exists'=>'Lớp dịch vụ không tìm thấy!'
        ];
    }
}
