<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthGroupModel extends Model
{
    protected $table = 'auth_groups';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['name', 'description', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
