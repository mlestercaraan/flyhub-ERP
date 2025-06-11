<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Contact;
use App\Models\Company;

class DashboardController
{
    private Contact $contactModel;
    private Company $companyModel;

    public function __construct()
    {
        $this->contactModel = new Contact();
        $this->companyModel = new Company();
    }

    public function getDashboardStats(): array
    {
        $totalContacts = count($this->contactModel->findAll());
        $totalCompanies = count($this->companyModel->findAll());
        
        // Get recent contacts (last 5)
        $recentContacts = $this->contactModel->findAll([], 'id', 'DESC');
        $recentContacts = array_slice($recentContacts, 0, 5);
        
        // Get recent companies (last 5)
        $recentCompanies = $this->companyModel->findAll([], 'id', 'DESC');
        $recentCompanies = array_slice($recentCompanies, 0, 5);
        
        return [
            'total_contacts' => $totalContacts,
            'total_companies' => $totalCompanies,
            'recent_contacts' => $recentContacts,
            'recent_companies' => $recentCompanies
        ];
    }
}