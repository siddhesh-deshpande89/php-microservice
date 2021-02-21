<?php
namespace App\Repositories;

use App\Helpers\Database;
use App\Helpers\Cache;

class TransactionRepository
{

    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * Creates transaction record
     *
     * @param array $params
     * @return bool
     */
    public function create(array $params): bool
    {
        try {
            $sql = "INSERT INTO `transactions` (id, sku, variant_id, title) VALUES (:id, :sku, :variant_id, :title)";
            $query = $this->database->prepare($sql);
            $query->bindParam(":id", $params['id']);
            $query->bindParam(":sku", $params['sku']);
            $query->bindParam(":variant_id", $params['variant_id']);
            $query->bindParam(":title", $params['title']);
            $query->execute();

            // Update cache
            Cache::update('transactions', $params);

            return true;
        } catch (\Exception $ex) {
            // TODO Log error

            return false;
        }
    }

    /**
     * Get all records in transaction table
     * 
     * @return array|bool
     */
    public function getAll()
    {
        try {
            $sql = "SELECT id, sku, variant_id, title FROM `transactions`";
            $query = $this->database->prepare($sql);
            $query->execute();

            $result = $query->fetchAll(\PDO::FETCH_ASSOC);

            // Update cache
            Cache::update('transactions', $result);
            return $result;
        } catch (\Exception $ex) {
            // TODO Log error

            return false;
        }
    }
}