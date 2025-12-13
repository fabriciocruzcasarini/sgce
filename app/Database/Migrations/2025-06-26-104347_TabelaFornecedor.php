<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TabelaFornecedor extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'razao_social'         => ['type' => 'VARCHAR', 'constraint' => 255],
            'nome_fantasia'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'cnpj'                 => ['type' => 'VARCHAR', 'constraint' => 18],
            'inscricao_estadual'  => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'inscricao_municipal' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'created_at'          => ['type' => 'timestamp', 'null' => true],
            'updated_at'          => ['type' => 'timestamp', 'null' => true],
            'status'              => ['type' => 'BOOLEAN', 'default' => true],
            'observacao'          => ['type' => 'TEXT', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('fornecedores');
    }

    public function down()
    {
        $this->forge->dropTable('fornecedores');
    }
}
