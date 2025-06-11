<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Company;
use Exception;

class CompanyController
{
    private Company $companyModel;

    public function __construct()
    {
        $this->companyModel = new Company();
    }

    public function listCompanies(string $searchTerm = '', string $orderBy = 'company_name', string $orderDir = 'asc'): array
    {
        return $this->companyModel->searchCompanies($searchTerm, $orderBy, $orderDir);
    }

    public function getCompanyById(int $id): ?array
    {
        return $this->companyModel->getCompanyWithContacts($id);
    }

    public function createCompany(array $data): void
    {
        // Validate required fields
        if (empty($data['company_name'])) {
            throw new Exception("Company name is required");
        }
        
        // Validate website URL if provided
        if (!empty($data['website_url']) && !filter_var($data['website_url'], FILTER_VALIDATE_URL)) {
            throw new Exception("Invalid website URL format");
        }
        
        // Validate email if provided
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        
        // Set default status if not provided
        if (!isset($data['status'])) {
            $data['status'] = 'active';
        }
        
        $this->companyModel->create($data);
    }

    public function updateCompany(int $id, array $data): void
    {
        if (!$this->companyModel->find($id)) {
            throw new Exception("Company not found");
        }
        
        // Validate website URL if provided
        if (!empty($data['website_url']) && !filter_var($data['website_url'], FILTER_VALIDATE_URL)) {
            throw new Exception("Invalid website URL format");
        }
        
        // Validate email if provided
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        
        $this->companyModel->update($id, $data);
    }

    public function deleteCompany(int $id): void
    {
        if (!$this->companyModel->find($id)) {
            throw new Exception("Company not found");
        }
        
        $this->companyModel->delete($id);
    }

    public function bulkDeleteCompanies(array $ids): void
    {
        $this->companyModel->bulkDelete($ids);
    }

    public function inlineEditCompany(int $id, string $field, string $value): string
    {
        try {
            $allowed = ['company_name', 'industry', 'city', 'country', 'website_url', 'phone', 'email'];
            if (!in_array($field, $allowed, true)) {
                throw new Exception('Invalid field for inline edit');
            }

            // Validate specific fields
            if ($field === 'email' && !empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Invalid email format');
            }
            
            if ($field === 'website_url' && !empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
                throw new Exception('Invalid URL format');
            }

            $this->companyModel->update($id, [$field => $value]);
            return 'OK';
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}