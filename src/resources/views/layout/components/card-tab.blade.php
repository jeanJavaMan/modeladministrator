<div class="card card-primary card-tabs">
    <div class="card-header p-0 pt-1">
        <ul class="nav nav-tabs" role="tablist">
            {{$menu ?? ""}}
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            {{$slot}}
        </div>
    </div>
    <!-- /.card -->
</div>
