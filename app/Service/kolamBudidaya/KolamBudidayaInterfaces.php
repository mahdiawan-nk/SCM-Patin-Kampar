<?php

namespace App\Service\kolamBudidaya;

interface KolamBudidayaInterfaces
{
    public function indexKolamBudidaya();
    public function storeKolamBudidaya($request);
    public function showKolamBudidaya($id);
    public function updateKolamBudidaya($request, $id);
    public function trashKolamBudidaya($id);
    public function restoreKolamBudidaya($id);
    public function destroyKolamBudidaya($id);
    public function replicate($id, $jml = 1);
    public function createFaker($jml = 1,$param);
}
