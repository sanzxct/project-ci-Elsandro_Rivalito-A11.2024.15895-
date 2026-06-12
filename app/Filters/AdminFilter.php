<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
         // Do something here
        if(session()->get('role') != 'admin') {
            return redirect()->to(site_url('/'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
         // Do something here
    }
}