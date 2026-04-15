<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
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
        $userId=$this->route('id');

        return [
            'hoten'=>'string|max:255',
            'email'=>['email','max:255',Rule::unique('users','email')->ignore($userId)],
            'sodienthoai'=>['required','max:10',Rule::unique('users','sodienthoai')->ignore($userId)],
            'matkhau'=>'min:6'
        ];
    }

    public function messages()
    {
        return [
            'hoten.string'=>'Họ tên phải là chuỗi ký tự!',
            'hoten.max'=>'Họ tên quá dài! Họ tên không được vượt quá 255 ký tự!',

            'email.email'=>'Định dạng email không hợp lệ!',
            'email.max'=>'Email quá dài! Email không được vượt quá 255 ký tự!',
            'email.unique'=>'Email đã tồn tại trong hệ thống!',

            'sodienthoai.required'=>'Số diện thoại là bắt buộc!',
            'sodienthoai.max'=>'Số diện thoại không được vượt quá 10 ký tự!',
            'sodienthoai.unique'=>'Số diện thoại đã tồn tại trong hệ thống!',

            'matkhau.min'=>'Mật khẩu quá ngắn! Mật khẩu phải lớn hơn 6 ký tự!',
        ];
    }
}
