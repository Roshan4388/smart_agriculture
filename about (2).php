<?php
session_start();
$config = require __DIR__ . '/includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>

<body class="friendly-farm-bg">
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    <main class="dashboard-page home-page">
        <section class="dashboard-section panel-3d about-friendly-hero">
            <div class="section-header">
                <div>
                    <span class="eyebrow">Friendly farm dashboard</span>
                    <h1>About Smart Agriculture</h1>
                    <p>Smart Agriculture combines modern data-driven farming tools with traditional field experience.</p>
                </div>
            </div>

            <div class="friendly-dashboard">
                <article class="friendly-dashboard-card harvest-card">
                    <span class="dashboard-label">Crop Health</span>
                    <strong>Good</strong>
                    <p>Daily crop checks, disease alerts, and field notes in one simple view.</p>
                </article>
                <article class="friendly-dashboard-card water-card">
                    <span class="dashboard-label">Water Level</span>
                    <strong>72%</strong>
                    <p>Friendly irrigation signals help farmers save water and protect plants.</p>
                </article>
                <article class="friendly-dashboard-card weather-card">
                    <span class="dashboard-label">Weather</span>
                    <strong>Clear</strong>
                    <p>Local weather guidance supports smarter planting and harvest decisions.</p>
                </article>
                <article class="friendly-dashboard-card market-card">
                    <span class="dashboard-label">Market</span>
                    <strong>Active</strong>
                    <p>Track price signals and connect farm products with nearby buyers.</p>
                </article>
            </div>

            <div class="content-grid">
                <div class="content-column">
                    <h2>Why Smart Agriculture?</h2>
                    <p>Smart Agriculture brings together weather, soil, crop, and market data to help farmers make
                        faster decisions with a friendly 3D layout.</p>
                    <ul>
                        <li><strong>Land tracking & crop planning:</strong> Use weather, soil moisture and local season
                            data to choose the best crop.</li>
                        <li><strong>3D farm visualization:</strong> View land borders, field shape and terrain with
                            interactive mapping.</li>
                        <li><strong>Security monitoring:</strong> Get alerts for unauthorized access or boundary
                            breaches.</li>
                        <li><strong>Marketplace:</strong> Sell produce directly, manage buyers, and access live price
                            signals.</li>
                        <li><strong>Expert consultation:</strong> Request guidance from agriculture specialists and
                            advisors.</li>
                    </ul>
                </div>
                <div class="content-column nepali-column">
                    <h2 class="nepali-heading">स्मार्ट कृषि के हो?</h2>
                    <p class="nepali-text">स्मार्ट कृषि आधुनिक डाटा-आधारित कृषि उपकरण र परम्परागत खेत अनुभवलाई एक साथ
                        ल्याउँछ। अब तपाइँले सहज 3D लेआउटमा आफ्नो खेत र बाली योजना सजिलै नियन्त्रित गर्न सक्नुहुन्छ।</p>
                    <ul>
                        <li><strong>भूमि ट्र्याकिङ र बाली योजना:</strong> मौसम, माटोको नमी र स्थानीय मौसमको डाटाबाट
                            उत्तम बाली छान्नुहोस्।</li>
                        <li><strong>3D फार्म भिजुअलाइजेशन:</strong> इन्टरएक्टिभ म्यापिङमार्फत भूमि सीमा, खेतको आकार र
                            स्थलाकृति हेर्नुहोस्।</li>
                        <li><strong>सुरक्षा अनुगमन:</strong> अनधिकृत पहुँच वा सीमा उल्लङ्घनको लागि सचेत नटका पाउनुहोस्।
                        </li>
                        <li><strong>बजार:</strong> सिधै उत्पादन बेच्नुहोस्, खरिदकर्तालाई व्यवस्थापन गर्नुहोस् र
                            प्रत्यक्ष मूल्य संकेतहरू प्राप्त गर्नुहोस्।</li>
                        <li><strong>विशेषज्ञ सल्लाह:</strong> कृषि विशेषज्ञहरूबाट मार्गदर्शनको अनुरोध गर्नुहोस्।</li>
                    </ul>
                </div>
            </div>
        </section>
    </main>
    <footer class="site-footer">
        <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($config['app_name']) ?>. Built for smarter farming.</p>
    </footer>
</body>

</html>
