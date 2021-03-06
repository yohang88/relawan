<?php

namespace Tests;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations, DatabaseTransactions;

    protected $faker;
    /**
     * Set up the test
     */
    public function setUp()
    {
        parent::setUp();
        $this->faker = Faker::create();
        $this->artisan('migrate');
        $this->artisan('db:seed');
        $this->artisan('webappid:content:seed');
    }

    protected function secure()
    {
        $user = new User;
        $user = $user->find('1');

        $this->be($user);
    }

    /**
     * Create Aplication set for fake connection
     *
     * @return void
     */
    public function createApplication()
    {
        putenv('DB_CONNECTION=sqlite');
        $app = require __DIR__ . '/../bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        return $app;
    }

    /**
     * Reset the migrations
     */
    public function tearDown()
    {
        $this->artisan('migrate:reset');
        parent::tearDown();
    }
}
