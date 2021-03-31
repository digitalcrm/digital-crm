<div>
    <div class="card-body p-0 table-responsive">
        <table id="example2" class="table" style="width:100%">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Prduct Category</th>
                    <th>Prduct SubCategory</th>
                    <th>Active</th>
                    <th>Created Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($product_of_interests as $poi)
                    <tr>
                        <td>
                            <a href="{{ route('product.interested',['productname'=>$poi->product_name]) }}" target="_blank">
                                {{ $poi->product_name }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('product.interested',['category'=>$poi->tbl_category->slug]) }}" target="_blank">
                                {{ $poi->tbl_category->category ?? '' }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('product.interested',['subcategory'=>$poi->tbl_sub_category->slug]) }}" target="_blank">
                                {{ $poi->tbl_sub_category->category ?? '' }}
                            </a>
                        </td>
                        <td>
                            <a href="#" wire:click="isActive({{ $poi->id }})" id="{{ $poi->id }}">
                                <i
                                    class="fas {{ ($poi->isActive) ? 'fa-toggle-on' : 'fa-toggle-off' }} fa-3x"></i>
                            </a>
                        </td>
                        <td>
                            {{ $poi->created_at->isoFormat('D/MM/Y') }}
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                </button>
                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item" data-toggle="modal" data-target="#poiModal"
                                        wire:click="editPoi({{ $poi->id }})">Edit</a>
                                    <small>
                                        <a class="dropdown-item" href="#" onclick="event.preventDefault();
                                if(confirm('Are you sure!')){
                                    $('#form-delete-{{ $poi->id }}').submit();
                                }
                                ">Delete</a>
                                        <form style="display:none" method="post" id="form-delete-{{ $poi->id }}"
                                            action="{{ route('pois.destroy',$poi->id) }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('delete')
                                        </form>
                                    </small>
                                </div>
                            </div>
                        </td>
                    @empty
                        <td colspan="15" class="text-center">No POIs Available yet!</td>
                @endforelse
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="poiModal" tabindex="-1" aria-labelledby="poiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="poiModalLabel">Add New</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @if($this->wantsToEdit)
                    <form wire:submit.prevent="editSave">
                    @else
                        <form wire:submit.prevent="store">
                @endif
                <div class="modal-body">
                    <div class="form-group">
                        <label for="product_name">Product Name</label>
                        <input wire:model="product_name" type="text" class="form-control required" id="product_name"
                            placeholder="eg: shoes">
                        @error('product_name')
                            <span class="d-block text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="product_category_id">Category</label>
                        <select class="form-control required" wire:model="product_category_id"
                            name="product_category_id" id="product_category_id">
                            <option value="0">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->procat_id }}">{{ $cat->category }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="sub_category_id">SubCategory</label>
                        <select class="form-control required" wire:model="sub_category_id" name="sub_category_id"
                            id="sub_category_id">
                            <option value="">select subcategory</option>
                            @foreach($subcategories as $subcat)
                                <option value="{{ $subcat->prosubcat_id }}">{{ $subcat->category }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
