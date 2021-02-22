<?php
namespace App\Repositories;

use App\Helpers\Database;
use App\Helpers\Logger;

class ProductRepository
{

    private $database;

    private $table = 'products';

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * Get random records from products table
     *
     * @return array|bool
     */
    public function getRandomProducts($count)
    {
        try {
            $sql = "SELECT id, sku, title FROM `$this->table` ORDER BY rand() limit $count";
            $query = $this->database->prepare($sql);
            $query->execute();

            $result = $query->fetchAll(\PDO::FETCH_ASSOC);
           
            return $result;
        } catch (\Exception $ex) {

            Logger::error('products', 'Error occured in get all products.');
            return false;
        }
    }
}