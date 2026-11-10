@props(['name','label','type'=>'text'])
<div>
    <label class="block text-sm font-medium mb-1" for="{{ $name }}">{{ $label }}</label>
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" value="{{ old($name) }}" required
           class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 outline-none">
    @error($name)<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
</div>
