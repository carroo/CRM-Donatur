<div class="col-12">
    <div class="card">
        <div class="card-header ">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="bi bi-plus-circle"></i>
                Tambahahkan
            </button>
        </div>


        <div class="card-body p-4">
            <div class="row">
                <?php foreach ($template as $key => $value) : ?>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body py-0">
                                <div class="card-title">
                                    <h2><?= $value->type ?></h2>
                                </div>
                                <?php if ($value->image) : ?>
                                    <img width="100%" class="img-fluid mb-3" src="<?php echo base_url('public/fotopesan/') ?><?= $value->image ?>" alt="" srcset="">
                                <?php endif; ?>
                                <?php if ($value->send_at) : ?>
                                    <?php
                                    $sendAtDateTime = new DateTime($value->send_at);
                                    $currentDateTime = new DateTime(); // Current time
                                    if ($sendAtDateTime < $currentDateTime) {
                                        // Send_at has passed, apply bg-danger class
                                        echo '<span class="badge bg-danger mb-3">Terlewat : ' . $value->send_at . '</span>';
                                    } else {
                                        // Send_at is in the future, apply bg-primary class
                                        echo '<span class="badge bg-primary mb-3">Terkirim pada ' . $value->send_at . '</span>';
                                    }
                                    ?>
                                <?php endif; ?>
                                <p><?= $value->text ?></p>
                            </div>
                            <div class="card-footer">
                                <!-- <button class="btn btn-sm btn-primary rounded">
                                    Detail
                                </button> -->
                                <!-- <button class="btn btn-sm btn-warning rounded">
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button> -->
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit-<?= $value->id ?>" title="update">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <?php
                                $linkhapus = base_url('pesan/template_hapus/' . $value->id);
                                ?>
                                <a href="#" onclick="confirmAlert('<?= $linkhapus ?>','Apa anda yakin ingin menghapus?')" type="button" class="btn btn-danger btn-sm" title="hapus">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal">Tambah Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('pesan/template_tambah') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" class="form-control" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="pesan" class="form-label">Pesan</label>
                        <textarea class="form-control" name="pesan" id="pesan" rows="6" required></textarea>
                    </div>
                    <div class="alert alert-info mb-3" role="alert">
                        Gunakan <strong>"{nama}"</strong> (tanpa petik) dan akan berisi nama dari penerima pesan.
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input toggle-input" name="use_image" type="checkbox" id="gambar">
                        <label class="form-check-label" for="gambar">Memakai Gambar ?</label>
                    </div>
                    <div class="mb-3 toggle-target d-none">
                        <input type="file" class="form-control" name="image">
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input toggle-input" name="use_send_at" type="checkbox" id="jadwal">
                        <label class="form-check-label" for="jadwal">Perlu Dijadwalkan ?</label>
                    </div>
                    <div class="mb-3 toggle-target d-none">
                        <input type="datetime-local" class="form-control" name="send_at">
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

<?php foreach ($template as $key => $value) :  ?>
    <div class="modal fade" id="edit-<?= $value->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal">Update Template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= base_url('pesan/template_update/' . $value->id) ?>" method="post">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" class="form-control" name="judul" value="<?= $value->type ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="pesan" class="form-label">Pesan</label>
                            <textarea class="form-control" name="pesan" id="pesan" rows="6" required><?= $value->text ?></textarea>
                        </div>
                        <div class="alert alert-info" role="alert">
                            Gunakan <strong>"{nama}"</strong> (tanpa petik) dan akan berisi nama dari penerima pesan.
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input toggle-input" name="use_image" type="checkbox" id="gambar" <?= $value->image ? 'checked' : '' ?>>
                            <label class="form-check-label" for="gambar">Memakai Gambar ?</label>
                        </div>
                        <div class="mb-3 toggle-target <?= $value->image ? '' : 'd-none' ?>">
                            <label for="">(isikan jika perlu update)</label>
                            <input type="file" class="form-control" name="image">
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input toggle-input" name="use_send_at" type="checkbox" id="jadwal" <?= $value->send_at ? 'checked' : '' ?>>
                            <label class="form-check-label" for="jadwal">Perlu Dijadwalkan ?</label>
                        </div>
                        <div class="mb-3 toggle-target <?= $value->send_at ? '' : 'd-none' ?>">
                            <input type="datetime-local" class="form-control" name="send_at" value="<?= $value->send_at ?>">
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
<?php endforeach; ?>
<script>
    document.querySelectorAll('.toggle-input').forEach((checkbox) => {
        checkbox.addEventListener('change', function() {
            const target = this.closest('.form-switch').nextElementSibling;
            if (target && target.classList.contains('toggle-target')) {
                target.classList.toggle('d-none', !this.checked);
            }
        });
    });
</script>