<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MovimentacoesEstoque extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'produto_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tipo' => [
                'type'       => 'ENUM',
                'constraint' => ['entrada', 'saida'],
                'default'    => 'entrada',
            ],
            'quantidade' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,4',
                'default'    => 0,
            ],
            'origem' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'origem_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('produto_id');
        $this->forge->createTable('movimentacoes_estoque');
    }

    public function down()
    {
        $this->forge->dropTable('movimentacoes_estoque');
    }
}
