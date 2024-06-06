<div class="col-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="text-dark mb-0">Table Donatur</h2>
            <div class="btn-group">
                <button id="exportExcel" class="btn btn-info">
                    <i class="bi bi-file-arrow-down"></i>
                    Export Excel
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="bi bi-plus-circle"></i>
                    Tambah Donatur
                </button>
            </div>
        </div>


        <div class="card-body table-responsive p-4">
            <table class="table table-bordered table-hover datatable pt-4">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Label</th>
                        <th>NPWP</th>
                        <th>Nama</th>
                        <th>Foto</th>
                        <th>Kota</th>
                        <!-- <th>NO Telp</th>
                        <th>Email</th>
                        <th>Tanggal Lahir</th> -->
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $key => $value) : ?>

                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td>
                                <?php if ($datalabel[$key]) :
                                    foreach ($datalabel[$key] as $k => $v) : ?>
                                        <span class="badge bg-primary"><?= $v->type ?></span>
                                <?php endforeach;
                                endif ?>
                            </td>
                            <td><?= $value->npwp ?></td>
                            <td><?= $value->nama ?></td>
                            <td><img src="<?= base_url('public/donatur/') ?><?= $value->foto ?? 'image.png' ?>" alt="" width="100px" srcset=""></td>
                            <td><?php echo $value->kota ?></td>
                            <!-- <td><?php //echo $value->notelp ?></td>
                            <td><?php // echo $value->email ?></td>
                            <td><?php // echo $value->tgl_lahir ?></td> -->
                            <td class="">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#info-<?= $value->id ?>" title="info">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit-<?= $value->id ?>" title="update">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <?php
                                    $linkhapus = base_url('donatur/hapus/' . $value->id);
                                    ?>
                                    <a href="#" onclick="confirmAlert('<?= $linkhapus ?>','Apa anda yakin ingin menghapus?')" type="button" class="btn btn-danger btn-sm" title="hapus">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Donatur</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="<?= base_url('donatur/tambah') ?>" method="post" enctype="multipart/form-data">
                        <div class="modal-body">

                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <!-- <input type="text" name="nama" class="form-control" id="nama" required> -->
                                <select name="nama" class="form-control choices" id="" required>
                                    <option value="">Pilih Nama</option>
                                    <?php foreach ($datanama as $key => $value) : ?>
                                        <option value="<?= $value ?>"><?= $value ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="label" class="form-label">Label</label>
                                <select class="choices form-select multiple-remove" name="label_id[]" multiple="multiple">
                                    <?php foreach ($labels as $k => $v) : ?>
                                        <option value="<?= $v->id ?>"><?= $v->type ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="npwp" class="form-label">NPWP</label>
                                <input type="text" name="npwp" class="form-control" id="npwp" required>
                            </div>
                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto</label>
                                <input type="file" name="foto" class="form-control" id="foto" required>
                            </div>
                            <div class="mb-3">
                                <label for="kota" class="form-label">Kota (beta test)</label>
                                <input type="text" name="kota" class="form-control" id="kota" required>
                            </div>
                            <div class="mb-3">
                                <label for="notelp" class="form-label">Nomor Telepon</label>
                                <input type="text" name="notelp" class="form-control" id="notelp" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" id="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tgl_lahir" class="form-control" id="tgl_lahir" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>


        <?php foreach ($data as $key => $value) : ?>
            <div class="modal fade" id="edit-<?= $value->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Update Donatur</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="<?= base_url('donatur/update/' . $value->id) ?>" method="post">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <!-- <input type="text" name="nama" class="form-control" id="nama" required> -->
                                    <select name="nama" class="form-control choices" id="" required>
                                        <option value="">Pilih Nama</option>
                                        <?php foreach ($datanama as $k => $v) : ?>
                                            <option value="<?= $v ?>" <?= $value->nama == $v ? 'selected' : ''  ?>><?= $v ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="label" class="form-label">Label</label>
                                    <select class="choices form-select multiple-remove" name="label_id[]" multiple="multiple">
                                        <?php foreach ($labels as $k => $v) : ?>
                                            <!-- Check if label is selected for current donatur -->
                                            <?php $selected = in_array($v->id, array_column($datalabel[$key], 'id')) ? 'selected' : ''; ?>
                                            <option value="<?= $v->id ?>" <?= $selected ?>><?= $v->type ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="npwp" class="form-label">NPWP</label>
                                    <input type="text" name="npwp" class="form-control" id="npwp" value="<?= $value->npwp ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="foto" class="form-label">Foto (opsional)</label>
                                    <input type="file" name="foto" class="form-control" id="foto">
                                </div>
                                <div class="mb-3">
                                    <label for="kota" class="form-label">Kota</label>
                                    <input type="text" name="kota" class="form-control" id="kota" value="<?= $value->kota ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="notelp" class="form-label">Nomor Telepon</label>
                                    <input type="text" name="notelp" class="form-control" id="notelp" value="<?= $value->notelp ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" id="email" value="<?= $value->email ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" name="tgl_lahir" class="form-control" id="tgl_lahir" value="<?= $value->tgl_lahir ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="info-<?= $value->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Profile Donatur</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <section class="section profile">
                                <div class="row">
                                    <div class="col-xl-4">

                                        <div class="card">
                                            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                                                <img src="<?= base_url('public/donatur/') ?><?= $value->foto ?? 'image.png' ?>" alt="Profile" class="rounded-circle">
                                                <h2 class="mb-2"><?= $value->nama ?></h2>
                                                <h3>
                                                    <?php if ($datalabel[$key]) :
                                                        foreach ($datalabel[$key] as $k => $v) : ?>
                                                            <span class="badge bg-primary"><?= $v->type ?></span>
                                                    <?php endforeach;
                                                    endif ?>
                                                </h3>
                                                <div class="social-links mt-2">
                                                    <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                                                    <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                                                    <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                                                    <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-xl-8">

                                        <div class="card">
                                            <div class="card-body pt-3">
                                                <!-- Bordered Tabs -->
                                                <ul class="nav nav-tabs nav-tabs-bordered" role="tablist">

                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview-<?= $value->id ?>" aria-selected="true" role="tab">Overview</button>
                                                    </li>

                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings-<?= $value->id ?>" aria-selected="false" tabindex="-1" role="tab">Info Lain</button>
                                                    </li>

                                                </ul>
                                                <div class="tab-content pt-2">

                                                    <div class="tab-pane fade show active profile-overview" id="profile-overview-<?= $value->id ?>" role="tabpanel">
                                                        <h5 class="card-title">About</h5>
                                                        <p class="small fst-italic">Lorem ipsum dolor sit amet consectetur adipisicing elit. Assumenda quam magni fugit minima possimus ipsam labore ut necessitatibus esse quos hic, tempora, laudantium exercitationem, corporis nemo aut vitae! Qui, aliquam, facere voluptas reprehenderit quod perferendis rem quas neque blanditiis sequi placeat excepturi omnis, magnam id doloremque beatae repudiandae non natus.</p>

                                                        <h5 class="card-title">Profile Details</h5>

                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-4 label ">Full Name</div>
                                                            <div class="col-lg-9 col-md-8"><?= $value->nama ?></div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-4 label">Label</div>
                                                            <div class="col-lg-9 col-md-8">
                                                                <?php if ($datalabel[$key]) :
                                                                    foreach ($datalabel[$key] as $k => $v) : ?>
                                                                        <span class="badge bg-primary"><?= $v->type ?></span>
                                                                <?php endforeach;
                                                                endif ?>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-4 label">NPWP</div>
                                                            <div class="col-lg-9 col-md-8"><?= $value->npwp ?></div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-4 label">Kota</div>
                                                            <div class="col-lg-9 col-md-8"><?= $value->kota ?></div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-4 label">Nomor Telpon</div>
                                                            <div class="col-lg-9 col-md-8"><?= $value->notelp ?></div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-4 label">Email</div>
                                                            <div class="col-lg-9 col-md-8"><?= $value->email ?></div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-4 label">Tanggal Lahir</div>
                                                            <div class="col-lg-9 col-md-8"><?= $value->tgl_lahir ?></div>
                                                        </div>

                                                    </div>

                                                    <div class="tab-pane fade pt-3" id="profile-settings-<?= $value->id ?>" role="tabpanel">

                                                        <!-- Settings Form -->
                                                        <form>

                                                            <div class="row mb-3">
                                                                <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email Notifications</label>
                                                                <div class="col-md-8 col-lg-9">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" id="changesMade" checked="">
                                                                        <label class="form-check-label" for="changesMade">
                                                                            Changes made to your account
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" id="newProducts" checked="">
                                                                        <label class="form-check-label" for="newProducts">
                                                                            Information on new products and services
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" id="proOffers">
                                                                        <label class="form-check-label" for="proOffers">
                                                                            Marketing and promo offers
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" id="securityNotify" checked="" disabled="">
                                                                        <label class="form-check-label" for="securityNotify">
                                                                            Security alerts
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="text-center">
                                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                                            </div>
                                                        </form><!-- End settings Form -->

                                                    </div>

                                                </div><!-- End Bordered Tabs -->

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach ?>

    </div>
</div>