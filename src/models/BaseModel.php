<?php
declare(strict_types=1);

namespace App\Models;

use mysqli;
use Exception;

abstract class BaseModel
{
    protected mysqli $db;
    protected string $table;
    protected array $fillable = [];
    
    public function __construct()
    {
        $database = \Database::getInstance();
        $this->db = $database->getConnection();
    }
    
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        return $result ?: null;
    }
    
    public function findAll(array $conditions = [], string $orderBy = 'id', string $orderDir = 'ASC'): array
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        $types = '';
        
        if (!empty($conditions)) {
            $whereClause = [];
            foreach ($conditions as $field => $value) {
                if (is_array($value)) {
                    $placeholders = str_repeat('?,', count($value) - 1) . '?';
                    $whereClause[] = "{$field} IN ({$placeholders})";
                    $params = array_merge($params, $value);
                    $types .= str_repeat('s', count($value));
                } else {
                    $whereClause[] = "{$field} LIKE ?";
                    $params[] = "%{$value}%";
                    $types .= 's';
                }
            }
            $sql .= ' WHERE ' . implode(' AND ', $whereClause);
        }
        
        $orderDir = strtoupper($orderDir) === 'DESC' ? 'DESC' : 'ASC';
        $sql .= " ORDER BY {$orderBy} {$orderDir}";
        
        if (!empty($params)) {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $this->db->query($sql);
        }
        
        $rows = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            if (isset($stmt)) {
                $stmt->close();
            }
        }
        
        return $rows;
    }
    
    public function create(array $data): int
    {
        $filteredData = $this->filterFillable($data);
        $fields = array_keys($filteredData);
        $placeholders = str_repeat('?,', count($fields) - 1) . '?';
        
        $sql = "INSERT INTO {$this->table} (" . implode(',', $fields) . ") VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        
        $types = str_repeat('s', count($filteredData));
        $stmt->bind_param($types, ...array_values($filteredData));
        $stmt->execute();
        
        $insertId = $this->db->insert_id;
        $stmt->close();
        
        return $insertId;
    }
    
    public function update(int $id, array $data): bool
    {
        $filteredData = $this->filterFillable($data);
        $fields = array_keys($filteredData);
        $setClause = implode(' = ?, ', $fields) . ' = ?';
        
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        
        $values = array_values($filteredData);
        $values[] = $id;
        $types = str_repeat('s', count($filteredData)) . 'i';
        
        $stmt->bind_param($types, ...$values);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->bind_param('i', $id);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
    public function bulkDelete(array $ids): bool
    {
        if (empty($ids)) {
            return false;
        }
        
        $ids = array_map('intval', $ids);
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        $types = str_repeat('i', count($ids));
        
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id IN ({$placeholders})");
        $stmt->bind_param($types, ...$ids);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
    protected function filterFillable(array $data): array
    {
        return array_intersect_key($data, array_flip($this->fillable));
    }
}