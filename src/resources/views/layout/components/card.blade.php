<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="{{$icon ?? ""}}"></i>
                {{$title ?? ""}}
            </h3>
            {!!$header ?? "" !!}
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            {{$slot}}
        </div>
        <!-- /.card-body -->
    </div>
</div>
