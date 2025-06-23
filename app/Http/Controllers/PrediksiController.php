<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\PenjualanProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PrediksiController extends Controller
{
    /**
     * Hitung prediksi dengan sistem kriteria dan bobot
     * Method ini dipindah dari controller lama dan difokuskan untuk analisis saja
     */
    public function hitungPrediksiDenganKriteria($produk)
    {
        // Ambil data penjualan produk
        $dataPenjualan = $produk->penjualan;
        
        // Jika tidak ada data atau hanya satu data, belum bisa memprediksi
        if ($dataPenjualan->count() < 2) {
            return [
                'prediksi_bulan_depan' => null,
                'dapat_prediksi' => false,
                'pesan' => 'Data penjualan minimal 2 bulan untuk melakukan prediksi',
                'kriteria' => $this->getEmptyKriteria(),
                'weighted_score' => 0,
                'rekomendasi' => ['Tambahkan lebih banyak data penjualan untuk prediksi yang akurat'],
                'periode_prediksi' => null
            ];
        }
        
        // Cari data penjualan terakhir
        $dataTerakhir = $dataPenjualan->sortByDesc(function($item) {
            return ($item->tahun * 100) + $item->bulan;
        })->first();
        
        // Hitung periode berikutnya
        $bulanBerikutnya = $dataTerakhir->bulan + 1;
        $tahunBerikutnya = $dataTerakhir->tahun;
        
        if ($bulanBerikutnya > 12) {
            $bulanBerikutnya = 1;
            $tahunBerikutnya++;
        }
        
        // Persiapkan data untuk linear regression
        $x = []; // representasi bulan (1, 2, 3, ...)
        $y = []; // jumlah penjualan
        
        // Konversi data penjualan menjadi array untuk linear regression
        $i = 1;
        foreach ($dataPenjualan->sortBy(function($item) {
            return ($item->tahun * 100) + $item->bulan; // Urutkan berdasarkan tahun dan bulan
        }) as $penjualan) {
            $x[] = $i++;
            $y[] = $penjualan->jumlah_penjualan;
        }
        
        // Validasi data sebelum perhitungan
        if (empty($x) || empty($y) || count($x) != count($y)) {
            return [
                'prediksi_bulan_depan' => null,
                'dapat_prediksi' => false,
                'pesan' => 'Data tidak valid untuk perhitungan prediksi',
                'kriteria' => $this->getEmptyKriteria(),
                'weighted_score' => 0,
                'rekomendasi' => ['Periksa kualitas data penjualan'],
                'periode_prediksi' => null
            ];
        }
        
        // Hitung koefisien linear regression (rumus: y = a + bx)
        $n = count($x);
        $sumX = array_sum($x);
        $sumY = array_sum($y);
        $sumXY = 0;
        $sumX2 = 0;
        
        for ($i = 0; $i < $n; $i++) {
            $sumXY += $x[$i] * $y[$i];
            $sumX2 += $x[$i] * $x[$i];
        }
        
        // Menghitung nilai a dan b dengan proteksi division by zero
        $denominator = ($n * $sumX2 - $sumX * $sumX);
        
        if ($denominator == 0) {
            // Jika denominator = 0, gunakan rata-rata sebagai prediksi
            $prediksi = array_sum($y) / count($y);
            $a = $prediksi;
            $b = 0;
        } else {
            $b = ($n * $sumXY - $sumX * $sumY) / $denominator;
            $a = $n > 0 ? ($sumY - $b * $sumX) / $n : 0;
            
            // Prediksi untuk bulan berikutnya
            $prediksi = $a + $b * ($n + 1);
        }
        
        // Pastikan tidak prediksi negatif
        $prediksi = max(0, round($prediksi));
        
        // Hitung metrik statistik
        $statistik = $this->hitungMetrikStatistik($x, $y, $a, $b);
        
        // Evaluasi kriteria dan bobot
        $kriteria = $this->evaluasiKriteria($dataPenjualan, $statistik, $y);
        
        // Hitung weighted score
        $weightedScore = $this->hitungWeightedScore($kriteria);
        
        // Generate rekomendasi
        $rekomendasi = $this->generateRekomendasi($kriteria, $statistik);
        
        return [
            'prediksi_bulan_depan' => $prediksi,
            'dapat_prediksi' => true,
            'koefisien_a' => $a,
            'koefisien_b' => $b,
            'r_squared' => $statistik['r_squared'],
            'mae' => $statistik['mae'],
            'rmse' => $statistik['rmse'],
            'mape' => $statistik['mape'],
            'data_x' => $x,
            'data_y' => $y,
            'kriteria' => $kriteria,
            'weighted_score' => $weightedScore,
            'confidence_level' => $this->getConfidenceLevel($weightedScore),
            'rekomendasi' => $rekomendasi,
            'pesan' => 'Prediksi berhasil dilakukan dengan analisis kriteria lengkap',
            // Informasi periode prediksi yang benar
            'periode_prediksi' => [
                'bulan' => $bulanBerikutnya,
                'tahun' => $tahunBerikutnya,
                'nama_bulan' => $this->getNamaBulan($bulanBerikutnya)
            ]
        ];
    }

    /**
     * Hitung metrik statistik untuk evaluasi model
     */
    private function hitungMetrikStatistik($x, $y, $a, $b)
    {
        $n = count($x);
        $yMean = array_sum($y) / $n;
        
        $ssRes = 0; // Sum of squares of residuals
        $ssTot = 0; // Total sum of squares
        $sumAbsError = 0;
        $sumSquaredError = 0;
        $sumPercentError = 0;
        
        for ($i = 0; $i < $n; $i++) {
            $predicted = $a + $b * $x[$i];
            $actual = $y[$i];
            
            $residual = $actual - $predicted;
            $ssRes += $residual * $residual;
            $ssTot += ($actual - $yMean) * ($actual - $yMean);
            
            // MAE (Mean Absolute Error)
            $sumAbsError += abs($residual);
            
            // RMSE (Root Mean Square Error)
            $sumSquaredError += $residual * $residual;
            
            // MAPE (Mean Absolute Percentage Error)
            if ($actual != 0) {
                $sumPercentError += abs($residual / $actual);
            }
        }
        
        // R-squared
        $rSquared = $ssTot != 0 ? 1 - ($ssRes / $ssTot) : 0;
        $rSquared = max(0, min(1, $rSquared)); // Clamp between 0 and 1
        
        // MAE
        $mae = $sumAbsError / $n;
        
        // RMSE
        $rmse = sqrt($sumSquaredError / $n);
        
        // MAPE
        $mape = ($sumPercentError / $n) * 100;
        
        return [
            'r_squared' => round($rSquared, 4),
            'mae' => round($mae, 2),
            'rmse' => round($rmse, 2),
            'mape' => round($mape, 2)
        ];
    }

    /**
     * Evaluasi kriteria untuk sistem scoring
     */
    private function evaluasiKriteria($dataPenjualan, $statistik, $y)
    {
        $n = count($y);
        $totalPenjualan = array_sum($y);
        $avgPenjualan = $totalPenjualan / $n;
        $maxPenjualan = max($y);
        $minPenjualan = min($y);
        
        // Kriteria 1: Kualitas Data (25%)
        $kualitasData = $this->evaluateDataQuality($n, $y);
        
        // Kriteria 2: Akurasi Model (30%)
        $akurasiModel = $this->evaluateModelAccuracy($statistik);
        
        // Kriteria 3: Konsistensi Trend (25%)
        $konsistensiTrend = $this->evaluateTrendConsistency($y);
        
        // Kriteria 4: Volume Penjualan (20%)
        $volumePenjualan = $this->evaluateSalesVolume($avgPenjualan, $maxPenjualan);
        
        return [
            'kualitas_data' => $kualitasData,
            'akurasi_model' => $akurasiModel,
            'konsistensi_trend' => $konsistensiTrend,
            'volume_penjualan' => $volumePenjualan
        ];
    }

    private function evaluateDataQuality($n, $y)
    {
        $score = 0;
        $keterangan = [];
        
        // Jumlah data points
        if ($n >= 12) {
            $score += 40;
            $keterangan[] = "Data sangat lengkap (≥12 periode)";
        } elseif ($n >= 6) {
            $score += 30;
            $keterangan[] = "Data cukup lengkap (6-11 periode)";
        } elseif ($n >= 3) {
            $score += 20;
            $keterangan[] = "Data minimal (3-5 periode)";
        } else {
            $score += 10;
            $keterangan[] = "Data terbatas (<3 periode)";
        }
        
        // Variabilitas data
        $variance = $this->calculateVariance($y);
        $mean = array_sum($y) / count($y);
        $cv = $mean > 0 ? ($variance / $mean) : 0;
        
        if ($cv < 0.3) {
            $score += 30;
            $keterangan[] = "Variabilitas rendah (stabil)";
        } elseif ($cv < 0.6) {
            $score += 20;
            $keterangan[] = "Variabilitas sedang";
        } else {
            $score += 10;
            $keterangan[] = "Variabilitas tinggi";
        }
        
        // Outlier detection
        $outliers = $this->detectOutliers($y);
        if (count($outliers) == 0) {
            $score += 30;
            $keterangan[] = "Tidak ada outlier";
        } elseif (count($outliers) <= 1) {
            $score += 20;
            $keterangan[] = "Outlier minimal";
        } else {
            $score += 10;
            $keterangan[] = "Beberapa outlier terdeteksi";
        }
        
        return [
            'score' => min(100, $score),
            'bobot' => 25,
            'keterangan' => $keterangan
        ];
    }

    private function evaluateModelAccuracy($statistik)
    {
        $score = 0;
        $keterangan = [];
        
        // R-squared evaluation
        if ($statistik['r_squared'] >= 0.8) {
            $score += 40;
            $keterangan[] = "R² sangat baik (≥0.8)";
        } elseif ($statistik['r_squared'] >= 0.6) {
            $score += 30;
            $keterangan[] = "R² baik (0.6-0.8)";
        } elseif ($statistik['r_squared'] >= 0.4) {
            $score += 20;
            $keterangan[] = "R² cukup (0.4-0.6)";
        } else {
            $score += 10;
            $keterangan[] = "R² rendah (<0.4)";
        }
        
        // MAPE evaluation
        if ($statistik['mape'] <= 10) {
            $score += 30;
            $keterangan[] = "MAPE sangat baik (≤10%)";
        } elseif ($statistik['mape'] <= 20) {
            $score += 25;
            $keterangan[] = "MAPE baik (10-20%)";
        } elseif ($statistik['mape'] <= 30) {
            $score += 15;
            $keterangan[] = "MAPE cukup (20-30%)";
        } else {
            $score += 5;
            $keterangan[] = "MAPE tinggi (>30%)";
        }
        
        // MAE evaluation (relative to mean)
        $score += 30; // Base score for MAE
        $keterangan[] = "MAE: " . $statistik['mae'];
        
        return [
            'score' => min(100, $score),
            'bobot' => 30,
            'keterangan' => $keterangan
        ];
    }

    private function evaluateTrendConsistency($y)
    {
        $score = 0;
        $keterangan = [];
        $n = count($y);
        
        if ($n < 2) {
            return [
                'score' => 50,
                'bobot' => 25,
                'keterangan' => ['Data tidak cukup untuk evaluasi trend']
            ];
        }
        
        // Hitung trend direction consistency
        $increases = 0;
        $decreases = 0;
        $stable = 0;
        
        for ($i = 1; $i < $n; $i++) {
            $diff = $y[$i] - $y[$i-1];
            if ($diff > 0) $increases++;
            elseif ($diff < 0) $decreases++;
            else $stable++;
        }
        
        $totalChanges = $n - 1;
        $maxDirection = max($increases, $decreases, $stable);
        $consistency = $maxDirection / $totalChanges;
        
        if ($consistency >= 0.8) {
            $score += 40;
            $keterangan[] = "Trend sangat konsisten";
        } elseif ($consistency >= 0.6) {
            $score += 30;
            $keterangan[] = "Trend cukup konsisten";
        } else {
            $score += 20;
            $keterangan[] = "Trend tidak konsisten";
        }
        
        // Evaluate trend strength
        $firstHalf = array_slice($y, 0, floor($n/2));
        $secondHalf = array_slice($y, floor($n/2));
        
        $avgFirst = array_sum($firstHalf) / count($firstHalf);
        $avgSecond = array_sum($secondHalf) / count($secondHalf);
        
        $trendStrength = abs($avgSecond - $avgFirst) / max($avgFirst, 1);
        
        if ($trendStrength >= 0.2) {
            $score += 30;
            $keterangan[] = "Trend kuat";
        } elseif ($trendStrength >= 0.1) {
            $score += 20;
            $keterangan[] = "Trend sedang";
        } else {
            $score += 15;
            $keterangan[] = "Trend lemah";
        }
        
        // Seasonality detection
        $score += 30; // Base score
        $keterangan[] = "Pola musiman: " . ($this->detectSeasonality($y) ? "Terdeteksi" : "Tidak terdeteksi");
        
        return [
            'score' => min(100, $score),
            'bobot' => 25,
            'keterangan' => $keterangan
        ];
    }

    private function evaluateSalesVolume($avgPenjualan, $maxPenjualan)
    {
        $score = 0;
        $keterangan = [];
        
        // Volume categories (you can adjust these thresholds)
        if ($avgPenjualan >= 1000) {
            $score += 40;
            $keterangan[] = "Volume tinggi (≥1000 unit/periode)";
        } elseif ($avgPenjualan >= 500) {
            $score += 30;
            $keterangan[] = "Volume sedang (500-999 unit/periode)";
        } elseif ($avgPenjualan >= 100) {
            $score += 25;
            $keterangan[] = "Volume rendah (100-499 unit/periode)";
        } else {
            $score += 15;
            $keterangan[] = "Volume sangat rendah (<100 unit/periode)";
        }
        
        // Peak performance
        if ($maxPenjualan >= 2000) {
            $score += 30;
            $keterangan[] = "Puncak penjualan sangat tinggi";
        } elseif ($maxPenjualan >= 1000) {
            $score += 25;
            $keterangan[] = "Puncak penjualan tinggi";
        } else {
            $score += 20;
            $keterangan[] = "Puncak penjualan moderat";
        }
        
        // Growth potential
        $score += 30; // Base score for growth potential
        $keterangan[] = "Rata-rata: " . number_format($avgPenjualan, 0) . " unit";
        
        return [
            'score' => min(100, $score),
            'bobot' => 20,
            'keterangan' => $keterangan
        ];
    }

    /**
     * Helper methods
     */
    private function calculateVariance($data)
    {
        $mean = array_sum($data) / count($data);
        $variance = 0;
        
        foreach ($data as $value) {
            $variance += pow($value - $mean, 2);
        }
        
        return $variance / count($data);
    }

    private function detectOutliers($data)
    {
        $q1 = $this->percentile($data, 25);
        $q3 = $this->percentile($data, 75);
        $iqr = $q3 - $q1;
        
        $lowerBound = $q1 - 1.5 * $iqr;
        $upperBound = $q3 + 1.5 * $iqr;
        
        $outliers = [];
        foreach ($data as $index => $value) {
            if ($value < $lowerBound || $value > $upperBound) {
                $outliers[] = $index;
            }
        }
        
        return $outliers;
    }

    private function percentile($data, $percentile)
    {
        sort($data);
        $index = ($percentile / 100) * (count($data) - 1);
        
        if (floor($index) == $index) {
            return $data[$index];
        } else {
            $lower = $data[floor($index)];
            $upper = $data[ceil($index)];
            return $lower + ($upper - $lower) * ($index - floor($index));
        }
    }

    private function detectSeasonality($data)
    {
        // Simple seasonality detection
        if (count($data) < 4) return false;
        
        // Check for repeating patterns (very basic)
        $n = count($data);
        $patterns = 0;
        
        for ($i = 0; $i < $n - 3; $i++) {
            for ($j = $i + 2; $j < $n - 1; $j++) {
                if (abs($data[$i] - $data[$j]) / max($data[$i], 1) < 0.2) {
                    $patterns++;
                }
            }
        }
        
        return $patterns > ($n / 4);
    }

    private function hitungWeightedScore($kriteria)
    {
        $totalScore = 0;
        $totalBobot = 0;
        
        foreach ($kriteria as $nama => $data) {
            $totalScore += $data['score'] * ($data['bobot'] / 100);
            $totalBobot += $data['bobot'];
        }
        
        return round($totalScore, 2);
    }

    private function getConfidenceLevel($weightedScore)
    {
        if ($weightedScore >= 80) return 'Sangat Tinggi';
        if ($weightedScore >= 70) return 'Tinggi';
        if ($weightedScore >= 60) return 'Sedang';
        if ($weightedScore >= 50) return 'Rendah';
        return 'Sangat Rendah';
    }

    private function generateRekomendasi($kriteria, $statistik)
    {
        $rekomendasi = [];
        
        // Rekomendasi berdasarkan kualitas data
        if ($kriteria['kualitas_data']['score'] < 70) {
            $rekomendasi[] = "Tambahkan lebih banyak data historis untuk meningkatkan akurasi prediksi";
        }
        
        // Rekomendasi berdasarkan akurasi model
        if ($kriteria['akurasi_model']['score'] < 60) {
            $rekomendasi[] = "Model prediksi memerlukan perbaikan - pertimbangkan faktor eksternal";
        }
        
        // Rekomendasi berdasarkan trend
        if ($kriteria['konsistensi_trend']['score'] < 60) {
            $rekomendasi[] = "Pola penjualan tidak konsisten - analisis faktor penyebab fluktuasi";
        }
        
        // Rekomendasi berdasarkan volume
        if ($kriteria['volume_penjualan']['score'] < 50) {
            $rekomendasi[] = "Volume penjualan rendah - fokus pada strategi peningkatan penjualan";
        }
        
        // Rekomendasi umum
        if (empty($rekomendasi)) {
            $rekomendasi[] = "Model prediksi dalam kondisi baik - lanjutkan monitoring berkala";
        }
        
        return $rekomendasi;
    }

    private function getEmptyKriteria()
    {
        return [
            'kualitas_data' => ['score' => 0, 'bobot' => 25, 'keterangan' => ['Data tidak tersedia']],
            'akurasi_model' => ['score' => 0, 'bobot' => 30, 'keterangan' => ['Model tidak dapat dievaluasi']],
            'konsistensi_trend' => ['score' => 0, 'bobot' => 25, 'keterangan' => ['Trend tidak dapat dianalisis']],
            'volume_penjualan' => ['score' => 0, 'bobot' => 20, 'keterangan' => ['Volume tidak dapat dievaluasi']]
        ];
    }

    private function getNamaBulan($bulan)
    {
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return $namaBulan[$bulan] ?? 'Unknown';
    }
}
