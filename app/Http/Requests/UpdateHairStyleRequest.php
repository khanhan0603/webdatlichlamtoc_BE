<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHairStyleRequest extends FormRequest
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
            'hoten'=>'required|string|max:255',
            'anh'=>'image',
            'mota'=>'required',
            'id_salon'=>'required|exists:salons,id'
        ];
    }

    public function messages()
    {
        return [
            'hoten.required'=>'Họ tên không được để trống!',
            'hoten.string'=>'Họ tên phải là chuỗi!',
            'hoten.max'=>'Họ tên quá dài! Họ tên không được vượt quá 255 ký tự!',

            'anh.image'=>'Ảnh phải là ảnh!',

            'mota.required'=>'Mô tả là bắt buộc!',

            'id_salon'=>'Vui lòng chọn salon!',
            'id_salon.exists'=>'Salon không tìm thấy!'
        ];
    }
}
