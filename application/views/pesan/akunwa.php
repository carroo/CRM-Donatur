<div class="col-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="text-dark mb-0">Table Pengaturan Api Pesan</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="bi bi-plus-circle"></i>
                Tambah Akun Wa
            </button>
        </div>


        <div class="card-body p-4">
            <div class="alert alert-info" role="alert">
                Jika scan QR telah berhasil, segera refresh halaman ini
            </div>
            <table class="table table-bordered table-hover datatable pt-4">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Phone Number</th>
                        <th>Status</th>
                        <th>Gunakan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $key => $value) : ?>

                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= $value['id'] ?></td>
                            <td><?= $value['phone_number'] ?></td>
                            <td><?= $value['status'] ?></td>
                            <td>
                                <?php if ($value['status'] == 'connected') : ?>
                                    <?php if ($digunakan == $value['id']) : ?>
                                        <span class="badge bg-success">Digunakan</span>
                                    <?php else : ?>
                                        <a href="<?= base_url('pesan/pengaturan_phone_use/' . $idset . '/' . $value['id']) ?>" class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-check"></i>
                                            Gunakan Akun Ini
                                        </a>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <a target="_blank" href="<?= base_url('pesan/pengaturan_phone_qr/' . $idset . '/' . $value['id']) ?>" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-phone"></i>
                                        Scan QR
                                    </a>
                                <?php endif; ?>
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
                        <h5 class="modal-title" id="exampleModal">Tambah Akun Wa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="<?= base_url('pesan/pengaturan_phone_add/' . $idset) ?>" method="post">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="judul" class="form-label">Title</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                            <div class="alert alert-info" role="alert">
                                klik simpan, setelah berhasil di tambahkan, maka langkah selanjutnya adalah scan qr melalui HP agar terkoneksi
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

    </div>
</div>