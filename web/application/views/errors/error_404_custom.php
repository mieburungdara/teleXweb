<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>404 Page Not Found</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    body {
        background-color: #f8f9fa;
    }
    .card {
        border: none;
        border-radius: 1rem;
    }
</style>
</head>
<body>
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card shadow-lg p-4">
                    <div class="card-body text-center">
                        <h1 class="display-1 fw-bold text-primary mb-3">404</h1>
                        <h2 class="fw-bold text-danger mb-3">Halaman Tidak Ditemukan</h2>
                        <p class="lead text-muted mb-4">
                            Maaf, halaman yang Anda cari tidak ada. Mungkin telah dipindahkan, dihapus, atau Anda salah mengetik alamat.
                        </p>
                        <button onclick="history.back()" class="btn btn-primary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>
                            Kembali ke Halaman Sebelumnya
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
