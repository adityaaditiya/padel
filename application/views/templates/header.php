<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>PadelPro</title>
    <!-- Bootstrap CSS via CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="<?php echo site_url('dashboard'); ?>">PadelPro</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <?php if ($this->session->userdata('logged_in')): ?>
                <?php $role = $this->session->userdata('role'); ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="bookingDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Booking</a>
                    <div class="dropdown-menu" aria-labelledby="bookingDropdown">
                        <?php if ($role === 'pelanggan'): ?>
                            <a class="dropdown-item" href="<?php echo site_url('booking/my'); ?>">Booking Saya</a>
                        <?php endif; ?>
                        <a class="dropdown-item" href="<?php echo site_url('booking'); ?>">Jadwal Booking Lapangan</a>
                        <?php if (in_array($role, ['kasir','admin_keuangan','owner'])): ?>
                            <a class="dropdown-item" href="<?php echo site_url('booking/cancelled'); ?>">Booking Batal</a>
                        <?php endif; ?>
                    </div>
                </li>
                <?php if (in_array($role, ['kasir','admin_keuangan','owner'])): ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo site_url('pos'); ?>">POS</a></li>
                <?php endif; ?>
                <?php if ($role === 'owner'): ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo site_url('courts'); ?>">Lapangan</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo site_url('reports'); ?>">Laporan</a></li>
                <?php endif; ?>
                <?php if (in_array($role, ['kasir','admin_keuangan','owner'])): ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo site_url('finance'); ?>">Keuangan</a></li>
                <?php endif; ?>
                <?php if (in_array($role, ['kasir','admin_keuangan','owner'])): ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo site_url('products'); ?>">Produk</a></li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
        <ul class="navbar-nav">
            <?php if ($this->session->userdata('logged_in')): ?>
                <li class="nav-item"><span class="navbar-text mr-3">Halo, <?php echo htmlspecialchars($this->session->userdata('nama_lengkap')); ?></span></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('auth/logout'); ?>">Logout</a></li>
            <?php else: ?>
                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('auth/login'); ?>">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('auth/register'); ?>">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<div class="container mt-4">
