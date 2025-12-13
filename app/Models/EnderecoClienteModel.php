<?php

namespace App\Models;

use CodeIgniter\Model;

class EnderecoClienteModel extends Model
{
    protected $table            = 'endereco_clientes';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'cliente_id',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'cep',
    ];
    protected $useTimestamps    = true;

    protected $validationRules = [
        'logradouro' => 'required',
        'numero'     => 'required',
        'bairro'     => 'required',
        'cidade'     => 'required',
        'estado'     => 'required|exact_length[2]',
        'cep'        => 'required|min_length[8]|max_length[9]',
    ];
}
