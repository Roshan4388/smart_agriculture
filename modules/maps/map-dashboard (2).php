<?php
session_start();
require_once '../includes/auth.php';
require_once '../includes/header.php';
?>

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
        width: 280px;
        background: white;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow-y: auto;
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
        <!-- Crop Filter -->
        <div class="control-section">
            <h4>🌾 Filter by Crop</h4>
            <div class="button-group">
                <button class="btn-filter active" onclick="filterByCrop('all')">All Crops</button>
                <button class="btn-filter" onclick="filterByCrop('Rice')" style="background: #2E7D32; color: white;">🍚 Rice</button>
                <button class="btn-filter" onclick="filterByCrop('Wheat')" style="background: #F4D03F; color: #333;">🌾 Wheat</button>
                <button class="btn-filter" onclick="filterByCrop('Corn')" style="background: #F0AD4E; color: white;">🌽 Corn</button>
                <button class="btn-filter" onclick="filterByCrop('Vegetables')" style="background: #27AE60; color: white;">🥬 Vegetables</button>
            </div>
        </div>

        <!-- Weather Info -->
        <div class="control-section">
            <h4>🌤️ Weather</h4>
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

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Google Maps API (optional - requires API key) -->
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY"></script>

<!-- Map Script -->
<script src="<?php echo '../assets/js/map.js'; ?>"></script>

<?php require_once '../includes/footer.php'; ?>
