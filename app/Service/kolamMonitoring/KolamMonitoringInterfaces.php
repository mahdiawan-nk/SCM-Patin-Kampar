<?php

namespace App\Service\kolamMonitoring;

interface KolamMonitoringInterfaces
{
    public function indexKolamMonitoring();
    public function storeKolamMonitoring($request);
    public function showKolamMonitoring($id);
    public function updateKolamMonitoring($request, $id);
    public function trashKolamMonitoring($id);
    public function restoreKolamMonitoring($id);
    public function destroyKolamMonitoring($id);
    public function replicate($id, $jml = 1);
    public function createFaker($jml = 1);
}
