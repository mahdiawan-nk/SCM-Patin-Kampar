<?php

namespace App\Service\Pembudiaya;

interface PemudidayaInterfaces
{
    public function indexPembudidaya();
    public function storePembudidaya($request);
    public function showPembudidaya($id);
    public function updatePembudidaya($request, $id);
    public function trashPembudidaya($id);
    public function restorePembudidaya($id);
    public function destroyPembudidaya($id);
    public function replicate($id, $jml = 1);
    public function createFaker($jml = 1);
}
