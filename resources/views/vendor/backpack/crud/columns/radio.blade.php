@php
	$keyName = isset($column['key']) ? $column['key'] : $column['name'];
	$entryValue=$entry->{$keyName};
	$displayValue = isset($column['options'][$entryValue]) ? $column['options'][$entryValue] : '';
	$html = isset($column['html']) && $column['html'] ? 1 : 0;
@endphp

<span>
	@if ($html) {!! $displayValue !!} @else {{ $displayValue }} @endif
</span>