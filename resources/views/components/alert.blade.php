@if(session('success'))
<div class='alert alert-success'>
    {{session('success')}}
</div>
@endif

@if(session('error'))
<div class='alert alert-danger'>
    {{session('error')}}
</div>
@endif

@if(session('info'))
<div class='alert alert-warning'>
    {{session('info')}}
</div>
@endif

@if (session()->has('message'))
<div class="alert alert-success">
    {{ session('message') }}
</div>
@endif
