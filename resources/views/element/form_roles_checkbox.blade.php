<div id="{{ $name }}" class="form-group form-item">
    <label class="col-sm-3 control-label no-padding-right form-label" for="{{ $name }}"> {{ $label }} </label>

    <div class="col-sm-5 form-val">
        <div id="roles-checkbox">
            @include('element._roles_checkbox', ['id' => 'roles-checkbox', 'name' => $name, 'role_ids' => $role_ids])
        </div>
    </div>
</div>
