<?php
// index.php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';
include __DIR__ . '/src/middleware/auth.php';

use App\Controllers\ContactController;
use App\Controllers\CompanyController;

// 1) Instantiate controllers
$contactCtrl = new ContactController();
$companyCtrl = new CompanyController();

// 2) Define routes: action => [ controller|null, method|null, view|null ]
$routes = [
    // Contacts
    'list'        => [ $contactCtrl, 'listContacts',    'contacts.php'   ],
    'edit'        => [ $contactCtrl, 'getContactById',   'editContact.php' ],
    'update'      => [ $contactCtrl, 'updateContact',    null              ],
    'create'      => [ $contactCtrl, 'createContact',    null              ],
    'delete'      => [ $contactCtrl, 'deleteContact',    null              ],

    // Companies
    'listCompanies'       => [ $companyCtrl, 'listCompanies',      'companies.php'      ],
    'viewCompany'         => [ $companyCtrl, 'getCompanyById',      'company_profile.php'],
    'createCompany'       => [ $companyCtrl, 'createCompany',       null                 ],
    'editCompany'         => [ $companyCtrl, 'getCompanyById',      'editCompany.php'    ],
    'updateCompany'       => [ $companyCtrl, 'updateCompany',       null                 ],
    'deleteCompany'       => [ $companyCtrl, 'deleteCompany',       null                 ],
    'bulkDeleteCompanies' => [ $companyCtrl, 'bulkDeleteCompanies', null                 ],
    'inlineEditCompany'   => [ $companyCtrl, 'inlineEditCompany',   null                 ],

    // Blank view‐only page
    'blank'       => [ null,           null,                  'blank.php'        ],
    'dashboard'       => [ null,           null,                  'dashboard.php'        ],
];

// 3) Determine action
$action = $_GET['action'] ?? 'list';
if (! isset($routes[$action])) {
    http_response_code(404);
    exit('Page not found');
}

list($controller, $method, $view) = $routes[$action];

// 4) If it's a view‐only route (no controller), just include and exit
if (is_null($controller)) {
    include __DIR__ . '/src/views/' . $view;
    exit;
}

// 5) Build params for controller call
$params = [];
switch ($action) {
    // Contacts
    case 'list':
        $params = [
            $_GET['search'] ?? '',
            $_GET['sort']   ?? 'first_name',
            $_GET['dir']    ?? 'asc',
        ];
        break;
    case 'edit':
    case 'delete':
        $params = [ (int)($_GET['id'] ?? 0) ];
        break;
    case 'update':
        $params = [
            (int)($_POST['id'] ?? 0),
            $_POST
        ];
        break;
    case 'create':
        $params = [ $_POST ];
        break;

    // Companies
    case 'listCompanies':
        $params = [
            $_GET['search'] ?? '',
            $_GET['sort']   ?? 'company_name',
            $_GET['dir']    ?? 'asc',
        ];
        break;
    case 'viewCompany':
    case 'editCompany':
    case 'deleteCompany':
        $params = [ (int)($_GET['id'] ?? 0) ];
        break;
    case 'createCompany':
        $params = [ $_POST ];
        break;
    case 'updateCompany':
        $params = [
            (int)($_POST['id'] ?? 0),
            $_POST
        ];
        break;
    case 'bulkDeleteCompanies':
        $params = [ $_POST['selected_ids'] ?? [] ];
        break;
    case 'inlineEditCompany':
        $params = [
            (int)($_POST['id']    ?? 0),
            $_POST['field']      ?? '',
            $_POST['value']      ?? ''
        ];
        break;
}

// 6) Call the controller method
$result = call_user_func_array([$controller, $method], $params);

// 7) Handle inline AJAX edit immediately
if ($action === 'inlineEditCompany') {
    echo $result ?? 'OK';
    exit;
}

// 8) If no view, it's a write action: redirect back to listing
if (is_null($view)) {
    $redirect = strpos($action, 'Company') !== false
        ? 'listCompanies'
        : 'list';
    header('Location: index.php?action=' . $redirect);
    exit;
}

// 9) Otherwise include the view
include __DIR__ . '/src/views/' . $view;
exit;
