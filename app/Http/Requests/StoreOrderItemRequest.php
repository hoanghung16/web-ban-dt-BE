<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderItemRequest extends FormRequest
{
    public function authorize() { return true; }
    
    public function rules()
    {
        return [
            'orderid' => 'required|exists:orders,id',
            'productid' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:100',
            'unitprice' => 'required|numeric|min:0',
        ];
    }
    
    public function withValidator($validator)
    {
        // Custom validation: Check inventory trước khi tạo OrderItem
        $validator->after(function ($validator) {
            $inventory = \App\Models\Inventory::where('ProductId', $this->productid)->first();
            
            if (!$inventory || $inventory->QuantityInStock < $this->quantity) {
                $validator->errors()->add(
                    'quantity',
                    'Hàng trong kho không đủ. Có ' . ($inventory->QuantityInStock ?? 0) . ' cái.'
                );
            }
        });
    }
}