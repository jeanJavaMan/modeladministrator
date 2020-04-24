<div class="{{$class ??"icheck-primary"}} d-inline">
    <input onclick="marcar_checkbox(this)" type="checkbox" name="{{$name ?? ""}}" id="{{$id ?? "checkboxPrimary1"}}" >
    <label for="{{$id ?? "checkboxPrimary1"}}">
        {{$slot}}
    </label>
</div>
