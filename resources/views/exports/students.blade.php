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
        <tr>
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
                @php
                $items = \App\Models\ItemUser::where('user_id', $student->id)->join('items', 'items.id', '=',
                'item_user.item_id')->join('modules', 'modules.id', '=',
                'items.module_id')->orderBy('modules.id')->orderBy('items.order', 'ASC')->select('item_user.*')->get();
                @endphp
                <tr>
                    <td>{{ $key + 1 }}</td>
                    @if ($items && count($items) > 0)
                        @foreach ($items as $userItem)
                            @if (!empty($userItem->value))
                                <td>{{ $userItem->value }}</td>
                            @else
                                <td></td>
                            @endif
                        @endforeach
                    @else
                        <td></td>
                    @endif
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
