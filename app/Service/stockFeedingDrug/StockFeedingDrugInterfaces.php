<?php

namespace App\Service\stockFeedingDrug;

interface StockFeedingDrugIntefaces
{
    public function indexStockFeedingDrug();
    public function storeStockFeedingDrug($request);
    public function showStockFeedingDrug($id);
    public function updateStockFeedingDrug($request, $id);
    public function trashStockFeedingDrug($id);
    public function restoreStockFeedingDrug($id);
    public function destroyStockFeedingDrug($id);
    public function replicate($id, $jml = 1);
    public function createFaker($jml = 1);
}
