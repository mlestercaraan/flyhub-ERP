<?php
// index.php
declare(strict_types=1);

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoload dependencies
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/config/database.php';

// Authentication middleware
include __DIR__ . '/src/middleware/auth.php';

use App\Controllers\ContactController;
use App\Controllers\CompanyController;
use App\Controllers\DashboardController;

try {
    // Initialize controllers
    $contactCtrl = new ContactController();
    $companyCtrl = new CompanyController();
    $dashboardCtrl = new DashboardController();

    // Define routes
    $routes = [
        // Dashboard
        'dashboard'           => [$dashboardCtrl, 'getDashboardStats',    'dashboard.php'],
        
        // Contacts
        'list'                => [$contactCtrl, 'listContacts',           'contacts.php'],
        'edit'                => [$contactCtrl, 'getContactById',         'editContact.php'],
        'update'              => [$contactCtrl, 'updateContact',          null],
        'create'              => [$contactCtrl, 'createContact',          null],
        'delete'              => [$contactCtrl, 'deleteContact',          null],
        'bulkDeleteContacts'  => [$contactCtrl, 'bulkDeleteContacts',     null],

        // Companies
        'listCompanies'       => [$companyCtrl, 'listCompanies',          'companies.php'],
        'viewCompany'         => [$companyCtrl, 'getCompanyById',         'company_profile.php'],
        'createCompany'       => [$companyCtrl, 'createCompany',          null],
        'editCompany'         => [$companyCtrl, 'getCompanyById',         'editCompany.php'],
        'updateCompany'       => [$companyCtrl, 'updateCompany',          null],
        'deleteCompany'       => [$companyCtrl, 'deleteCompany',          null],
        'bulkDeleteCompanies' => [$companyCtrl, 'bulkDeleteCompanies',    null],
        'inlineEditCompany'   => [$companyCtrl, 'inlineEditCompany',      null],

        // Static pages
        'blank'               => [null, null, 'blank.php'],
    ];

    // Get action from URL
    $action = $_GET['action'] ?? 'dashboard';
    
    if (!isset($routes[$action])) {
        http_response_code(404);
        include __DIR__ . '/src/views/404.php';
        exit;
    }

    list($controller, $method, $view) = $routes[$action];

    // Handle view-only routes
    if (is_null($controller)) {
        include __DIR__ . '/src/views/' . $view;
        exit;
    }

    // Build parameters for controller method
    $params = [];
    switch ($action) {
        // Dashboard
        case 'dashboard':
            $params = [];
            break;
            
        // Contacts
        case 'list':
            $params = [
                $_GET['search'] ?? '',
                $_GET['sort'] ?? 'first_name',
                $_GET['dir'] ?? 'asc',
            ];
            break;
        case 'edit':
        case 'delete':
            $params = [(int)($_GET['id'] ?? 0)];
            break;
        case 'update':
            $params = [(int)($_POST['id'] ?? 0), $_POST];
            break;
        case 'create':
            $params = [$_POST];
            break;
        case 'bulkDeleteContacts':
            $params = [$_POST['selected_ids'] ?? []];
            break;

        // Companies
        case 'listCompanies':
            $params = [
                $_GET['search'] ?? '',
                $_GET['sort'] ?? 'company_name',
                $_GET['dir'] ?? 'asc',
            ];
            break;
        case 'viewCompany':
        case 'editCompany':
        case 'deleteCompany':
            $params = [(int)($_GET['id'] ?? 0)];
            break;
        case 'createCompany':
            $params = [$_POST];
            break;
        case 'updateCompany':
            $params = [(int)($_POST['id'] ?? 0), $_POST];
            break;
        case 'bulkDeleteCompanies':
            $params = [$_POST['selected_ids'] ?? []];
            break;
        case 'inlineEditCompany':
            $params = [
                (int)($_POST['id'] ?? 0),
                $_POST['field'] ?? '',
                $_POST['value'] ?? ''
            ];
            break;
    }

    // Call controller method
    $result = call_user_func_array([$controller, $method], $params);

    // Handle AJAX responses
    if ($action === 'inlineEditCompany') {
        echo $result ?? 'OK';
        exit;
    }

    // Handle redirects for write operations
    if (is_null($view)) {
        $redirect = match($action) {
            'create', 'update', 'delete', 'bulkDeleteContacts' => 'list',
            'createCompany', 'updateCompany', 'deleteCompany', 'bulkDeleteCompanies' => 'listCompanies',
            default => 'dashboard'
        };
        header('Location: index.php?action=' . $redirect);
        exit;
    }

    // Include the view
    include __DIR__ . '/src/views/' . $view;

} catch (Exception $e) {
    error_log("Application Error: " . $e->getMessage());
    http_response_code(500);
    include __DIR__ . '/src/views/error.php';
}