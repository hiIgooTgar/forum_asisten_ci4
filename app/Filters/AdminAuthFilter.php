<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if (!$session->get('is_admin_logged_in') || !$session->get('admin_id')) {
            $session->destroy();
            return redirect()->to(base_url('auth/login-admin'))
                ->with('error', 'Akses ditolak. Silakan login sebagai administrator.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
