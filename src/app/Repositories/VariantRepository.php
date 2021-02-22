<?php
namespace App\Repositories;

use App\Helpers\Database;
use App\Helpers\Logger;

class VariantRepository
{

    private $database;

    private $table = 'variants';

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * Get random records from variants table
     *
     * @return array|bool
     */
    public function getRandomVariants($count = 100)
    {
        try {
            $sql = "SELECT id, color, size FROM `$this->table` ORDER BY rand() limit $count";
            $query = $this->database->prepare($sql);
            $query->execute();

            $result = $query->fetchAll(\PDO::FETCH_ASSOC);

            return $result;
        } catch (\Exception $ex) {

            Logger::error('variants', 'Error occured in get all products.');
            return false;
        }
    }
}