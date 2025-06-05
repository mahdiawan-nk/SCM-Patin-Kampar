<?php

namespace App\Service\kolamTreatment;

interface KolamTreatmentIntefaces
{
    public function indexKolamTreatment();
    public function storeKolamTreatment($request);
    public function showKolamTreatment($id);
    public function updateKolamTreatment($request, $id);
    public function trashKolamTreatment($id);
    public function restoreKolamTreatment($id);
    public function destroyKolamTreatment($id);
    public function replicate($id, $jml = 1);
    public function createFaker($jml = 1);
}
