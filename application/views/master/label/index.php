<div class="col-12">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="text-dark mb-0">Table Master Label</h2>
            <div class="btn-group">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImport">
                    <i class="bi bi-file-arrow-up"></i>
                    Import
                </button>
                <button id="exportExcel" class="btn btn-info">
                    <i class="bi bi-file-arrow-down"></i>
                    Export Excel
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="bi bi-plus-circle"></i>
                    Tambah label
                </button>
            </div>
        </div>


        <div class="card-body p-4">
            <table class="table table-bordered table-hover datatable pt-4">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Label</th>
                        <th>Row Active</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $key => $value) : ?>

                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= $value->type ?></td>
                            <td class="text-center">
                                <span class="badge <?= $value->row_active ? 'bg-success' : 'bg-danger' ?>"><?= $value->row_active ? 'Aktif' : 'Tidak Aktif' ?></span>
                                <br>
                                <div class="btn-group mt-2" role="group" aria-label="Basic example">
                                    <?php
                                    $linkaktif = base_url('master/label_status/1/' . $value->id);
                                    $linktidakaktif = base_url('master/label_status/0/' . $value->id);
                                    ?>
                                    <a href="#" onclick="confirmAlert('<?= $linkaktif ?>','Apa anda yakin ingin mengaktifkan?')" class="btn <?= $value->row_active ? 'btn-success' : 'btn-outline-success' ?> btn-sm" title="aktif">
                                        <i class="bi bi-patch-check"></i>
                                    </a>
                                    <a href="#" onclick="confirmAlert('<?= $linktidakaktif ?>','Apa anda yakin ingin menonaktifkan?')" class="btn <?= !$value->row_active ? 'btn-danger' : 'btn-outline-danger' ?> btn-sm" title="tidak aktif">
                                        <i class="bi bi-x-octagon"></i>
                                    </a>
                                </div>
                            </td>
                            <td class="">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit-<?= $value->id ?>" title="update">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <?php
                                    $linkhapus = base_url('master/label_hapus/' . $value->id);
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
                        <h5 class="modal-title" id="exampleModalLabel">Tambah label</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="<?= base_url('master/label_tambah') ?>" method="post">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="type" class="form-label">label</label>
                                <input type="text" class="form-control" name="type" required>
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
        <div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" name="fileImport" id="fileImport">
                            <a href="<?php echo base_url('public/format/format_label.xlsx') 
                                        ?>" target="_blank">
                                <span class="input-group-text" id="basic-addon2">
                                    Download Format
                                </span>
                            </a>
                        </div>
                    </div>
                    <form action="<?php echo base_url('master/label_tambah_batch') 
                                    ?>" method="post">
                        <div class="modal-body">
                            <table id="excelData" class="table ">
                            </table>
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
                            <h5 class="modal-title" id="exampleModalLabel">Update label</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="<?= base_url('master/label_update/' . $value->id) ?>" method="post">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="type" class="form-label">label</label>
                                    <input type="text" class="form-control" name="type" value="<?= $value->type ?>" required>
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
    document.getElementById('fileImport').addEventListener('change', function(e) {
        var file = e.target.files[0];
        var reader = new FileReader();
        reader.onload = function(e) {
            var data = new Uint8Array(e.target.result);
            var workbook = XLSX.read(data, {
                type: 'array'
            });
            var sheetName = workbook.SheetNames[0];
            var sheet = workbook.Sheets[sheetName];
            var excelData = XLSX.utils.sheet_to_json(sheet);

            // Empty the table first
            var table = document.getElementById('excelData');
            table.innerHTML = '';
            var trh = document.createElement('tr');
            trh.innerHTML = '<th>label</th><th>pakai lampiran?</th>';
            table.appendChild(trh);

            // Loop through each row of Excel data
            excelData.forEach(function(row) {
                console.log(row);
                var tr = document.createElement('tr');
                var tdlabel = document.createElement('td');
                var tdPakaiLampiran = document.createElement('td');

                // Assign values from Excel to input fields
                tdlabel.innerHTML = '<input type="text" class="form-control" style="width:70%;"  name="label[]" value="' + row.label + '">';
                tdPakaiLampiran.innerHTML = '<div class="form-check form-switch"><input class="form-check-input" name="pakai_lampiran[]"  type="checkbox" ' + (row.Lampiran === 'Ya' ? 'checked' : '') + ' value="1"><label class="form-check-label" for="pakai_lampiran[]">Memakai Lampiran?</label></div>';

                // Append the input fields to the table row
                tr.appendChild(tdlabel);
                tr.appendChild(tdPakaiLampiran);

                // Append the table row to the table
                table.appendChild(tr);
            });
        };
        reader.readAsArrayBuffer(file);
    });
</script>