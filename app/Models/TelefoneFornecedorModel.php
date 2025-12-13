<?php

namespace App\Models;

use CodeIgniter\Model;

class TelefoneFornecedorModel extends Model
{
    protected $table            = 'telefone_fornecedores';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['fornecedor_id', 'numero', 'tipo', 'usuario_id'];
    protected $useTimestamps    = true;

    protected $validationRules = [
        'numero' => 'required|min_length[8]|max_length[20]',
        'tipo'   => 'in_list[celular,residencial,comercial]',
    ];
}
