<div class="row">
    <div class="row">
        @foreach ($products as $k => $formdetails)
        <?php
        $img = ($formdetails->picture) ? $formdetails->picture : '/uploads/default/products.jpg';
        ?>
        <div class="col-lg-3 col-md-4 mb-3">
            <div class="card h-100">
                <a href="{{url('consumers/ajax/getproductdetails/'.$formdetails->pro_id)}}" target="_blank"><img class="card-img-top" src="{{url($img)}}" alt="" width="301" height="172"></a>
                <div class="card-body">
                    <h4 class="card-title">
                        <a href="{{url('consumers/ajax/getproductdetails/'.$formdetails->pro_id)}}" target="_blank">{{$formdetails->name}}</a>
                    </h4>
                    <h5>{{$formdetails->price}}</h5>
                    <p class=" card-text">{{substr($formdetails->description, 0, 10)}}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>
<div class="text-center">
    {!!$products->links()!!}
</div>