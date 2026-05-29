<?php
session_start();
$config = require __DIR__ . '/includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market Status - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>

<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <div class="section-header">
                <div>
                    <span class="eyebrow">Market Status</span>
                    <h1>Live Market Overview</h1>
                </div>
                <a class="secondary-link" href="modules/marketplace/products.php">Go to Marketplace</a>
                <a class="secondary-link" href="market-assistant.php">Open Market Assistant</a>
            </div>
            <p>Track the latest market demand, buyer assistance, and pricing signals for your crops and services.</p>
            <div class="dashboard-grid stats-grid">
                <article class="dashboard-card">
                    <h3>Demand Score</h3>
                    <p>High</p>
                </article>
                <article class="dashboard-card">
                    <h3>Top Crop</h3>
                    <p>Tomatoes</p>
                </article>
                <article class="dashboard-card">
                    <h3>Price Trend</h3>
                    <p>Stable to rising</p>
                </article>
                <article class="dashboard-card">
                    <h3>Assistant Available</h3>
                    <p>Yes — market guidance active</p>
                </article>
            </div>
            <section class="dashboard-section">
                <h2>Market Assistant</h2>
                <p>Select buyer roles, pricing strategy, and product readiness from the marketplace assistant tools.</p>
                <ul class="recommendation-list">
                    <li>Select the best buyer option before posting.</li>
                    <li>Use price guidance for local demand.</li>
                    <li>Prepare produce listings with quality and harvest timing notes.</li>
                </ul>
            </section>
        </section>
    </main>
</body>

</html>