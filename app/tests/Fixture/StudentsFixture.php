<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * StudentsFixture
 */
class StudentsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'name' => 'Lorem ipsum dolor sit amet',
                'email' => 'Lorem ipsum dolor sit amet',
                'matricula' => 'Lorem ipsum dolor sit amet',
                'created' => '2026-11-19 23:18:28',
                'modified' => '2026-11-19 23:18:28',
            ],
        ];
        parent::init();
    }
}
