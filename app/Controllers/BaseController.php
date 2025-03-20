<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $db;
    protected $eservicesdb;
    protected $request;
    protected $session;
    protected $security;
    protected $validation;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['url','form','functions','common'];

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->db = \Config\Database::connect();
        $this->eservicesdb = \Config\Database::connect('eservices');
        $this->session = \Config\Services::session();
        $this->session->start();
        $this->security = \Config\Services::security();
        $this->validation = \Config\Services::validation();
        date_default_timezone_set('Asia/Calcutta');
        if (isset($_SESSION['login'])) {
            //header('Location:'.base_url('Signout'));exit();
        }else {
            header('Location:'.base_url('Signout'));exit();
        }
    }
}
