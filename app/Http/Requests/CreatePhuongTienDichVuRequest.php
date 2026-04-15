<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePhuongTienDichVuRequest extends FormRequest
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
            'id_dichvu'  => 'required|string',
            'anh'         => 'array|max:3',
            'anh.*'       => 'file|mimes:jpg,jpeg,png,webp|max:5120',
            'video'       => 'array|max:2',
            'video.*'     => 'file|mimes:mp4,mov,avi|max:51200',
        ];
    }
    public function messages()
    {
        return [
            'id_dich_vu.required'=>'Mã dịch vụ bị rỗng!',
            'id_dich_vu.string'=>'Mã dịch vụ phải là chuỗi!',

            'anh.array'=>'Hình ảnh phải là mảng!',
            'anh.max'=>'Tối đa 3 hình ảnh!',

            'anh.*.file'=>'Hình ảnh phải là file!',
            'anh.*.mimes'=>'Hình ảnh phải là dạng jpg,jpeg,png,webp!',
            'anh.*.max'=>'Hình ảnh quá lớn!',

            'video.array'=>'Video phải là mảng!',
            'video.max'=>'Tối đa 2 video!',

            'video.*.file'=>'Video phải là file!',
            'video.*.mimes'=>'Video phải là dạng mp4,mov,avi!',
            'video.*.max'=>'Video quá lớn!',
        ];
    }
}
