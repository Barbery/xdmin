<div class="search-item search-select" style="display: inline-block; margin-right: 10px;">
    <label class="search-label">{{ $label }}</label>
    <select name="{{ $name }}" class="chosen-select form-control">
        <option value="">请选择</option>
        @foreach($items as $id => $_name)
            <option value="{{ $id }}">{{ $_name }}</option>
        @endforeach
    </select>
</div>
