<?php
namespace care\core;

use Exception;

class Controller {
    protected $db;

    public function __construct() {
        $this->db = new \care\config\Database();
    }
    /**
     * @throws Exception
     */
    public function loadModel($model) {
        $modelClass = '\\care\\models\\' . $model;

        // Log the model being loaded
      //  error_log("Attempting to load model: $modelClass");

        if (class_exists($modelClass)) {
            return new $modelClass();
        } else {
            // Log the error if the class is not found
           // error_log("Model class not found: $modelClass");
            throw new Exception("Model not found: $modelClass");
        }
    }

    /**
     * @throws Exception
     */
    public function loadView($view, $data = []) {
        if (file_exists('views/' . $view . '.php')) {
            require_once 'views/' . $view . '.php';
        } else {
            error_log("View not found: $view");
            throw new Exception("View not found: $view");
        }
    }
}
