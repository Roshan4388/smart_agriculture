<?php
function getStaticCropProfiles()
{
    return [
        ['id' => 1, 'name' => 'Rice', 'season' => 'Monsoon', 'soil_type' => 'Clay loam', 'expected_yield' => '4.5 tons/ha', 'description' => 'Rice thrives in wet, flooded fields and is a staple cereal crop in many regions.', 'tips' => 'Maintain continuous moisture, use organic matter, and monitor for blast disease.'],
        ['id' => 2, 'name' => 'Wheat', 'season' => 'Winter', 'soil_type' => 'Loam', 'expected_yield' => '3.2 tons/ha', 'description' => 'Wheat prefers cool, dry weather during grain filling with moderate soil moisture.', 'tips' => 'Plant in well-drained soil, apply nitrogen carefully, and rotate with legumes.'],
        ['id' => 3, 'name' => 'Maize', 'season' => 'Summer', 'soil_type' => 'Loam', 'expected_yield' => '6.0 tons/ha', 'description' => 'Maize is a warm-weather crop with high yield potential when irrigated and fertilized properly.', 'tips' => 'Use quality hybrid seed, space rows evenly, and manage weeds early.'],
        ['id' => 4, 'name' => 'Barley', 'season' => 'Winter', 'soil_type' => 'Sandy loam', 'expected_yield' => '3.0 tons/ha', 'description' => 'Barley is suited for cool climates and can tolerate moderate soil salinity.', 'tips' => 'Avoid waterlogging and use balanced phosphorus to support root growth.'],
        ['id' => 5, 'name' => 'Oats', 'season' => 'Winter', 'soil_type' => 'Silty loam', 'expected_yield' => '2.8 tons/ha', 'description' => 'Oats are a hardy cereal crop with good performance on lighter soils and cool weather.', 'tips' => 'Ensure good seedbed preparation and control cereal rust with resistant varieties.'],
        ['id' => 6, 'name' => 'Pearl Millet', 'season' => 'Summer', 'soil_type' => 'Sandy clay', 'expected_yield' => '1.5 tons/ha', 'description' => 'Pearl millet is drought-tolerant and well suited for dryland farming.', 'tips' => 'Plant in warmer soils, keep spacing correct, and harvest at the right moisture.'],
        ['id' => 7, 'name' => 'Sorghum', 'season' => 'Summer', 'soil_type' => 'Loam', 'expected_yield' => '3.5 tons/ha', 'description' => 'Sorghum tolerates heat and drought, making it a good crop for marginal conditions.', 'tips' => 'Use basal fertilizer and protect young plants from birds and pests.'],
        ['id' => 8, 'name' => 'Soybean', 'season' => 'Monsoon', 'soil_type' => 'Loam', 'expected_yield' => '2.2 tons/ha', 'description' => 'Soybean is a legume that improves soil nitrogen and is valuable as a cash crop.', 'tips' => 'Ensure good inoculation, manage pod borer, and harvest at correct grain moisture.'],
        ['id' => 9, 'name' => 'Lentils', 'season' => 'Winter', 'soil_type' => 'Loam', 'expected_yield' => '1.0 ton/ha', 'description' => 'Lentils are short-duration legumes useful for crop rotation and soil health.', 'tips' => 'Plant in cool weather and provide adequate irrigation during flowering.'],
        ['id' => 10, 'name' => 'Chickpeas', 'season' => 'Winter', 'soil_type' => 'Sandy loam', 'expected_yield' => '1.3 tons/ha', 'description' => 'Chickpeas are a resilient legume with good value for human food and soil nutrient cycling.', 'tips' => 'Avoid excess water, use deep sowing in dry conditions, and preserve nodulation.'],
        ['id' => 11, 'name' => 'Green Peas', 'season' => 'Winter', 'soil_type' => 'Clay loam', 'expected_yield' => '2.0 tons/ha', 'description' => 'Green peas require cool conditions for best pod quality and yield.', 'tips' => 'Support vines if needed and harvest early for tender pods.'],
        ['id' => 12, 'name' => 'Groundnut', 'season' => 'Monsoon', 'soil_type' => 'Sandy loam', 'expected_yield' => '3.5 tons/ha', 'description' => 'Groundnut grows well in warm climates with a good balance of moisture.', 'tips' => 'Use well-drained fields and rotate to prevent leaf spot diseases.'],
        ['id' => 13, 'name' => 'Cotton', 'season' => 'Summer', 'soil_type' => 'Loam', 'expected_yield' => '1.5 tons/ha', 'description' => 'Cotton needs warm, long growing seasons and careful pest management.', 'tips' => 'Scout for bollworms, use Bt hybrids where appropriate, and manage irrigation.'],
        ['id' => 14, 'name' => 'Sugarcane', 'season' => 'Monsoon', 'soil_type' => 'Clay loam', 'expected_yield' => '80 tons/ha', 'description' => 'Sugarcane requires long-term care, regular irrigation, and fertile soil.', 'tips' => 'Apply green manure, schedule fertilization, and monitor ratoon crops closely.'],
        ['id' => 15, 'name' => 'Sesame', 'season' => 'Summer', 'soil_type' => 'Sandy loam', 'expected_yield' => '0.8 ton/ha', 'description' => 'Sesame is a small oilseed crop that performs well in warm, dry weather.', 'tips' => 'Harvest when capsules turn yellow and dry quickly to preserve oil quality.'],
        ['id' => 16, 'name' => 'Mustard', 'season' => 'Winter', 'soil_type' => 'Loam', 'expected_yield' => '2.4 tons/ha', 'description' => 'Mustard is a cool-season oilseed crop used widely for edible oil and meal.', 'tips' => 'Keep soil moisture uniform and control aphids and white rust.'],
        ['id' => 17, 'name' => 'Sunflower', 'season' => 'Summer', 'soil_type' => 'Loam', 'expected_yield' => '1.5 tons/ha', 'description' => 'Sunflower is a popular oilseed crop that prefers sunny, warm weather.', 'tips' => 'Use deep loosening, keep rows aligned, and avoid waterlogging.'],
        ['id' => 18, 'name' => 'Potato', 'season' => 'Winter', 'soil_type' => 'Loam', 'expected_yield' => '20 tons/ha', 'description' => 'Potato is a high-value tuber crop that needs cool weather and nutrient-rich soil.', 'tips' => 'Plant disease-free tubers, hill plants early, and manage late blight.'],
        ['id' => 19, 'name' => 'Onion', 'season' => 'Winter', 'soil_type' => 'Sandy loam', 'expected_yield' => '25 tons/ha', 'description' => 'Onions need consistent moisture and loose soil for good bulb development.', 'tips' => 'Thin crowded plants, provide sufficient potassium, and harvest at correct maturity.'],
        ['id' => 20, 'name' => 'Tomato', 'season' => 'Summer', 'soil_type' => 'Loam', 'expected_yield' => '40 tons/ha', 'description' => 'Tomatoes require warm days, cooler nights, and careful disease management.', 'tips' => 'Stake vines, irrigate at the base, and protect from tomato blight.'],
        ['id' => 21, 'name' => 'Eggplant', 'season' => 'Summer', 'soil_type' => 'Loam', 'expected_yield' => '18 tons/ha', 'description' => 'Eggplant grows best in warm, sunny conditions with regular watering.', 'tips' => 'Mulch to maintain soil moisture and watch for fruit borers.'],
        ['id' => 22, 'name' => 'Chili Pepper', 'season' => 'Summer', 'soil_type' => 'Sandy loam', 'expected_yield' => '12 tons/ha', 'description' => 'Chili peppers need warm temperatures and plenty of sunlight for spicy fruit.', 'tips' => 'Support plants, keep soil evenly moist, and harvest when fruit is ripe.'],
        ['id' => 23, 'name' => 'Cabbage', 'season' => 'Winter', 'soil_type' => 'Loam', 'expected_yield' => '30 tons/ha', 'description' => 'Cabbage prefers cool temperatures and rich, moist soil for tight heads.', 'tips' => 'Plant with enough spacing and protect from cabbage worms.'],
        ['id' => 24, 'name' => 'Cauliflower', 'season' => 'Winter', 'soil_type' => 'Loam', 'expected_yield' => '20 tons/ha', 'description' => 'Cauliflower requires cool weather and good fertility for compact heads.', 'tips' => 'Keep the soil cool, and blanch heads when they are firm.'],
        ['id' => 25, 'name' => 'Spinach', 'season' => 'Winter', 'soil_type' => 'Loam', 'expected_yield' => '10 tons/ha', 'description' => 'Spinach is a leafy vegetable that grows quickly in cooler weather.', 'tips' => 'Harvest young leaves regularly and provide good drainage.'],
        ['id' => 26, 'name' => 'Banana', 'season' => 'Tropical', 'soil_type' => 'Loam', 'expected_yield' => '50 tons/ha', 'description' => 'Banana needs warm, humid conditions and rich organic soil.', 'tips' => 'Irrigate deeply, support heavy bunches, and remove suckers regularly.'],
        ['id' => 27, 'name' => 'Mango', 'season' => 'Summer', 'soil_type' => 'Well-drained', 'expected_yield' => '12 tons/ha', 'description' => 'Mango trees thrive in tropical and subtropical climates with dry winters.', 'tips' => 'Prune for air circulation and protect flowers from late frosts.'],
        ['id' => 28, 'name' => 'Papaya', 'season' => 'Tropical', 'soil_type' => 'Loam', 'expected_yield' => '25 tons/ha', 'description' => 'Papaya grows quickly in warm climates and produces fruit year-round.', 'tips' => 'Provide protection from strong winds and regular irrigation.'],
        ['id' => 29, 'name' => 'Guava', 'season' => 'Tropical', 'soil_type' => 'Loam', 'expected_yield' => '20 tons/ha', 'description' => 'Guava is a hardy fruit crop with good tolerance for a range of soils.', 'tips' => 'Use balanced fertilizer and harvest when fruit is fragrant.'],
        ['id' => 30, 'name' => 'Orange', 'season' => 'Subtropical', 'soil_type' => 'Loam', 'expected_yield' => '30 tons/ha', 'description' => 'Orange trees require warm days and cool nights for best sweet fruit quality.', 'tips' => 'Maintain irrigation during fruit set and manage citrus greening carefully.'],
    ];
}

function findStaticCropProfileById($id)
{
    foreach (getStaticCropProfiles() as $crop) {
        if ($crop['id'] === $id) {
            return $crop;
        }
    }
    return null;
}

function findStaticCropProfileByName($name)
{
    foreach (getStaticCropProfiles() as $crop) {
        if (strcasecmp($crop['name'], $name) === 0) {
            return $crop;
        }
    }
    return null;
}
