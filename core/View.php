<?php
namespace care\core;

class View {
    public static function render($view, $data = [],$layout="frontend") {
        // Extract data to variables
        extract($data);

        // Start output buffering
        ob_start();

        // Include the view file
        require_once 'views/' . $view . '.php';

        // Capture the content
        $content = ob_get_clean();

        // Set title if provided
        //$title = isset($data['title']) ? $data['title'] : 'E-Health Care';

        // Include the layout file and pass the content
        if ($layout === 'admin') {
            $layoutFile = 'views/admin-layout.php'; // Path to admin layout
        } else {
            $layoutFile = 'views/layout.php'; // Path to frontend layout
        }

        // Include the layout file and pass the content
        require_once $layoutFile;
    }
}
