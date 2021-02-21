<?php
use Phinx\Seed\AbstractSeed;
use Ramsey\Uuid\Uuid;

class VariantSeeder extends AbstractSeed
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
                'color' => $faker->colorName,
                'size' => $faker->randomElement($this->getSizes())
            ];
        }

        $users = $this->table('variants');
        $users->insert($data)->save();
    }

    /**
     * Returns sizes of items
     *
     * @return array
     */
    private function getSizes(): array
    {
        return [
            'XXXS',
            'XXS',
            'XS',
            'S',
            'M',
            'L',
            'XL',
            'XXL',
            'XXXL'
        ];
    }
}
