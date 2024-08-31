<?php

namespace care\core;

use care\config\Database;
use Exception;

class Model {
    protected $db;

    /**
     * Model constructor.
     * @throws Exception
     */
    public function __construct() {
        $this->db = new Database();
    }
}
