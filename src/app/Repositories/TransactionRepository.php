<?php
namespace App\Repositories;

use App\Helpers\Database;
use App\Helpers\Cache;
use App\Helpers\Logger;

class TransactionRepository
{

    private $database;
    private $table = 'transactions';

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
            $sql = "INSERT INTO `$this->table` (id, sku, variant_id, title) VALUES (:id, :sku, :variant_id, :title)";
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
            
            Logger::error('transactions','Error occured in create transaction.',$params);
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
            $sql = "SELECT id, sku, variant_id, title FROM `$this->table`";
            $query = $this->database->prepare($sql);
            $query->execute();

            $result = $query->fetchAll(\PDO::FETCH_ASSOC);

            // Update cache
            Cache::update('transactions', $result);
            return $result;
        } catch (\Exception $ex) {
            
            Logger::error('transactions','Error occured in get all transaction.');
            return false;
        }
    }
}