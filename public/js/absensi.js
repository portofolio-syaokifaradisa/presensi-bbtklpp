document.addEventListener("DOMContentLoaded", function(){
    document.getElementById('print').addEventListener('click', function(event){
        event.preventDefault();

        var params = '?';
        const forms = ["hari", "pegawai", "tanggal_start", "tanggal_end", 'masuk', 'keluar', 'status', 'keterangan'];
        for(var name of forms){
            console.log(name);
            var formValue = document.getElementById(`${name}-form`).value;
            params += `${name}=${formValue}`;
            if(forms.indexOf(name) !== (forms.length - 1)){
                params += '&';
            }
        }

        window.open(`${window.location.href}/print${params}`, '_blank');
    });

    document.getElementById('print-late').addEventListener('click', function(event){
        event.preventDefault();

        var params = '?';
        const forms = ["pegawai", "hari", "tanggal_start", "tanggal_end"];
        for(var name of forms){
            var formValue = document.getElementById(`${name}-form`).value;
            params += `${name}=${formValue}`;
            if(forms.indexOf(name) !== (forms.length - 1)){
                params += '&';
            }
        }

        window.open(`${window.location.href}/print-late${params}`, '_blank');
    });

    document.getElementById('summary').addEventListener('click', function(event){
        event.preventDefault();
        
        var params = '?';
        const forms = ["tanggal_start", "tanggal_end"];
        for(var name of forms){
            var formValue = document.getElementById(`${name}-form`).value;
            params += `${name}=${formValue}`;
            if(forms.indexOf(name) !== (forms.length - 1)){
                params += '&';
            }
        }

        window.open(`${window.location.href}/summary${params}`, '_blank');
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
                data: 'pegawai', 
                name: 'pegawai',
                render: function(_, type, absensi, meta){
                    return `
                        ${absensi.nama} <br>
                        NIP.${absensi.nip}
                    `;
                }
            },
            { data: 'hari', name: 'hari'},
            { data: 'tanggal', name: 'tanggal'},
            { data: 'status', name: 'status'},
            { data: 'masuk', name: 'masuk'},
            { data: 'keluar', name: 'keluar'},
            { data: 'keterangan', name: 'keterangan'},
        ], 
        createdRow: function( row, data, dataIndex ){
            for(var i=1; i <= 8; i++){
                $(row).children(`:nth-child(${i})`).addClass(`${[2, 8].includes(i) ? "" : "text-center"} align-middle`);
            }
        },
        initComplete: function () {
            this.api().columns().every(function () {
                var table = this;
                $('input', this.footer()).on('keyup change clear', function (event) {
                    var input = this.value;
                    if(event.target.name.includes("tanggal")){
                        input = `${document.getElementById('tanggal_start-form')?.value ?? ''}|${document.getElementById('tanggal_end-form')?.value}`;
                    }

                    table.search(input).draw();
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
    
        if(name === "tanggal"){
            $(this).html(`
                <div class="form-group mb-0">
                    <input type="date" class="form-control text-center" name="${name}_start" id="${name}_start-form">
                </div>
                <div class="form-group mb-0 mt-2">
                    <input type="date" class="form-control text-center" name="${name}_end" id="${name}_end-form">
                </div>
            `);
        }else if(name === "hari"){
            $(this).html(`
                <div class="form-group" style="width: 100%;">
                    <select class="form-control select2 category-select text-center" name="${name}" id="${name}-form" data-index="${index}">
                        <option value="SEMUA">Semua</option>
                        <option value="Monday">Senin</option>
                        <option value="Tuesday">Selasa</option>
                        <option value="Wednesday">Rabu</option>
                        <option value="Thursday">Kamis</option>
                        <option value="Friday">Jumat</option>
                    </select>
                </div>
            `);
        }else if(name == "masuk" || name == "keluar"){
            $(this).html(`
                <div class="form-group mb-0 pr-4">
                    <input type="time" class="form-control text-center" name="${name}" id="${name}-form">
                </div>
            `);
        }else if(name === "pegawai" || name === "keterangan"){
            $(this).html(`
                <div class="form-group mb-0 pr-4">
                    <input type="text" class="form-control text-center" name="${name}" id="${name}-form">
                </div>
            `);
        }else if(name === "status"){
            $(this).html(`
                <div class="form-group pr-4" style="width: 100%;">
                    <select class="form-control select2 category-select text-center" name="${name}" id="${name}-form" data-index="${index}">
                        <option value="SEMUA">Semua</option>
                        <option value="Hadir">Hadir</option>
                        <option value="Izin">Izin</option>
                        <option value="Cuti">Cuti</option>
                        <option value="Dinas Luar">Dinas Luar</option>
                    </select>
                </div>
            `);
        }else{
            $(this).text('');
        }
    });
});