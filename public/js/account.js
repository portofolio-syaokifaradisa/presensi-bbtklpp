document.addEventListener("DOMContentLoaded", function(){
    document.getElementById('print').addEventListener('click', function(event){
        event.preventDefault();

        var params = '?';
        const forms = ["name", 'identitas', 'jabatan', 'status'];
        for(var name of forms){
            var formValue = document.getElementById(`${name}-form`).value;
            params += `${name}=${formValue}`;
            if(forms.indexOf(name) !== (forms.length - 1)){
                params += '&';
            }
        }

        window.open(`${window.location.href}/print${params}`, '_blank');
    });

    const table = $("#datatable").dataTable({
        processing: true,
        serverSide: true,
        searching: true,
        dom: 'Brtip',
        ajax : `${window.location.href}/datatable`,
        columns: [
            { data: 'no', name: 'no', orderable: false, searchable: false },
            { 
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false,
                render: function(_, type, account, meta){

                    // Pengambilan Base URL
                    const baseUrl = window.location.href;

                    // HTML Builder untuk Tombol Aksi
                    return `
                        <div class="btn-group dropright px-0 pr-2">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-cog"></i>
                            </button>
                            <div class="dropdown-menu dropright">
                                <a class="dropdown-item has-icon" href="${baseUrl}/${account.id}/edit">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </a>
                                <a class="dropdown-item has-icon btn-delete" href="#" id="btn-delete-${account.id}">
                                    <i class="fas fa-trash"></i>
                                    Hapus
                                </a>
                            </div>
                        </div>
                    `;
                }
            },
            { 
                data: 'name',
                name: 'name',
                render: function(_, type, account, meta){
                    return `
                        ${account.name} ${account.gelar} <br>
                        NIP. ${account.nip} <br>
                        ${account.email} 
                    `;
                }
            },
            { 
                data: 'identitas',
                name: 'identitas',
                render: function(_, type, account, meta){
                    return `
                        NIK ${account.nik} <br>
                        TTL ${account.tanggal_lahir} <br>
                        Pendidikan ${account.pendidikan} <br>
                        Alamat ${account.alamat}
                    `;
                }
            },
            { 
                data: 'jabatan',
                name: 'jabatan',
                render: function(_, type, account, meta){
                    return `
                        ${account.jabatan} <br>
                        ${account.pangkat} 
                    `;
                }
            },
            { data: 'status', name: 'status'},
        ], 
        createdRow: function( row, data, dataIndex ){
            for(var i = 1; i <= 6; i++){
                $(row).children(`:nth-child(${i})`).addClass(`${[1, 6].includes(i) ? "text-center" : ""} align-middle`);
            }
        },
        initComplete: function () {
            this.api().columns().every(function () {
                var table = this;
                $('input', this.footer()).on('keyup change clear', function () {
                    table.search(this.value).draw();
                });

                $('select', this.footer()).on('keyup change clear', function () {
                    table.search(this.value).draw();
                });
            });
        }
    }); 

    // Pembuatan Individual Search Pada Bagian Footer
    $('#datatable tfoot th').each(function (index) {
        var name = $(this).attr('id');
    
        if(['name', 'jabatan','status', 'identitas'].includes(name)){
            $(this).html(`
                <div class="form-group mb-0 pr-4">
                    <input type="text" class="form-control text-center" name="${name}" id="${name}-form">
                </div>
            `);
        }else if(["no", 'action'].includes(name)){
            $(this).html('');
        }
    });

    // Inisialisasi Ulang Ketika Datatable di Refresh
    $('#datatable').on('draw.dt', function (datatable) {
        // Inisialisasi Event Batalkan Order
        const cancelButtons = datatable.target.getElementsByClassName('btn-delete');
        for(let button of cancelButtons){
            button.addEventListener('click', function(e){
                e.preventDefault();
                var accountId = e.target.id.split('-')[2];

                console.log(accountId);
                confirmAlert(
                    "Konfirmasi Hapus Akun",
                    "Apakah Anda Yakin ingin Menghapus Akun?",
                    async function(){
                        const response = await fetch(
                            `${window.location.href}/${accountId}/delete`,
                            { method: "GET", headers: {'Content-Type': 'application/json'}}
                        );
            
                        var {status, title, message} = await response.json();
                        Swal.fire({icon: status, title: title, text: message});
                        if(status == 'success'){
                            table.api().ajax.reload(null, false);
                        }
                    }
                );
            });
        }
    });
});