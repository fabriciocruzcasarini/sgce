<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTelefoneFornecedoresTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'fornecedor_id'  => ['type' => 'INT', 'constraint' => 10, 'unsigned' => true],
            'numero'         => ['type' => 'VARCHAR', 'constraint' => 20],
            'tipo'           => ['type' => 'ENUM', 'constraint' => ['residencial', 'comercial', 'celular', 'outro'], 'default' => 'outro'],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
            'status'         => ['type' => 'BOOLEAN', 'default' => true],
            'observacao'     => ['type' => 'TEXT', 'null' => true],
            'usuario_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => false],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('fornecedor_id', 'fornecedores', 'id', 'CASCADE', 'CASCADE');
        //$this->forge->addForeignKey('usuario_id', 'users', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('telefone_fornecedores');
    }

    public function down()
    {
        $this->forge->dropTable('telefone_fornecedores');
    }
}
