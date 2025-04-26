<?php

namespace App\Repositories;

use App\Interfaces\VendorOrderRepositoryInterface;
use App\Models\VendorOrder;

class VendorOrderRepository extends BaseRepository implements VendorOrderRepositoryInterface 
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'is_fulfilled'
    ];

    /**
     * Get searchable fields
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return VendorOrder::class;
    }
    
    public function getAllVendorOrders() 
    {
        return VendorOrder::all();
    }

    public function getVendorOrderById($VendorOrderId) 
    {
        return VendorOrder::findOrFail($VendorOrderId);
    }

    public function deleteVendorOrder($VendorOrderId) 
    {
        VendorOrder::destroy($VendorOrderId);
    }

    public function createVendorOrder(array $VendorOrderDetails) 
    {
        return VendorOrder::create($VendorOrderDetails);
    }

    public function updateVendorOrder($VendorOrderId, array $newDetails) 
    {
        return VendorOrder::whereId($VendorOrderId)->update($newDetails);
    }

    public function getFulfilledVendorOrders() 
    {
        return VendorOrder::where('is_fulfilled', true);
    }
}