<?php
// Baby Name Generator & Pregnancy Calculator - Main Entry Point

session_start();

// Include your config
if (file_exists('config/config.php')) {
    require_once 'config/config.php';
}

// Get the page parameter (default to baby name generator)
$page = isset($_GET['page']) ? $_GET['page'] : 'baby-name-generator';

// Set page title based on route
switch($page) {
    case 'pregnancy-calculator':
        $title = "Pregnancy Due Date Calculator - Calculate Your Due Date";
        break;
    case 'winner-photo-release':
        $title = "Winner Photo Release Form - Baby Brands Gift Club";
        break;
    case 'baby-name-generator':
    default:
        $title = "Baby Name Generator - Find Perfect Names";
        break;
}

?>

<?php include 'template/header.php'; ?>

<?php
// Route to appropriate view
switch($page) {
    case 'pregnancy-calculator':
        // Load Pregnancy Calculator
        if (file_exists('views/pregnancycalculator.php')) {
            include 'views/pregnancycalculator.php';
        } else {
            ?>
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="alert alert-warning text-center">
                            <h4><i class="fas fa-exclamation-triangle"></i> File Missing</h4>
                            <p>The pregnancy calculator view file is missing.</p>
                            <hr>
                            <p class="mb-0">Please ensure this file exists:</p>
                            <ul class="list-unstyled mt-3">
                                <li>views/pregnancycalculator.php</li>
                            </ul>
                            <a href="?page=baby-name-generator" class="btn btn-primary mt-3">
                                <i class="fas fa-arrow-left"></i> Back to Baby Name Generator
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        break;

    case 'winner-photo-release':
        // Load Winner Photo Release Form
        if (file_exists('views/winnerphotorelease.php')) {
            include 'views/winnerphotorelease.php';
        } else {
            ?>
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="alert alert-warning text-center">
                            <h4><i class="fas fa-exclamation-triangle"></i> File Missing</h4>
                            <p>The winner photo release view file is missing.</p>
                            <hr>
                            <p class="mb-0">Please ensure this file exists:</p>
                            <ul class="list-unstyled mt-3">
                                <li>views/winnerphotorelease.php</li>
                            </ul>
                            <a href="?page=baby-name-generator" class="btn btn-primary mt-3">
                                <i class="fas fa-arrow-left"></i> Back to babybrandsgiftclub.com
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        break;

    case 'baby-name-generator':
    default:
        // Load Baby Name Generator
        if (file_exists('views/babynamegenerator.php')) {
            include 'views/babynamegenerator.php';
        } else {
            ?>
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="alert alert-info text-center">
                            <h4><i class="fas fa-info-circle"></i> Setup Complete!</h4>
                            <p>Your baby name generator system is ready.</p>
                            <hr>
                            <p class="mb-0">File Status:</p>
                            <ul class="list-unstyled mt-3">
                                <li>template/header.php</li>
                                <li>template/footer.php</li> 
                                <li>template/style.css</li>
                                <li>controllers/babynamegeneratorcontroller.php</li>
                                <li>index.php</li>
                                <li>views/babynamegenerator.php</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        break;
}
?>

<?php include 'template/footer.php'; ?>