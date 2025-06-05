<?php

namespace App\Service\kolamGrowth;

interface KolamGrowthInterfaces
{
    public function indexKolamGrowth();
    public function storeKolamGrowth($request);
    public function showKolamGrowth($id);
    public function updateKolamGrowth($request, $id);
    public function trashKolamGrowth($id);
    public function restoreKolamGrowth($id);
    public function destroyKolamGrowth($id);
    public function replicate($id, $jml = 1);
    public function createFaker($jml = 1);
}
