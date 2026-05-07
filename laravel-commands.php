<?php
// laravel-commands.php - Run Laravel commands without terminal
// DELETE THIS FILE AFTER USE FOR SECURITY!

// Protect with a secret key (change this!)
$secret_key = 'patrick-12345';
if (!isset($_GET['key']) || $_GET['key'] !== $secret_key) {
    die('❌ Unauthorized. Use ?key=' . $secret_key);
}

// Display current directory
echo "<h1>Laravel Commands - No Terminal Required</h1>";
echo "<p>Current directory: " . __DIR__ . "</p>";
echo "<hr>";

// Function to run commands
function runCommand($cmd, $description) {
    echo "<h3>$description</h3>";
    echo "<pre>";
    $output = shell_exec("cd " . __DIR__ . " && $cmd 2>&1");
    echo htmlspecialchars($output ?: "Command executed (no output)");
    echo "</pre>";
    echo "<hr>";
}

// Check which command to run
$command = $_GET['run'] ?? 'help';

switch($command) {
    case 'npm-build':
        runCommand('npm run build', '📦 Running npm run build...');
        break;
        
    case 'clear':
        runCommand('php artisan optimize:clear', '🧹 Clearing all cache...');
        runCommand('php artisan view:clear', 'Clearing views...');
        runCommand('php artisan route:clear', 'Clearing routes...');
        runCommand('php artisan config:clear', 'Clearing config...');
        runCommand('php artisan cache:clear', 'Clearing application cache...');
        break;
        
    case 'migrate':
        runCommand('php artisan migrate --force', '🗄️ Running database migrations...');
        break;
        
    case 'migrate-fresh':
        echo "<div style='background:red; color:white; padding:10px;'>⚠️ WARNING: This will delete all data! ⚠️</div>";
        runCommand('php artisan migrate:fresh --force', '🗄️ Fresh migration (ALL DATA LOST)...');
        break;
        
    case 'migrate-rollback':
        runCommand('php artisan migrate:rollback --step=1', '⏪ Rolling back last migration...');
        break;
        
    case 'optimize':
        runCommand('php artisan optimize', '⚡ Optimizing Laravel...');
        break;
        
    case 'storage-link':
        runCommand('php artisan storage:link', '🔗 Creating storage symlink...');
        break;
        
    case 'all':
        echo "<h2>Running ALL commands (except fresh migration)...</h2>";
        runCommand('npm run build', '📦 Building assets...');
        runCommand('php artisan optimize:clear', '🧹 Clearing cache...');
        runCommand('php artisan migrate --force', '🗄️ Running migrations...');
        runCommand('php artisan storage:link', '🔗 Creating storage link...');
        runCommand('php artisan optimize', '⚡ Optimizing...');
        break;
        
    default:
        echo "<h2>Available Commands:</h2>";
        echo "<ul>";
        echo "<li><a href='?key=$secret_key&run=npm-build'>📦 npm run build</a> - Build frontend assets</li>";
        echo "<li><a href='?key=$secret_key&run=clear'>🧹 Clear all cache</a> - Clear config, routes, views, cache</li>";
        echo "<li><a href='?key=$secret_key&run=migrate'>🗄️ Run migrations</a> - Update database schema</li>";
        echo "<li><a href='?key=$secret_key&run=migrate-rollback'>⏪ Rollback migration</a> - Undo last migration</li>";
        echo "<li><a href='?key=$secret_key&run=optimize'>⚡ Optimize Laravel</a> - Cache config, routes, views</li>";
        echo "<li><a href='?key=$secret_key&run=storage-link'>🔗 Create storage link</a> - For file uploads</li>";
        echo "<li><a href='?key=$secret_key&run=all'>🚀 Run ALL (optimize, migrate, build)</a> - Complete refresh</li>";
        echo "<li style='color:red;'><a href='?key=$secret_key&run=migrate-fresh'>⚠️ FRESH MIGRATION (DANGER)</a> - Deletes all data!</li>";
        echo "</ul>";
        echo "<hr>";
        echo "<p><strong>Security:</strong> Delete this file after use!</p>";
        break;
}
?>