<?php
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../includes/auth.php';
requireLogin();
$config = require __DIR__ . '/../../includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Land Map - <?= htmlspecialchars($config['app_name']) ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/responsive.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    
    <style>
        .map-container {
            display: flex;
            gap: 15px;
            padding: 20px;
            height: calc(100vh - 200px);
        }

        .map-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        #land-map, #google-map {
            height: 100%;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        #google-map {
            display: none;
        }

        .controls-panel {
            width: 300px;
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow-y: auto;
            max-height: calc(100vh - 200px);
        }

        .control-section {
            margin-bottom: 20px;
        }

        .control-section h4 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 8px;
        }

        .button-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .btn-filter, .btn-map, .btn-action {
            padding: 10px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s;
            background: #f0f0f0;
            color: #333;
        }

        .btn-filter:hover, .btn-map:hover, .btn-action:hover {
            background: #e0e0e0;
            transform: translateX(2px);
        }

        .btn-filter.active {
            background: #27AE60;
            color: white;
        }

        .btn-map.active {
            background: #3498DB;
            color: white;
        }

        #weather-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 5px;
            font-size: 13px;
            line-height: 1.6;
        }

        #weather-info strong {
            display: block;
            margin-top: 8px;
            font-size: 14px;
        }

        .legend {
            background: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
            font-size: 12px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 5px 0;
        }

        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 2px solid #ddd;
        }

        .crop-selection-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
            margin-top: 10px;
        }

        .crop-selection-grid label {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #f7f9fc;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 8px;
            cursor: pointer;
            font-size: 13px;
        }

        .crop-selection-grid input {
            width: 16px;
            height: 16px;
            accent-color: #3498DB;
        }

        .control-section input[type="text"],
        .control-section textarea {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px;
            margin-top: 8px;
            font-size: 13px;
            resize: vertical;
        }

        .control-section textarea {
            min-height: 80px;
        }

        .plan-status {
            margin-top: 10px;
            font-size: 13px;
        }

        #realtime-feed {
            background: #f0f7ff;
            padding: 10px;
            border-radius: 5px;
            font-size: 12px;
            max-height: 200px;
            overflow-y: auto;
            border-left: 4px solid #3498DB;
        }

        #realtime-feed p {
            margin: 5px 0;
            padding: 5px;
            background: white;
            border-radius: 3px;
        }

        .toolbar {
            display: flex;
            gap: 10px;
            padding: 10px;
            background: white;
            border-radius: 5px;
            flex-wrap: wrap;
        }

        .toolbar button {
            padding: 8px 12px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s;
        }

        .toolbar button:hover {
            background: #f5f5f5;
            border-color: #999;
        }

        .map-mode-toggle {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .map-mode-toggle button.active,
        .btn-action.active {
            background: #12747d;
            color: #fff;
        }

        .selection-panel {
            background: #f6fbfc;
            border: 1px solid #d8eaef;
            border-radius: 8px;
            padding: 12px;
            font-size: 13px;
            line-height: 1.6;
        }

        .selection-panel strong {
            display: block;
            color: #14383d;
            margin-bottom: 4px;
        }

        .test-result {
            margin-top: 10px;
            border-radius: 8px;
            padding: 10px;
            background: #eef9f2;
            border-left: 4px solid #27AE60;
            font-size: 13px;
            line-height: 1.6;
        }

        .test-result.warning {
            background: #fff8e8;
            border-left-color: #F39C12;
        }

        .test-result.danger {
            background: #fff2f2;
            border-left-color: #E74C3C;
        }

        .element-list {
            display: grid;
            gap: 8px;
            margin-top: 10px;
        }

        .element-list button {
            text-align: left;
        }

        @media (max-width: 1024px) {
            .map-container {
                flex-direction: column;
            }
            .controls-panel {
                width: 100%;
                max-height: 300px;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../../includes/navbar.php'; ?>
    <main class="dashboard-page">
        <section class="dashboard-section">
            <div class="section-header">
                <h1>Interactive Land Visualization</h1>
                <p>Review your land boundaries, monitoring zones, and field data with advanced filtering options.</p>
            </div>

            <div class="map-container">
                <!-- Map Area -->
                <div class="map-wrapper">
                    <div class="toolbar">
                        <button onclick="exportWeatherData()">📊 Export Weather Data</button>
                        <button onclick="toggleMapType('satellite')">🛰️ Satellite</button>
                        <button onclick="toggleMapType('leaflet')">🗺️ Standard Map</button>
                        <button onclick="toggleMapType('google')">🔍 3D View</button>
                    </div>
                    <div id="land-map"></div>
                    <div id="google-map"></div>
                </div>

                <!-- Controls Panel -->
                <div class="controls-panel">
                    <div class="control-section">
                        <h4>Map Mode</h4>
                        <div class="map-mode-toggle">
                            <button class="btn-action active" id="real-mode-btn" type="button" onclick="setMapMode('real')">Real</button>
                            <button class="btn-action" id="virtual-mode-btn" type="button" onclick="setMapMode('virtual')">Virtual</button>
                        </div>
                    </div>

                    <div class="control-section">
                        <h4>Selected Element</h4>
                        <div id="selected-element" class="selection-panel">
                            <strong>No element selected</strong>
                            Click a field, boundary, or plan area on the map.
                        </div>
                        <div class="button-group" style="margin-top: 10px;">
                            <button class="btn-action" type="button" onclick="testSelectedElement()">Test selected element</button>
                            <button class="btn-action" type="button" onclick="testAllElements()">Test all visible elements</button>
                        </div>
                        <div id="element-test-result" class="test-result" style="display: none;"></div>
                    </div>

                    <div class="control-section">
                        <h4>Map Elements</h4>
                        <div id="map-element-list" class="element-list">
                            <button class="btn-action" type="button">Loading elements...</button>
                        </div>
                    </div>

                    <!-- Crop Filter -->
                    <div class="control-section">
                        <h4>🌾 Filter by Crop</h4>
                        <div class="button-group">
                            <button class="btn-filter active" data-crop-type="all" onclick="filterByCrop('all')">All Crops</button>
                            <button class="btn-filter" data-crop-type="Rice" onclick="filterByCrop('Rice')" style="background: #2E7D32; color: white;">🍚 Rice</button>
                            <button class="btn-filter" data-crop-type="Wheat" onclick="filterByCrop('Wheat')" style="background: #F4D03F; color: #333;">🌾 Wheat</button>
                            <button class="btn-filter" data-crop-type="Corn" onclick="filterByCrop('Corn')" style="background: #F0AD4E; color: white;">🌽 Corn</button>
                            <button class="btn-filter" data-crop-type="Vegetables" onclick="filterByCrop('Vegetables')" style="background: #27AE60; color: white;">🥬 Vegetables</button>
                        </div>
                        <div class="crop-selection-grid" id="crop-selectors">
                            <label><input type="checkbox" value="Rice" onchange="toggleCropSelection('Rice')"> Rice</label>
                            <label><input type="checkbox" value="Wheat" onchange="toggleCropSelection('Wheat')"> Wheat</label>
                            <label><input type="checkbox" value="Corn" onchange="toggleCropSelection('Corn')"> Corn</label>
                            <label><input type="checkbox" value="Vegetables" onchange="toggleCropSelection('Vegetables')"> Vegetables</label>
                            <label><input type="checkbox" value="Fruits" onchange="toggleCropSelection('Fruits')"> Fruits</label>
                        </div>
                        <button class="btn-action" onclick="clearSelectedCrops()">Clear selection</button>
                    </div>

                    <div class="control-section">
                        <h4>📍 Cultivation Plan</h4>
                        <div id="plan-details">
                            <div><strong>Planned cultivation point:</strong> 27.71720, 85.32400</div>
                            <div><strong>Selected crops:</strong> All crops selected</div>
                            <div><strong>Mapped radius:</strong> 70 meters</div>
                        </div>
                    </div>

                    <div class="control-section">
                        <h4>✍️ Sign & Save Plan</h4>
                        <input id="plan-signature" type="text" placeholder="Your signature name">
                        <textarea id="plan-notes" placeholder="Add notes for this cultivation plan"></textarea>
                        <button class="btn-action" onclick="savePlan()">Save signed plan</button>
                        <div id="plan-status" class="plan-status"></div>
                    </div>

                    <!-- Weather Info -->
                    <div class="control-section">
                        <h4>🌤️ Weather & Hourly</h4>
                        <div id="weather-info">
                            <div>Loading weather data...</div>
                        </div>
                    </div>

                    <!-- Pesticide Levels Legend -->
                    <div class="control-section">
                        <h4>⚠️ Pesticide Status</h4>
                        <div class="legend">
                            <div class="legend-item">
                                <div class="legend-color" style="background: #27AE60;"></div>
                                <span>✓ Normal (0-50%)</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background: #F39C12;"></div>
                                <span>⚠ Warning (50-80%)</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background: #E74C3C;"></div>
                                <span>✕ Danger (80%+)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Insect/Pest Detection -->
                    <div class="control-section">
                        <h4>🐛 Insect Alerts</h4>
                        <div class="legend">
                            <div class="legend-item">
                                <div class="legend-color" style="background: #27AE60;"></div>
                                <span>No Insects Detected</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background: #F39C12;"></div>
                                <span>Low Risk - Monitor</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background: #E74C3C;"></div>
                                <span>High Risk - Action Needed</span>
                            </div>
                        </div>
                    </div>

                    <!-- Crop Colors Legend -->
                    <div class="control-section">
                        <h4>🎨 Crop Legend</h4>
                        <div class="legend">
                            <div class="legend-item">
                                <div class="legend-color" style="background: #2E7D32;"></div>
                                <span>Rice</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background: #F4D03F;"></div>
                                <span>Wheat</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background: #F0AD4E;"></div>
                                <span>Corn</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background: #27AE60;"></div>
                                <span>Vegetables</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background: #E74C3C;"></div>
                                <span>Fruits</span>
                            </div>
                        </div>
                    </div>

                    <!-- Real-time Feed -->
                    <div class="control-section">
                        <h4>📡 Real-time Data</h4>
                        <div id="realtime-feed">
                            <p>Loading field data...</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- Google Maps API (optional - requires API key) -->
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY"></script>

    <!-- Map Script -->
    <script src="../../assets/js/map.js"></script>
</body>
</html>
