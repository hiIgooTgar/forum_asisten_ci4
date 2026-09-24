<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use DateTime;
use DateTimeZone;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;
    protected $helpers = ['security', 'form', 'url', 'date', 'text'];

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');
    }

    protected function logActivity(
        string $action,
        ?string $description = null,
        array $details = [],
        string $userType = 'student'
    ): void {
        $db     = \Config\Database::connect();
        $userId = session()->get('user_id') ?: session()->get('admin_id');

        $timezone    = new DateTimeZone('Asia/Jakarta');
        $dateTime    = new DateTime('now', $timezone);
        $currentTime = $dateTime->format('Y-m-d H:i:s');

        $logData = [
            'user_id'     => $userId ?: null,
            'user_type'   => $userType,
            'action'      => $action,
            'description' => $description,
            'details'     => !empty($details) ? json_encode($details) : null,
            'ip_address'  => $this->request->getIPAddress(),
            'user_agent'  => substr((string) $this->request->getUserAgent(), 0, 500),
            'created_at'  => $currentTime,
        ];

        $db->table('activity_logs')->insert($logData);
    }
}
