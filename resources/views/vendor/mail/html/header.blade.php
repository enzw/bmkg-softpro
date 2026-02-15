@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block; text-align: center;">
            <img src="https://www.bmkg.go.id/images/profil/logo-bmkg.png" alt="BMKG"
                style="height: 100px; display: block; margin: 0 auto;">
            <br>
            <span
                style="font-size: 16px; font-weight: bold; color: #16a34a; display: block; margin-top: 8px;">{{ config('app.name') }}</span>
        </a>
    </td>
</tr>