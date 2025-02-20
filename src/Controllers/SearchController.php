<?php

namespace App\Controllers;

use App\Entities\Service;
use App\Entities\Auth;

class SearchController
{
    public function searchResults()
    {
        session_start();
        Auth::redirectIfNotLoggedIn();

        $service = new Service();

        $query = isset($_GET['query']) ? trim($_GET['query']) : '';
        $location = isset($_GET['location']) ? trim($_GET['location']) : '';

        $results = [];

        if (!empty($query) && !empty($location)) {
            $results = $service->searchServices($query, $location);
        }

        include __DIR__ . '/../views/search_results.php';
    }
}
?>