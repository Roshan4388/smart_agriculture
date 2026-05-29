<?php
session_start();
$config = require __DIR__ . '/includes/config.php';

$buyerRole = $_POST['buyer_role'] ?? 'wholesaler';
$produceType = $_POST['produce_type'] ?? 'vegetables';
$packageOption = $_POST['package_option'] ?? 'bulk';
$priceExpectation = $_POST['price_expectation'] ?? 'standard';
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recommendation = 'Offer your produce at a competitive rate based on current demand.';
    if ($buyerRole === 'wholesaler') {
        $recommendation = 'Wholesalers prefer large quantity orders. Use standard packaging and prioritize reliable delivery timing.';
    } elseif ($buyerRole === 'retailer') {
        $recommendation = 'Retailers value freshness and smaller packages. Highlight local origin and quality.';
    } elseif ($buyerRole === 'exporter') {
        $recommendation = 'Exporters need consistent quality and compliance. Choose premium packaging and clear traceability details.';
    } elseif ($buyerRole === 'direct_customer') {
        $recommendation = 'Direct customers appreciate fresh, small-batch produce. Emphasize farm-to-table value and convenience.';
    }

    if ($packageOption === 'packaged') {
        $recommendation .= ' Packaged goods can fetch higher prices if quality is clearly specified.';
    } elseif ($packageOption === 'fresh') {
        $recommendation .= ' Fresh produce must be delivered quickly, so plan harvest and transport carefully.';
    }

    if ($priceExpectation === 'premium') {
        $recommendation .= ' Premium pricing works best with organic or specialty produce.';
    } elseif ($priceExpectation === 'budget') {
        $recommendation .= ' Budget pricing is great for high-volume sales and quick turnover.';
    }

    $result = [
        'buyerRole' => $buyerRole,
        'produceType' => $produceType,
        'packageOption' => $packageOption,
        'priceExpectation' => $priceExpectation,
        'recommendation' => $recommendation,
    ];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market Assistant - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>

<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <div class="section-header">
                <div>
                    <span class="eyebrow">Market Assistant</span>
                    <h1>Marketplace Assistance</h1>
                </div>
                <a class="secondary-link" href="modules/marketplace/products.php">Back to Marketplace</a>
            </div>
            <p>Select options below and get a tailored recommendation for selling your produce.</p>
            <form method="post" action="market-assistant.php" class="contact-form">
                <div class="form-group">
                    <label for="buyer_role">Buyer Role</label>
                    <select id="buyer_role" name="buyer_role" required>
                        <option value="wholesaler" <?= $buyerRole === 'wholesaler' ? 'selected' : '' ?>>Wholesaler</option>
                        <option value="retailer" <?= $buyerRole === 'retailer' ? 'selected' : '' ?>>Retailer</option>
                        <option value="exporter" <?= $buyerRole === 'exporter' ? 'selected' : '' ?>>Exporter</option>
                        <option value="direct_customer" <?= $buyerRole === 'direct_customer' ? 'selected' : '' ?>>Direct Customer</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="produce_type">Produce Type</label>
                    <select id="produce_type" name="produce_type" required>
                        <option value="vegetables" <?= $produceType === 'vegetables' ? 'selected' : '' ?>>Vegetables</option>
                        <option value="fruits" <?= $produceType === 'fruits' ? 'selected' : '' ?>>Fruits</option>
                        <option value="grains" <?= $produceType === 'grains' ? 'selected' : '' ?>>Grains</option>
                        <option value="spices" <?= $produceType === 'spices' ? 'selected' : '' ?>>Spices</option>
                        <option value="dairy" <?= $produceType === 'dairy' ? 'selected' : '' ?>>Dairy</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="package_option">Packaging Option</label>
                    <select id="package_option" name="package_option" required>
                        <option value="bulk" <?= $packageOption === 'bulk' ? 'selected' : '' ?>>Bulk</option>
                        <option value="packaged" <?= $packageOption === 'packaged' ? 'selected' : '' ?>>Packaged</option>
                        <option value="fresh" <?= $packageOption === 'fresh' ? 'selected' : '' ?>>Fresh</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="price_expectation">Price Expectation</label>
                    <select id="price_expectation" name="price_expectation" required>
                        <option value="budget" <?= $priceExpectation === 'budget' ? 'selected' : '' ?>>Budget</option>
                        <option value="standard" <?= $priceExpectation === 'standard' ? 'selected' : '' ?>>Standard</option>
                        <option value="premium" <?= $priceExpectation === 'premium' ? 'selected' : '' ?>>Premium</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Get Assistance</button>
                </div>
            </form>

            <?php if ($result) : ?>
                <section class="dashboard-section" style="margin-top: 24px;">
                    <div class="section-header">
                        <h2>Recommendation</h2>
                    </div>
                    <div class="dashboard-grid stats-grid">
                        <article class="dashboard-card">
                            <h3>Buyer Role</h3>
                            <p><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $result['buyerRole']))) ?></p>
                        </article>
                        <article class="dashboard-card">
                            <h3>Produce Type</h3>
                            <p><?= htmlspecialchars(ucfirst($result['produceType'])) ?></p>
                        </article>
                        <article class="dashboard-card">
                            <h3>Packaging</h3>
                            <p><?= htmlspecialchars(ucfirst($result['packageOption'])) ?></p>
                        </article>
                        <article class="dashboard-card">
                            <h3>Price Target</h3>
                            <p><?= htmlspecialchars(ucfirst($result['priceExpectation'])) ?></p>
                        </article>
                    </div>
                    <div class="alert-item alert-info" style="margin-top: 18px;">
                        <?= htmlspecialchars($result['recommendation']) ?>
                    </div>
                </section>
            <?php endif; ?>
        </section>
    </main>
</body>

</html>