<div class="row">
    <div class="col-md-12 mb-2">
        <div class="card">
            <div class="card-header bg-primary">
            </div>
            <div class="card-body pt-2 d-flex justify-content-between">
                <div>
                    <h2>Kirim Pesan Ulang Tahun</h2>
                    <button class="btn btn-warning disabled" data-bs-toggle="modal" data-bs-target="#kirimUlangTahun">
                        <i class="bi bi-cake"></i>
                        Ucapan Telah Dikirimkan Secara Otomatis
                    </button>
                </div>
                <img src="<?= base_url('public/icon2.svg') ?>" alt="Your SVG Image" class="img-fluid" width="180px">
            </div>
        </div>
    </div>
    <div class="col-md-12 mb-2">
        <div class="card">
            <div class="card-header bg-primary">
            </div>
            <div class="card-body pt-2 d-flex justify-content-between">
                <div>
                    <h2>Kirim Pesan Perorangan</h2>
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#kirimPerorangan">
                        <i class="bi bi-person"></i>
                        Kirim Ucapan
                    </button>
                </div>
                <img src="<?= base_url('public/icon2.svg') ?>" alt="Your SVG Image" class="img-fluid" width="180px">
            </div>
        </div>
    </div>
    <div class="col-md-12 mb-2">
        <div class="card">
            <div class="card-header bg-primary">
            </div>
            <div class="card-body pt-2 d-flex justify-content-between">
                <div>
                    <h2>Kirim Pesan Group Label</h2>
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#kirimGroupLabel">
                        <i class="bi bi-person"></i>
                        Kirim Ucapan
                    </button>
                </div>
                <img src="<?= base_url('public/icon2.svg') ?>" alt="Your SVG Image" class="img-fluid" width="180px">
            </div>
        </div>
    </div>
    <div class="col-md-12 mb-2">
        <div class="card">
            <div class="card-header bg-primary">
            </div>
            <div class="card-body pt-2 d-flex justify-content-between">
                <div>
                    <h2>Kirim Pesan Group</h2>
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#kirimGroup">
                        <i class="bi bi-people"></i>
                        Kirim Ucapan
                    </button>
                </div>
                <img src="<?= base_url('public/icon2.svg') ?>" alt="Your SVG Image" class="img-fluid" width="180px">
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="kirimPerorangan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kirim Perorangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('pesan/kirim_wa/0') ?>" method="post">
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Bulk</th>
                                <th>Nama</th>
                                <th>Nomor WA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($donatur as $key => $value) : ?>
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" name="notelp[<?= $key ?>]" id="" value="<?= $value->notelp ?>">
                                        <input type="hidden" name="nama[<?= $key ?>]" value="<?= $value->nama ?>">
                                    </td>
                                    <td><?= $value->nama ?></td>
                                    <td><?= $value->notelp ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3">
                                    <select name="template" class="form-control" id="" required>
                                        <option value="" disabled selected>Pilih Template Pesan</option>
                                        <?php foreach ($template as $k => $v) : ?>
                                            <option value="<?= $v->id ?>"><?= $v->type ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="kirimGroupLabel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kirim Perorangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('pesan/kirim_wa/0') ?>" method="post">
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Bulk</th>
                                <th>Group</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($labels as $key => $value) : ?>
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" name="labels[]" id="" value="<?= $value->id ?>">
                                    </td>
                                    <td><?= $value->type ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3">
                                    <select name="template" class="form-control" id="" required>
                                        <option value="" disabled selected>Pilih Template Pesan</option>
                                        <?php foreach ($template as $k => $v) : ?>
                                            <option value="<?= $v->id ?>"><?= $v->type ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="kirimGroup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kirim Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('pesan/kirim_wa/1') ?>" method="post">
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Bulk</th>
                                <th>Nama Group</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                function compareByName($a, $b) {
                                    return strcmp($a['name'], $b['name']);
                                }
                                usort($group, 'compareByName');
                            ?>
                            <?php foreach ($group as $key => $value) : ?>
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" name="notelp[<?= $key ?>]" value="<?= $value['id'] ?>">
                                        <input type="hidden" name="nama[<?= $key ?>]" value="<?= $value['name'] ?>">
                                    </td>
                                    <td><label for="checkboxg<?= $value['id'] ?>"></label><?= $value['name'] ?></label></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3">
                                    <select name="template" class="form-control" id="" required>
                                        <option value="" disabled selected>Pilih Template Pesan</option>
                                        <?php foreach ($template as $k => $v) : ?>
                                            <option value="<?= $v->id ?>"><?= $v->type ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>