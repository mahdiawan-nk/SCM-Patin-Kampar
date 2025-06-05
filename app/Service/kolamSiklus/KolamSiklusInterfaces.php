<?php

namespace App\Service\kolamSiklus;

interface KolamSiklusIntefaces
{
    public function indexKolamSiklus();
    public function storeKolamSiklus($request);
    public function showKolamSiklus($id);
    public function updateKolamSiklus($request, $id);
    public function trashKolamSiklus($id);
    public function restoreKolamSiklus($id);
    public function destroyKolamSiklus($id);
    public function replicate($id, $jml = 1);
    public function createFaker($jml = 1);
}
