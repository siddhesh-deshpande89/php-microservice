<?php
namespace App\Controllers;

use App\Helpers\MessageBrokers\MessageBroker;

class ProductController
{

    /**
     * ProductController Constructor
     */
    public function __construct()
    {}

    /**
     * Insert request
     */
    public function insert()
    {
        $params['sku'] = $_POST['sku'];

        $messageBroker = new MessageBroker();
        $messageBroker->publish('product_queue', $params);
    }
}