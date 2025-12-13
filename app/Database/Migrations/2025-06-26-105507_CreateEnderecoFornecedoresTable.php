<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEnderecoFornecedoresTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'fornecedor_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'logadouro'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'numero'          => ['type' => 'VARCHAR', 'constraint' => 10],
            'complemento'     => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'bairro'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'cidade'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'estado'          => ['type' => 'CHAR', 'constraint' => 2],
            'cep'             => ['type' => 'VARCHAR', 'constraint' => 9],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
            'status'          => ['type' => 'BOOLEAN', 'default' => true],
            'observacao'      => ['type' => 'TEXT', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('fornecedor_id', 'fornecedores', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('endereco_fornecedores');
    }

    public function down()
    {
        $this->forge->dropTable('endereco_fornecedores');
    }
}
