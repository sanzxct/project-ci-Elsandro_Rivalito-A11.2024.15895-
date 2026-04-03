<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class KeranjangController extends BaseController
{
    public function keranjang()
    {
        return view('v_keranjang.php');
    }
}
