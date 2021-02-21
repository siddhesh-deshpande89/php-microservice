<?php
use Phinx\Seed\AbstractSeed;
use Ramsey\Uuid\Uuid;
class ProductSeeder extends AbstractSeed
{

    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $data = [];
        for ($i = 1; $i <= 100; $i ++) {
            $data[] = [
                'id' => Uuid::uuid4(),
                'sku' => $i,
                'title' => $faker->sentence(3)
            ];
        }

        $users = $this->table('products');
        $users->insert($data)->save();
    }
}
