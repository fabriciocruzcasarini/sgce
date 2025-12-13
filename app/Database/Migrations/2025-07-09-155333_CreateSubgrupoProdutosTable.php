<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubgrupoProdutosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                 => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'grupo_produtos_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'usuario_id'        => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'nome'              => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'descricao'         => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at'        => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at'        => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'status'            => [
                'type'       => 'ENUM',
                'constraint' => ['ativo', 'inativo'],
                'default'    => 'ativo',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('grupo_produtos_id', 'grupo_produtos', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('usuario_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('subgrupo_produtos');
    }

    public function down()
    {
        $this->forge->dropTable('subgrupo_produtos');
    }
}
