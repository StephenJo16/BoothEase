<!-- resources/views/components/table.blade.php -->
@props([
'headers' => [],
'rows' => [],
'tableClass' => 'w-full',
'containerClass' => 'overflow-x-auto'
])

<div class="{{ $containerClass }}">
    <table class="{{ $tableClass }}">
        <!-- Table Headers -->
        <thead>
            <tr class="border-b border-gray-200">
                @foreach($headers as $header)
                @php
                $customClass = trim($header['class'] ?? '');
                $alignmentClass = $customClass === '' ? 'text-left' : $customClass;
                @endphp
                <th class="py-3 px-4 font-medium text-gray-700 {{ $alignmentClass }}">
                    {{ $header['title'] }}
                </th>
                @endforeach
            </tr>
        </thead>

        <!-- Table Body -->
        <tbody class="divide-y divide-gray-200">
            @foreach($rows as $row)
            <tr class="{{ $row['rowClass'] ?? 'h-20' }}">
                @foreach($row['cells'] as $cell)
                <td class="py-4 px-4 align-middle {{ $cell['class'] ?? '' }}">
                    {!! $cell['content'] !!}
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
