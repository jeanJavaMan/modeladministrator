<table {{empty($id) ? "":"id=$id"}} {!! empty($attribute) ? "":$attribute !!} class="table table-striped table-bordered">
    <thead>
    {{$table_head ?? ""}}
    </thead>
    <tbody>
    {{$table_body ?? ""}}
    </tbody>
</table>
