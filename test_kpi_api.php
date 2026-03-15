<?php

/**
 * File Testing untuk API KPI
 * Jalankan file ini untuk test semua endpoint API KPI
 */

// Konfigurasi
$base_url = 'http://localhost/presensi'; // Sesuaikan dengan URL aplikasi Anda
$api_url = $base_url . '/api/kpi';

// Test data
$test_pegawai_id = 1;
$test_bulan = 3;
$test_tahun = 2025;

echo "=================================================\n";
echo "TEST API KPI\n";
echo "=================================================\n\n";

// ==================== TEST 1: Get Bobot ====================
echo "TEST 1: Get Bobot\n";
echo "-------------------------------------------------\n";
$response = file_get_contents($api_url . '/bobot');
$data = json_decode($response, true);
echo "Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";

// ==================== TEST 2: Hitung KPI Single ====================
echo "TEST 2: Hitung KPI Single Pegawai\n";
echo "-------------------------------------------------\n";
$post_data = [
    'pegawai_id' => $test_pegawai_id,
    'bulan' => $test_bulan,
    'tahun' => $test_tahun
];

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($post_data)
    ]
];
$context  = stream_context_create($options);
$response = file_get_contents($api_url . '/hitung', false, $context);
$data = json_decode($response, true);
echo "Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";

// ==================== TEST 3: Get KPI by Periode ====================
echo "TEST 3: Get KPI by Periode\n";
echo "-------------------------------------------------\n";
$response = file_get_contents($api_url . '/get_by_periode?bulan=' . $test_bulan . '&tahun=' . $test_tahun);
$data = json_decode($response, true);
echo "Total Data: " . count($data['data']) . "\n";
echo "Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";

// ==================== TEST 4: Get KPI Pegawai ====================
echo "TEST 4: Get KPI Pegawai\n";
echo "-------------------------------------------------\n";
$response = file_get_contents($api_url . '/get_pegawai?pegawai_id=' . $test_pegawai_id);
$data = json_decode($response, true);
echo "Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";

// ==================== TEST 5: Get Ranking ====================
echo "TEST 5: Get Ranking KPI\n";
echo "-------------------------------------------------\n";
$response = file_get_contents($api_url . '/ranking?bulan=' . $test_bulan . '&tahun=' . $test_tahun . '&limit=5');
$data = json_decode($response, true);
echo "Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";

// ==================== TEST 6: Get Statistik ====================
echo "TEST 6: Get Statistik KPI\n";
echo "-------------------------------------------------\n";
$response = file_get_contents($api_url . '/statistik?bulan=' . $test_bulan . '&tahun=' . $test_tahun);
$data = json_decode($response, true);
echo "Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";

echo "=================================================\n";
echo "TEST SELESAI\n";
echo "=================================================\n";
