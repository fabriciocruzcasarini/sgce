<?php

namespace App\Models;

use CodeIgniter\Model;

class TelefoneClienteModel extends Model
{
    protected $table            = 'telefone_clientes';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['cliente_id', 'numero', 'tipo'];
    protected $useTimestamps    = true;

    protected $validationRules = [
        'numero' => 'required|min_length[8]|max_length[20]',
        'tipo'   => 'in_list[celular,residencial,comercial]',
    ];
}
