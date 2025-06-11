<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Contact;
use App\Models\Company;
use Exception;

class ContactController
{
    private Contact $contactModel;
    private Company $companyModel;

    public function __construct()
    {
        $this->contactModel = new Contact();
        $this->companyModel = new Company();
    }

    public function listContacts(string $search = '', string $orderBy = 'first_name', string $orderDir = 'asc'): array
    {
        return $this->contactModel->searchContacts($search, $orderBy, $orderDir);
    }

    public function getContactById(int $id): ?array
    {
        $contact = $this->contactModel->find($id);
        if ($contact && $contact['company_id']) {
            $company = $this->companyModel->find($contact['company_id']);
            $contact['company'] = $company;
        }
        return $contact;
    }

    public function createContact(array $data): void
    {
        // Validate required fields
        $required = ['first_name', 'last_name', 'email'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("Field {$field} is required");
            }
        }
        
        // Validate email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        
        // Set default status if not provided
        if (!isset($data['status'])) {
            $data['status'] = 'active';
        }
        
        $this->contactModel->create($data);
    }

    public function updateContact(int $id, array $data): void
    {
        if (!$this->contactModel->find($id)) {
            throw new Exception("Contact not found");
        }
        
        // Validate email if provided
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        
        $this->contactModel->update($id, $data);
    }

    public function deleteContact(int $id): void
    {
        if (!$this->contactModel->find($id)) {
            throw new Exception("Contact not found");
        }
        
        $this->contactModel->delete($id);
    }
    
    public function bulkDeleteContacts(array $ids): void
    {
        $this->contactModel->bulkDelete($ids);
    }
    
    public function getContactsWithCompanies(): array
    {
        return $this->contactModel->getContactsWithCompany();
    }
}