<table class="table">
    <thead>
        <tr>
            <th>SL.NO</th>
            @foreach ($modules as $module)
                <th height="30" style="text-align:center;vertical-align: middle;font-size: 16px; font-weight: bold"
                    colspan="{{ count($module->items) }}">
                    {{ strtoupper($module->name) }}
                </th>
            @endforeach
        </tr>
        <tr style="border-bottom: 2px solid #00000; vertical-align: middle">
            <th></th>
            @foreach ($modules as $module2)
                @if (count($module2->items) > 0)
                    @foreach ($module2->items as $item)
                        <th height="20" style="vertical-align: middle">{{ strtoupper($item->label) }}</th>
                    @endforeach
                @else
                    <th></th>
                @endif
            @endforeach
        </tr>
    </thead>
    <tbody>
        @if (count($students) > 0)
            @foreach ($students as $key => $student)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    @foreach ($modules as $module3)
                        @if ($module3->itemUsers()->exists())
                            @if ($module3->isRepeatable())
                                @php
                                // get latest group
                                $itemGroup = $module3->itemGroups()->where('user_id', $student->id)->latest()->first();
                                @endphp
                                @if ($itemGroup && $itemGroup->itemUsers)
                                    @foreach ($itemGroup->orderedItemUsers() as $itemUser)
                                        <td>{{ $itemUser->getValue() }}</td>
                                    @endforeach
                                @endif
                            @else
                                @foreach ($module3->orderedItemUsers() as $itemUser)
                                    <td>{{ $itemUser->getValue() }}</td>
                                @endforeach
                            @endif
                        @else
                            <td></td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        @endif
    </tbody>
</table>