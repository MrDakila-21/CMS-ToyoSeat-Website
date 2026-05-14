<?php
// laravel-commands.php - Run Laravel commands without terminal
// DELETE THIS FILE AFTER USE FOR SECURITY!

// Protect with a secret key (change this!)
$secret_key = 'toy0s34T-ph';
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
        
    case 'migrate-fresh-seed':
        echo "<div style='background:red; color:white; padding:10px;'>⚠️ WARNING: This will delete all data and seed fresh data! ⚠️</div>";
        runCommand('php artisan migrate:fresh --seed --force', '🗄️ Fresh migration with seeding (ALL DATA LOST, then seeded)...');
        break;
        
    case 'migrate-rollback':
        runCommand('php artisan migrate:rollback --step=1', '⏪ Rolling back last migration...');
        break;
        
    case 'db-seed':
        echo "<div style='background:orange; color:white; padding:10px;'>⚠️ This will add/insert seed data (existing data will remain unless seeder deletes it)</div>";
        runCommand('php artisan db:seed --force', '🌱 Running database seeder...');
        break;
        
    case 'db-seed-class':
        $seederClass = $_GET['seeder'] ?? '';
        if ($seederClass) {
            echo "<div style='background:orange; color:white; padding:10px;'>⚠️ Running specific seeder: $seederClass</div>";
            runCommand("php artisan db:seed --class=$seederClass --force", "🌱 Running seeder: $seederClass");
        } else {
            echo "<div style='background:red; color:white; padding:10px;'>❌ No seeder class specified! Use ?seeder=UsersTableSeeder</div>";
        }
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
        runCommand('php artisan db:seed --force', '🌱 Running seeders...');
        runCommand('php artisan storage:link', '🔗 Creating storage link...');
        runCommand('php artisan optimize', '⚡ Optimizing...');
        break;
        
    case 'make-seeder':
        $seederName = $_GET['name'] ?? '';
        if ($seederName) {
            runCommand("php artisan make:seeder {$seederName}Seeder", "🔨 Creating seeder: {$seederName}Seeder");
        } else {
            echo "<div style='background:red; color:white; padding:10px;'>❌ No seeder name specified! Use ?name=User (creates UserSeeder)</div>";
        }
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
        echo "<li><a href='?key=$secret_key&run=all'>🚀 Run ALL (optimize, migrate, seed, build)</a> - Complete refresh</li>";
        
        echo "<li style='margin-top:10px;'><strong>🌱 Database Seeding:</strong></li>";
        echo "<li><a href='?key=$secret_key&run=db-seed'>🌱 Run all seeders</a> - Execute database seeders</li>";
        echo "<li><form method='GET' style='display:inline; margin-left:10px;'><input type='hidden' name='key' value='$secret_key'><input type='hidden' name='run' value='db-seed-class'><input type='text' name='seeder' placeholder='Specific seeder (e.g., UsersTableSeeder)' style='padding:2px;'><input type='submit' value='Run Specific Seeder'></form></li>";
        
        echo "<li><a href='?key=$secret_key&run=make-seeder&name=New'>🔨 Create new seeder</a> - <form method='GET' style='display:inline; margin-left:10px;'><input type='hidden' name='key' value='$secret_key'><input type='hidden' name='run' value='make-seeder'><input type='text' name='name' placeholder='Seeder name (e.g., Product)' required style='padding:2px;'><input type='submit' value='Create'></form></li>";
        
        echo "<li style='color:red;'><a href='?key=$secret_key&run=migrate-fresh'>⚠️ FRESH MIGRATION (DANGER)</a> - Deletes all data!</li>";
        echo "<li style='color:red;'><a href='?key=$secret_key&run=migrate-fresh-seed'>⚠️ FRESH MIGRATION WITH SEED (DANGER)</a> - Deletes all data and runs seeders!</li>";
        echo "</ul>";
        
        echo "<hr>";
        echo "<h3>💡 Quick Examples:</h3>";
        echo "<ul>";
        echo "<li><strong>Create a seeder:</strong> Use the form above or: <code>?key=patrick-12345&run=make-seeder&name=Product</code></li>";
        echo "<li><strong>Run specific seeder:</strong> <code>?key=patrick-12345&run=db-seed-class&seeder=ProductSeeder</code></li>";
        echo "<li><strong>Database reset + seed:</strong> <code>?key=patrick-12345&run=migrate-fresh-seed</code> (WARNING: deletes all data!)</li>";
        echo "</ul>";
        
        echo "<p><strong>Security:</strong> Delete this file after use!</p>";
        break;
}
?>