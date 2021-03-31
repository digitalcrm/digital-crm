@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
@if (session('error'))
<div class="alert alert-success">
    {{ session('error') }}
</div>
@endif
@if (session('info'))
<div class="alert alert-success">
    {{ session('info') }}
</div>
@endif
@if (session('warning'))
<div class="alert alert-success">
    {{ session('warning') }}
</div>
@endif
{{-- <script>
    $(function(){

        if(session('success')) {
            toastr.success('{{ session('success') }}')
        },

        $('.toastrDefaultInfo').click(function() {
            toastr.info('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
        });
        $('.toastrDefaultError').click(function() {
            toastr.error('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
        });
        $('.toastrDefaultWarning').click(function() {
            toastr.warning('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
        });

    });
</script> --}}














@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
