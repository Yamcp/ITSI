<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTabInscripcionesActividades extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'ID_INSCRIPCION' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'ID_ACTIVIDAD_EDUCACION' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'ID_ESTUDIANTE' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'FECHA_INSCRIPCION' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'ESTADO' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'Inscrito',
            ],
        ]);
        $this->forge->addKey('ID_INSCRIPCION', true);
        $this->forge->addKey(['ID_ACTIVIDAD_EDUCACION', 'ID_ESTUDIANTE'], false, true); // unique
        $this->forge->addForeignKey('ID_ACTIVIDAD_EDUCACION', 'TAB_ACTIVIDADES_EDUCACION', 'ID_ACTIVIDAD_EDUCACION', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('ID_ESTUDIANTE', 'TAB_ESTUDIANTES', 'ID_ESTUDIANTE', 'CASCADE', 'CASCADE');
        $this->forge->createTable('TAB_INSCRIPCIONES_ACTIVIDADES');
    }

    public function down(): void
    {
        $this->forge->dropTable('TAB_INSCRIPCIONES_ACTIVIDADES');
    }
}
