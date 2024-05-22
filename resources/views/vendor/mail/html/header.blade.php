@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'Laravel')
                <img src="https://photos.google.com/photo/AF1QipOjhbK1udyE8YTdiw2b1Ce8bne_Dm13W4bSNbk" class="logo"
                    alt="Laravel Logo">
            @else
                {{ $slot }}
            @endif
        </a>
    </td>
</tr>
