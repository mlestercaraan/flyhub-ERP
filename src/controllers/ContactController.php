<?php
declare(strict_types=1);
namespace App\Controllers;

use mysqli;
use Exception;

class ContactController
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = new mysqli('localhost','root','','flyhub_erp');
        if($this->db->connect_error){
            throw new Exception('DB connect error: '.$this->db->connect_error);
        }
    }

    public function listContacts(string $search='', string $orderBy='first_name', string $orderDir='asc'): array
    {
        $allowed = ['first_name','last_name','email','phone'];
        if(!in_array($orderBy,$allowed,true)) $orderBy='first_name';
        $orderDir = strtolower($orderDir)==='desc' ? 'DESC' : 'ASC';

        $sql = "SELECT id, first_name, last_name, email, phone FROM contacts";
        if(trim($search)!==''){
          $s = $this->db->real_escape_string($search);
          $sql .= " WHERE first_name LIKE '%{$s}%' OR last_name LIKE '%{$s}%'
                    OR email LIKE '%{$s}%' OR phone LIKE '%{$s}%'";
        }
        $sql .= " ORDER BY {$orderBy} {$orderDir}";

        $res = $this->db->query($sql);
        $rows = [];
        if($res instanceof \mysqli_result){
          while($r = $res->fetch_assoc()){
            $rows[] = $r;
          }
          $res->free();
        }
        return $rows;
    }

    public function getContactById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM contacts WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $r = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $r ?: null;
    }

    public function createContact(array $data): void
    {
        $stmt = $this->db->prepare(
          'INSERT INTO contacts (first_name,last_name,email,phone) VALUES (?,?,?,?)'
        );
        $stmt->bind_param(
          'ssss',
          $data['first_name'],
          $data['last_name'],
          $data['email'],
          $data['phone']
        );
        $stmt->execute();
        $stmt->close();
    }

    public function updateContact(int $id, array $data): void
    {
        $stmt = $this->db->prepare(
          'UPDATE contacts SET first_name=?, last_name=?, email=?, phone=? WHERE id=?'
        );
        $stmt->bind_param(
          'ssssi',
          $data['first_name'],
          $data['last_name'],
          $data['email'],
          $data['phone'],
          $id
        );
        $stmt->execute();
        $stmt->close();
    }

    public function deleteContact(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM contacts WHERE id=?');
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $stmt->close();
    }

    public function __destruct()
    {
        $this->db->close();
    }
}
