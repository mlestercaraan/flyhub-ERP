<?php
declare(strict_types=1);

namespace App\Controllers;

use mysqli;
use Exception;

class CompanyController
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = new mysqli('localhost', 'root', '', 'flyhub_erp');
        if ($this->db->connect_error) {
            throw new Exception('Database connect error: ' . $this->db->connect_error);
        }
    }

    /**
     * List companies, with optional search and sort
     *
     * @param string $searchTerm
     * @param string $orderBy     One of company_name, city, country, website_url
     * @param string $orderDir    asc or desc
     * @return array<int,array{ id:string, company_name:string, city:string, country:string, website_url:string }>
     */
    public function listCompanies(string $searchTerm = '', string $orderBy = 'company_name', string $orderDir = 'asc'): array
    {
        $allowed = ['company_name','city','country','website_url'];
        if (! in_array($orderBy, $allowed, true)) {
            $orderBy = 'company_name';
        }
        $orderDir = strtolower($orderDir) === 'desc' ? 'DESC' : 'ASC';

        $sql = 'SELECT id, company_name, city, country, website_url FROM companies';

        if (trim($searchTerm) !== '') {
            $s = $this->db->real_escape_string($searchTerm);
            $sql .= sprintf(
                " WHERE company_name LIKE '%%%1\$s%%' OR city LIKE '%%%1\$s%%' OR country LIKE '%%%1\$s%%' OR website_url LIKE '%%%1\$s%%'",
                $s
            );
        }

        $sql .= " ORDER BY {$orderBy} {$orderDir}";

        $result = $this->db->query($sql);
        $rows   = [];

        if ($result instanceof \mysqli_result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $result->free();
        }

        return $rows;
    }

    /**
     * Get a single company by id
     */
    public function getCompanyById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, company_name, city, country, website_url FROM companies WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $res ?: null;
    }

    /**
     * Create a new company
     */
    public function createCompany(array $data): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO companies (company_name, city, country, website_url) VALUES (?, ?, ?, ?)'
        );
        $stmt->bind_param(
            'ssss',
            $data['company_name'],
            $data['city'],
            $data['country'],
            $data['website_url']
        );
        $stmt->execute();
        $stmt->close();
    }

    /**
     * Update an existing company
     */
    public function updateCompany(int $id, array $data): void
    {
        $stmt = $this->db->prepare(
            'UPDATE companies SET company_name = ?, city = ?, country = ?, website_url = ? WHERE id = ?'
        );
        $stmt->bind_param(
            'ssssi',
            $data['company_name'],
            $data['city'],
            $data['country'],
            $data['website_url'],
            $id
        );
        $stmt->execute();
        $stmt->close();
    }

    /**
     * Delete a single company
     */
    public function deleteCompany(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM companies WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * Bulk delete companies by array of ids
     */
    public function bulkDeleteCompanies(array $ids): void
    {
        $filtered = array_map('intval', $ids);
        if (empty($filtered)) {
            return;
        }
        $in  = implode(',', $filtered);
        $sql = "DELETE FROM companies WHERE id IN ({$in})";
        $this->db->query($sql);
    }

    /**
     * Inline edit a single field
     */
    public function inlineEditCompany(int $id, string $field, string $value): void
    {
        $allowed = ['company_name','city','country','website_url'];
        if (! in_array($field, $allowed, true)) {
            throw new Exception('Invalid field for inline edit');
        }

        $stmt = $this->db->prepare("UPDATE companies SET {$field} = ? WHERE id = ?");
        $stmt->bind_param('si', $value, $id);
        $stmt->execute();
        $stmt->close();
    }

    public function __destruct()
    {
        $this->db->close();
    }
}
