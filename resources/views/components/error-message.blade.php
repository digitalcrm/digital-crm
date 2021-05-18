@props(['value'])

@error($value)
    <div class="text-danger">{{ $message }}</div>
@enderror
