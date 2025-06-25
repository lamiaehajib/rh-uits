@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'UITS')
<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSASmWOzmLDcQQu2Rg3OAIHGZp82Hbh4f-Fig&s" class="logo" alt="Laravel Logo"  width="100%">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
