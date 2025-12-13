<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGrupoProdutosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nome_grupo'   => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'descricao'    => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at'   => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at'   => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'status'       => [
                'type'       => 'ENUM',
                'constraint' => ['ativo', 'inativo'],
                'default'    => 'ativo',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('grupo_produtos');
    }

    public function down()
    {
        $this->forge->dropTable('grupo_produtos');
    }
}
