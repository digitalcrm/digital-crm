<div>
    <div class="card shadow card-primary card-outline">
        <div class="card-header">
        </div>

        <div class="card-body p-0" id="leadsTableDiv">

            {!! $data['table'] !!}

        </div>
        <div class="card-footer bg-white border-top pull-left">
            <div class="btn-group btn-flat pull-left">
                <button class="btn btn-sm btn-outline-secondary" id="selectAll" onclick="table_row_color_change();"><i
                        class="fas fa-check"></i> Select All</button>
                <button class="btn btn-sm text-danger btn-outline-secondary" onclick="return deleteAll();"><i
                        class="far fa-trash-alt"></i></button>
            </div>
        </div>

    </div>
</div>
