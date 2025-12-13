<?php

namespace App\Models;

use CodeIgniter\Model;

class ItensNotaFiscalModel extends Model
{
    protected $table            = 'itens_nota_fiscal';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'nota_id',
        'produto_id',
        'lote',
        'validade',
        'quantidade',
        'valor_unitario',
        'base_calculo_icms',
        'valor_icms',
        'base_calculo_ipi',
        'valor_ipi',
        'observacao',
        'status',
    ];

    // Timestamp automático
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';


    protected $skipValidation     = false;
}
