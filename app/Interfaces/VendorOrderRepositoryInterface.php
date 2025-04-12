<?php

namespace App\Interfaces;

interface VendorOrderRepositoryInterface 
{
    public function getAllVendorOrders();
    public function getVendorOrderById($VendorOrderId);
    public function deleteVendorOrder($VendorOrderId);
    public function createVendorOrder(array $VendorOrderDetails);
    public function updateVendorOrder($VendorOrderId, array $newDetails);
    public function getFulfilledVendorOrders();
}