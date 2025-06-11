<?php
declare(strict_types=1);

namespace App\Models;

class Company extends BaseModel
{
    protected string $table = 'companies';
    protected array $fillable = [
        'company_name',
        'industry',
        'website_url',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'notes',
        'status'
    ];
    
    public function searchCompanies(string $searchTerm = '', string $orderBy = 'company_name', string $orderDir = 'ASC'): array
    {
        if (trim($searchTerm) !== '') {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE company_name LIKE ? 
                    OR industry LIKE ? 
                    OR city LIKE ? 
                    OR country LIKE ?
                    OR website_url LIKE ?";
            
            $orderDir = strtoupper($orderDir) === 'DESC' ? 'DESC' : 'ASC';
            $sql .= " ORDER BY {$orderBy} {$orderDir}";
            
            $searchParam = "%{$searchTerm}%";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('sssss', $searchParam, $searchParam, $searchParam, $searchParam, $searchParam);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $rows = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $stmt->close();
            
            return $rows;
        }
        
        return $this->findAll([], $orderBy, $orderDir);
    }
    
    public function getCompanyWithContacts(int $id): ?array
    {
        $company = $this->find($id);
        if (!$company) {
            return null;
        }
        
        $contactModel = new Contact();
        $contacts = $contactModel->findAll(['company_id' => $id]);
        $company['contacts'] = $contacts;
        
        return $company;
    }
}