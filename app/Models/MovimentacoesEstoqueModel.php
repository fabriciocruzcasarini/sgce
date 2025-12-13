<?php

namespace App\Models;

use CodeIgniter\Model;

class MovimentacoesEstoqueModel extends Model
{
    protected $table            = 'movimentacoes_estoque';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'produto_id',
        'cliente_id',
        'tipo',
        'quantidade',
        'data_entrada',
        'lote',
        'validade',
        'origem',
        'origem_id',
        'created_at',
        'updated_at',
    ];
    // Timestamp automático
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';


    protected $skipValidation     = false;
}
