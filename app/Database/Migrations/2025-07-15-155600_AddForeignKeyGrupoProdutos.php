<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyGrupoProdutos extends Migration
{
    public function up()
    {

        // Cria chave estrangeira
        $this->db->query("
            ALTER TABLE subgrupo_produtos
            ADD CONSTRAINT fk_subgrupo_grupo
            FOREIGN KEY (grupo_produtos_id)
            REFERENCES grupo_produtos(id)
            ON DELETE CASCADE
            ON UPDATE CASCADE
        ");
    }

    public function down()
    {
        // Remove a chave estrangeira
        $this->db->query("
            ALTER TABLE subgrupo_produtos
            DROP FOREIGN KEY fk_subgrupo_grupo
        ");

        // Remove a coluna grupo_produtos_id
        $this->forge->dropColumn('subgrupo_produtos', 'grupo_produtos_id');
    }
}
