<?php

namespace App\Controllers;
use App\Models\ProductModel;


class Home extends BaseController
{
    protected $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    public function index(): string
    {
        return view('v_home', [
            'products' => $this->productModel->findAll()
        ]);
    }

    public function faq()
    {
        return view('v_faq.php');
    }

    public function contact()
    {
        return view('v_contact.php');
    }
    
}
