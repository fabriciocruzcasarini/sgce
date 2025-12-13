<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddConsolidadaToNotasFiscais extends Migration
{
    public function up()
    {
        $this->forge->addColumn('notas_fiscais', [
            'consolidada' => [
                'type'       => 'INT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
                'after'      => 'status', // Ajuste conforme a ordem desejada
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('notas_fiscais', 'consolidada');
    }
}
