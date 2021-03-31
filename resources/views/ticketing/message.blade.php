@if(Session::has('message'))
    <script>
        $(function () {
            toastr.options = {
                "positionClass": "toast-top-left",
                "progressBar": true,
                "debug": false,
            }
            toastr.success('{{ Session::get('message') }}')
        });
    </script>
@endif

@if (Session::has('errors'))
    <script>
        $(function () {
            toastr.options = {
                "positionClass": "toast-top-left",
                "progressBar": true,
                "debug": false,
            }
            toastr.error('{{ Session::get('errors') }}')
        });
    </script>
@endif
