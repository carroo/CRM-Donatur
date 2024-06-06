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
                        <th>Nama</th>
                        <th>Foto</th>
                        <th>Pesan 1</th>
                        <th>Pesan 2</th>
                        <th>Pesan 3</th>
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
                            <td><?= $value->nama ?></td>
                            <td><img src="<?= base_url('public/donatur/') ?><?= $value->foto ?? 'image.png' ?>" alt="" width="100px" srcset=""></td>
                            <td>
                                <?php if ($pesan[$key]) : ?>
                                    <?php if (!$pesan[$key]->pesan1) : ?>
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#kirim-<?= $value->id ?>" title="kirim">
                                            <i class="bi bi-send"></i> Kirim
                                        </button>
                                    <?php else : ?>
                                        <span class="badge bg-success">Sudah</span>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#kirim-<?= $value->id ?>" title="kirim">
                                        <i class="bi bi-send"></i> Kirim
                                    </button>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($pesan[$key]) : ?>
                                    <?php if ($pesan[$key]->pesan1 && !$pesan[$key]->pesan2) : ?>
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#kirim-<?= $value->id ?>" title="kirim">
                                            <i class="bi bi-send"></i> Kirim
                                        </button>
                                    <?php else : ?>
                                        <span class="badge bg-success">Sudah</span>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <span class="badge bg-warning">Belum</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($pesan[$key]) : ?>
                                    <?php if ($pesan[$key]->pesan1 && $pesan[$key]->pesan2 && !$pesan[$key]->pesan3) : ?>
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#kirim-<?= $value->id ?>" title="kirim">
                                            <i class="bi bi-send"></i> Kirim
                                        </button>
                                    <?php elseif ($pesan[$key]->pesan1 && !$pesan[$key]->pesan2 && !$pesan[$key]->pesan3) : ?>
                                        <span class="badge bg-warning">Belum</span>
                                    <?php else : ?>
                                        <span class="badge bg-success">Sudah</span>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <span class="badge bg-warning">Belum</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
        <?php foreach ($data as $key => $value) : ?>
            <div class="modal fade" id="kirim-<?= $value->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">kirim Pesan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="<?= base_url('qurban/kirim/' . $value->id) ?>" method="post" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input toggle-input" name="use_template" value="1" type="checkbox" onchange="toggleTemplate(this)">
                                    <label class="form-check-label" for="use_template">Gunakan Template Pesan ?</label>
                                </div>
                                <div class="nontmp">
                                    <div class="mb-3">
                                        <label for="text" class="form-label">Text</label>
                                        <textarea name="text" name="text" rows="3" id="" class="form-control"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="foto" class="form-label">Foto (opsional)</label>
                                        <input type="file" name="foto" class="form-control foto-input" id="foto" onchange="previewImage(this)">
                                    </div>
                                    <div id="preview-container" class="mt-3"></div>
                                </div>
                                <div class="d-none tmp">
                                    <div class="mb-3">
                                        <select name="template" class="form-control" id="pt">
                                            <option value="" disabled selected>Pilih Template Pesan</option>
                                            <?php foreach ($template as $k => $v) : ?>
                                                <option value="<?= $v->id ?>"><?= $v->type ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
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
        <?php endforeach ?>

    </div>
</div>
<script>
    function previewImage(input) {
        var previewContainer = input.parentElement.nextElementSibling;
        var files = input.files;

        // Hapus preview gambar sebelumnya jika ada
        previewContainer.innerHTML = '';

        // Jika pengguna memilih satu atau lebih gambar
        if (files.length > 0) {
            // Loop melalui setiap file gambar yang dipilih
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var reader = new FileReader();

                // Saat file telah dibaca, buat elemen gambar untuk preview
                reader.onload = function(e) {
                    var imgElement = document.createElement('img');
                    imgElement.setAttribute('src', e.target.result);
                    imgElement.setAttribute('class', 'img-thumbnail');
                    previewContainer.appendChild(imgElement);
                }

                // Baca file gambar sebagai URL data
                reader.readAsDataURL(file);
            }
        }
    }
</script>
<script>
    function toggleTemplate(checkbox) {
        const modalBody = checkbox.closest('.modal-body');
        const nontmp = modalBody.querySelector('.nontmp');
        const tmp = modalBody.querySelector('.tmp');

        if (checkbox.checked) {
            nontmp.classList.add('d-none');
            tmp.classList.remove('d-none');
        } else {
            nontmp.classList.remove('d-none');
            tmp.classList.add('d-none');
        }
    }
</script>