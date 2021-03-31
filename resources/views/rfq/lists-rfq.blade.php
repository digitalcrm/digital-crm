@extends('layouts.adminlte-boot-4.front-main')

@section('title', 'RFQ Lists')

@section('content')

<div class="container mb-4">
    <div class="row">
        <div class="col">
            <h3 class="text-center">
                Most Recent RFQs from Various Industries
            </h3>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('lists.rfq') }}">All RFQ</a></li>
                    @if(request('category'))
                        <li class="breadcrumb-item">
                            <a
                                href="{{ route('lists.rfq',['category'=>request('category')]) }}">{{ request('category') }}</a>
                        </li>
                    @elseif(request('subcategory'))
                        <li class="breadcrumb-item">
                            <a
                                href="{{ route('lists.rfq',['subcategory'=>request('subcategory')]) }}">{{ request('subcategory') }}</a>
                        </li>
                    @endif
                </ol>
            </nav>
        </div>
        <div class="col-md-3">
            <div class="dropdown float-right">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Category
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    @forelse ($productCategory as $cat)
                        <a class="dropdown-item" href="{{ route('lists.rfq',['category'=>$cat->category]) }}">{{ $cat->category }}</a>
                    @empty
                        <a class="dropdown-item" href="#">No category found!</a>
                    @endforelse
                </div>
            </div>
        </div>
        @forelse($rfqs as $rfq)
            <div class="col-md-6 mb-2">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            @foreach($rfq->GetSingleImage as $img)
                            @if($img->fileType())
                                <div class="col-3">
                                    <img src="{{ $img->imageUrl() }}" class="img-fluid rounded-circle"
                                        style="width: 3rem;" alt="{{ $img->file_name }}">
                                </div>
                            @endif
                            @endforeach
                            <div class="col-9">
                                <h5 class="card-title">
                                    <a href="{{ route('details.rfq', $rfq->id) }}">
                                        {{ $rfq->product_name }}
                                    </a>
                                </h5>
                                <p class="card-text mb-0">
                                    {{ ($rfq->city) ? 'Destination: '.$rfq->city : '' }}
                                </p>
                                <span class="m-0 d-block">Category:
                                    <a
                                        href="{{ route('lists.rfq',['category'=>$rfq->tbl_category->category]) }}">
                                        {{ $rfq->tbl_category->category }}
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span class="text-muted">
                            {{ $rfq->created_at->diffForHumans() }}
                        </span>
                        <a href="{{ route('rfq-forms.create') }}"
                            class="btn btn-sm btn-outline-secondary mx-1 float-right">
                            Try RFQ
                        </a>
                        <a href="{{ route('details.rfq', $rfq->id) }}"
                            class="btn btn-sm btn-primary float-right mx-1">Quote Now</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">No RFQ Found</h5>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
    <div class="d-flex justify-content-center m-4">
        {{ $rfqs->links() }}
    </div>
</div>

@endsection
