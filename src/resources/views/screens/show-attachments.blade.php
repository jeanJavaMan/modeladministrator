@php
    /**@var \Jeanderson\modeladministrator\Models\CustomModel $model*/
    /**@var \Jeanderson\modeladministrator\Models\ModelConfig $modelConfig*/
    $route_delete_attachment = $modelConfig->routes_cache()->first(function ($item){return $item->type == "attachment_delete";});
@endphp
@component("modeladmin::layout.components.table")
    @slot("table_head")
        <tr style="background: #535353;color: white;">
            <th>{{__("modeladminlang::default.attachments")}}</th>
            <th>{{__("modeladminlang::default.options")}}</th>
        </tr>
    @endslot
    @slot("table_body")
        @foreach($model->attachments()->get() as $attachment)
            <tr>
                <td><a target="_blank" href="{{$attachment->url}}">{{$attachment->title}}</a></td>
                <td>
                    <button type="button" onclick="delete_attachment('#attachment_{{$attachment->key}}');" class="btn btn-danger"><i class="fas fa-trash"> {{__("modeladminlang::default.delete")}}</i>
                    </button>
                    <a href="./{{$route_delete_attachment->url}}?key={{$attachment->key}}&id={{$model->id}}" id="attachment_{{$attachment->key}}"></a>
                </td>
            </tr>
        @endforeach
    @endslot
@endcomponent
