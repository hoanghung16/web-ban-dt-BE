<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'userid' => $this->userid,
            'user' => new UserResource($this->whenLoaded('user')), 
            'orderdate' => $this->orderdate,
            'status' => $this->status,
            'paymentstatus' => $this->paymentstatus,
            'totalprice' => (float) $this->totalprice, 
            'shipname' => $this->shipname,
            'shipaddress' => $this->shipaddress,
            'shipphone' => $this->shipphone,
            'items' => OrderItemResource::collection($this->whenLoaded('items')), 
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}