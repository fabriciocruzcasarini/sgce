<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthPermissionModel extends Model
{
    protected $table = 'auth_permissions';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['name', 'description', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
