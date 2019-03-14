<div class="search-{{ $name }} search-item">
    <label for="{{ $name }}" class="search-label">{{ $label }}：</label>
    <input id="{{ $name }}" data-operator="{{ $operator ?? '' }}" type="text" class="search-val form-control" name="{{ $name }}" value="{{ $val ?? '' }}" placeholder="{{ $label }}" />
</div>
