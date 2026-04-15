<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateKhuyenMaiRequest extends FormRequest
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
	        'tenkhuyenmai'    => 'required|string|max:255',
	        'thoigian_apdung' => 'required|date',
	        'thoigian_ketthuc'=> 'required|date|after:thoigian_apdung',
	        'giatri'          => 'required',
	        'loai'            => 'required',
	        'mota'            => 'required',
	    ];
    }
    public function messages()
    {
       return [
	        'tenkhuyenmai.required'     => 'Tên khuyến mãi không được để trống!',
	        'tenkhuyenmai.string'       => 'Tên khuyến mãi phải là chuỗi!',
	        'tenkhuyenmai.max'          => 'Tên khuyến mãi quá dài!',
	
	        'thoigian_apdung.required'        => 'Thời gian áp dụng không được để trống!',
	        'thoigian_apdung.date'            => 'Thời gian áp dụng phải là kiểu ngày!',
	
	        'thoigian_ketthuc.required' => 'Thời gian kết thúc không được để trống!',
	        'thoigian_ketthuc.date'     => 'Thời gian kết thúc phải là kiểu ngày!',
	        'thoigian_ketthuc.after'    => 'Thời gian kết thúc phải sau thời gian áp dụng!',
	
	        'giatri.required' => 'Giá trị khuyến mãi không được để trống!',
	        'loai.required'   => 'Loại khuyến mãi không được để trống!',
	        'mota.required'   => 'Mô tả không được để trống!',
	    ];
    }
}
