<?php
declare(strict_types=1);

namespace App\Models;

class Contact extends BaseModel
{
    protected string $table = 'contacts';
    protected array $fillable = [
        'first_name',
        'last_name', 
        'email',
        'phone',
        'company_id',
        'position',
        'notes',
        'status'
    ];
    
    public function searchContacts(string $searchTerm = '', string $orderBy = 'first_name', string $orderDir = 'ASC'): array
    {
        $conditions = [];
        if (trim($searchTerm) !== '') {
            $sql = "SELECT c.*, comp.company_name 
                    FROM {$this->table} c 
                    LEFT JOIN companies comp ON c.company_id = comp.id 
                    WHERE c.first_name LIKE ? 
                    OR c.last_name LIKE ? 
                    OR c.email LIKE ? 
                    OR c.phone LIKE ?
                    OR comp.company_name LIKE ?";
            
            $orderDir = strtoupper($orderDir) === 'DESC' ? 'DESC' : 'ASC';
            $sql .= " ORDER BY c.{$orderBy} {$orderDir}";
            
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
        
        $sql = "SELECT c.*, comp.company_name 
                FROM {$this->table} c 
                LEFT JOIN companies comp ON c.company_id = comp.id 
                ORDER BY c.{$orderBy} {$orderDir}";
        
        $result = $this->db->query($sql);
        $rows = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        
        return $rows;
    }
    
    public function getContactsWithCompany(): array
    {
        $sql = "SELECT c.*, comp.company_name 
                FROM {$this->table} c 
                LEFT JOIN companies comp ON c.company_id = comp.id 
                ORDER BY c.first_name ASC";
        
        $result = $this->db->query($sql);
        $rows = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        
        return $rows;
    }
}