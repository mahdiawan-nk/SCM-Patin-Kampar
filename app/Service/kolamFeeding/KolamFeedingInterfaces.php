<?php

namespace App\Service\kolamFeeding;

interface KolamFeedingInterfaces
{
    public function indexKolamFeeding();
    public function storeKolamFeeding($request);
    public function showKolamFeeding($id);
    public function updateKolamFeeding($request, $id);
    public function trashKolamFeeding($id);
    public function restoreKolamFeeding($id);
    public function destroyKolamFeeding($id);
    public function replicate($id, $jml = 1);
    public function createFaker($jml = 1);
}
