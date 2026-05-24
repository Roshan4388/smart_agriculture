<?php
header('Content-Type: application/json');
require_once '../includes/config.php';

// Fetch fields with pesticide and crop data
$query = "
    SELECT 
        f.id, 
        f.field_name, 
        f.latitude, 
        f.longitude, 
        f.area,
        c.crop_name as crop_type,
        f.soil_humidity,
        f.temperature,
        f.crop_health,
        CASE 
            WHEN f.pesticide_level > 80 THEN 'danger'
            WHEN f.pesticide_level > 50 THEN 'warning'
            ELSE 'normal'
        END as pesticide_level,
        f.pesticide_level as pesticide_value,
        f.last_updated
    FROM fields f
    LEFT JOIN crops c ON f.crop_id = c.id
    ORDER BY f.field_name
";

try {
    $result = $pdo->query($query);
    $fields = $result->fetchAll(PDO::FETCH_ASSOC);
    
    // If no database records, return sample data
    if (empty($fields)) {
        $fields = [
            [
                'id' => 1,
                'field_name' => 'North Field',
                'latitude' => 27.7175,
                'longitude' => 85.3242,
                'area' => 2.5,
                'crop_type' => 'Rice',
                'soil_humidity' => '68%',
                'temperature' => '24°C',
                'crop_health' => 'Good',
                'pesticide_level' => 'normal',
                'pesticide_value' => 35
            ],
            [
                'id' => 2,
                'field_name' => 'South Field',
                'latitude' => 27.7168,
                'longitude' => 85.3238,
                'area' => 1.8,
                'crop_type' => 'Wheat',
                'soil_humidity' => '72%',
                'temperature' => '23°C',
                'crop_health' => 'Excellent',
                'pesticide_level' => 'normal',
                'pesticide_value' => 25
            ],
            [
                'id' => 3,
                'field_name' => 'East Field',
                'latitude' => 27.7172,
                'longitude' => 85.3245,
                'area' => 3.2,
                'crop_type' => 'Corn',
                'soil_humidity' => '65%',
                'temperature' => '25°C',
                'crop_health' => 'Good',
                'pesticide_level' => 'warning',
                'pesticide_value' => 62
            ],
            [
                'id' => 4,
                'field_name' => 'West Field',
                'latitude' => 27.7170,
                'longitude' => 85.3235,
                'area' => 2.0,
                'crop_type' => 'Vegetables',
                'soil_humidity' => '70%',
                'temperature' => '24°C',
                'crop_health' => 'Good',
                'pesticide_level' => 'normal',
                'pesticide_value' => 40
            ]
        ];
    }
    
    echo json_encode($fields);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
