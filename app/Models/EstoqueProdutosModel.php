<?php

namespace App\Models;

use CodeIgniter\Model;

class EstoqueProdutosModel extends Model
{
    protected $table            = 'estoque_produtos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'produto_id',
        'quantidade',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
