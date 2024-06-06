<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Dashboard - CRM LMI Donatur - <?= $title ?></title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="<?php echo base_url() ?>public/logo-newsp.png" rel="icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="<?php echo base_url() ?>public/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>public/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>public/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>public/assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>public/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="<?php echo base_url() ?>public/assets/vendor/remixicon/remixicon.css" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo base_url() ?>public/assets/vendor/choices.js/choices.min.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">

    <!-- Template Main CSS File -->
    <link href="<?php echo base_url() ?>public/assets/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <style>
        .nowrap th {
            white-space: nowrap;
        }

        #loader-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #loader {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div id="loader-wrapper">
        <div id="loader"></div>
    </div>


    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="index.html" class="logo d-flex align-items-center">
                <img src="<?php echo base_url() ?>public/logo-newsp.png" alt="">
                <span class="d-none d-lg-block"> CRM Donatur LMI</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->

        <!-- <div class="search-bar">
			<form class="search-form d-flex align-items-center" method="POST" action="#">
				<input type="text" name="query" placeholder="Search" title="Enter search keyword">
				<button type="submit" title="Search"><i class="bi bi-search"></i></button>
			</form>
		</div> -->

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <li class="nav-item d-block d-lg-none">
                    <!-- <a class="nav-link nav-icon search-bar-toggle " href="#">
						<i class="bi bi-search"></i>
					</a> -->
                </li><!-- End Search Icon-->
            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link collapsed<?= ($this->uri->uri_string() == '') ? '-' : '' ?>" href="<?= base_url() ?>">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-heading">Menu</li>
            <li class="nav-item">
                <a class="nav-link collapsed<?= ($this->uri->uri_string() == 'donatur') ? '-' : '' ?>" href="<?= base_url('donatur') ?>">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Donatur</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed<?= (strpos($this->uri->uri_string(), 'pesan') !== false) ? '-' : '' ?>" data-bs-target="#components-nav1" data-bs-toggle="collapse" href="#" aria-expanded="false">
                    <i class="bi bi-chat-right"></i><span>Pesan</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav1" class="nav-content collapse <?= (strpos($this->uri->uri_string(), 'pesan') !== false) ? ' show' : '' ?> " data-bs-parent="#sidebar-nav" style="">
                    <li>
                        <a href="<?= base_url('pesan/kirim') ?>" class="<?= ($this->uri->uri_string() == 'pesan/kirim') ? 'active' : '' ?>">
                            <i class="bi bi-circle"></i><span>Kirim Pesan</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('qurban') ?>" class="<?= ($this->uri->uri_string() == 'qurban') ? 'active' : '' ?>">
                            <i class="bi bi-circle"></i><span>Label Qurban Tracking</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('pesan/riwayat') ?>" class="<?= ($this->uri->uri_string() == 'pesan/riwayat') ? 'active' : '' ?>">
                            <i class="bi bi-circle"></i><span>Riwayat Pesan</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('pesan/template') ?>" class="<?= ($this->uri->uri_string() == 'pesan/template') ? 'active' : '' ?>">
                            <i class="bi bi-circle"></i><span>Template Pesan</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('pesan/pengaturan') ?>" class="<?= ($this->uri->uri_string() == 'pesan/pengaturan') ? 'active' : '' ?>">
                            <i class="bi bi-circle"></i><span>Pengaturan Pesan</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed<?= (strpos($this->uri->uri_string(), 'master') !== false) ? '-' : '' ?>" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#" aria-expanded="false">
                    <i class="bi bi-menu-button-wide"></i><span>Master</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav" class="nav-content collapse <?= (strpos($this->uri->uri_string(), 'master') !== false) ? ' show' : '' ?> " data-bs-parent="#sidebar-nav" style="">
                    <li>
                        <a href="<?= base_url('master/label') ?>" class="<?= ($this->uri->uri_string() == 'master/label') ? 'active' : '' ?>">
                            <i class="bi bi-circle"></i><span>Label</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('master/kanal_donasi') ?>" class="<?= ($this->uri->uri_string() == 'master/kanal_donasi') ? 'active' : '' ?>">
                            <i class="bi bi-circle"></i><span>Kanal Donasi</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('master/jenis_donasi') ?>" class="<?= ($this->uri->uri_string() == 'master/jenis_donasi') ? 'active' : '' ?>">
                            <i class="bi bi-circle"></i><span>Jenis Donasi</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('master/kategori') ?>" class="<?= ($this->uri->uri_string() == 'master/kategori') ? 'active' : '' ?>">
                            <i class="bi bi-circle"></i><span>Kategori</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('master/metode_pembayaran') ?>" class="<?= ($this->uri->uri_string() == 'master/metode_pembayaran') ? 'active' : '' ?>">
                            <i class="bi bi-circle"></i><span>Methode Pembayaran</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('master/api') ?>" class="<?= ($this->uri->uri_string() == 'master/Api') ? 'active' : '' ?>">
                            <i class="bi bi-circle"></i><span>Api</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

    </aside><!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle mb-4">
            <h1><?= $title ?></h1>
        </div><!-- End Page Title -->

        <?php echo $halaman; ?>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>MSIB 6 - IT </span></strong>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="<?php echo base_url() ?>public/assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="<?php echo base_url() ?>public/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url() ?>public/assets/vendor/chart.js/chart.umd.js"></script>
    <script src="<?php echo base_url() ?>public/assets/vendor/echarts/echarts.min.js"></script>
    <script src="<?php echo base_url() ?>public/assets/vendor/quill/quill.min.js"></script>
    <script src="<?php echo base_url() ?>public/assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="<?php echo base_url() ?>public/assets/vendor/php-email-form/validate.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>

    <script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <script src="<?php echo base_url() ?>public/assets/vendor/choices.js/choices.min.js"></script>

    <!-- Template Main JS File -->
    <script src="<?php echo base_url() ?>public/assets/js/main.js"></script>
    <script>
        document.onreadystatechange = function() {
            if (document.readyState === "complete") {
                // Hapus animasi loading saat halaman sudah selesai dimuat
                document.getElementById("loader-wrapper").style.display = "none";
            }
        };

        $(document).ready(function() {

            var table = $('.datatable').DataTable({
                dom: 'Blfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    text: 'Export to Excel',
                    className: 'd-none',
                    exportOptions: {
                        columns: ':not(:last-child)' // Menyembunyikan kolom terakhir
                    },
                }, ]
            });
            $('#exportExcel').on('click', function() {
                $('.buttons-excel').trigger('click');
            });
            var tbls = $('.tbls').DataTable({
                pagingType: 'simple',
                dom: 'ipf'
            });

        });

        function confirmAlert(link, title) {
            Swal.fire({
                title: title,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = link;
                }
            });
        }
    </script>
    <script>
        // Periksa jika terdapat pesan dari controller
        <?php if ($this->session->flashdata('message')) { ?>
            // Tampilkan pesan SweetAlert2
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: '<?php echo $this->session->flashdata('message'); ?>',
                showConfirmButton: false,
                timer: 1500
            });
        <?php } ?>

        <?php if ($this->session->flashdata('message_gagal')) { ?>
            // Tampilkan pesan SweetAlert2
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '<?php echo $this->session->flashdata('message_gagal'); ?>',
                showConfirmButton: false,
                timer: 2500
            });
        <?php } ?>
    </script>


</body>

</html>