<table class="table">
    <thead>
        <tr>
            <th height="30" style="vertical-align: middle; font-size: 14px; font-weight: bold">SL.NO</th>
            <th height="30" style="vertical-align: middle; font-size: 14px; font-weight: bold">NAME</th>
            <th height="30" style="vertical-align: middle; font-size: 14px; font-weight: bold">ENROLL NO</th>
            <th height="30" style="vertical-align: middle; font-size: 14px; font-weight: bold">DOB</th>
            @foreach ($modules as $module)
                <th height="30" style="text-align:center;vertical-align: middle;font-size: 16px; font-weight: bold"
                    colspan="{{ count($module->items) }}">
                    {{ strtoupper($module->name) }}
                </th>
            @endforeach
        </tr>
        <tr style="border-bottom: 2px solid #000000; vertical-align: middle">
            <th></th>
            <th></th>
            <th></th>
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
            {{-- 
                If student has data which belong to repeatable module
                Else    
            --}}
                @if ($student->itemGroups && count($student->itemGroups) > 1)
                    @foreach ($student->itemGroups as $itemGroupKey => $itemGroup)
                    <tr>
                        @if ($itemGroupKey == 0)
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ is_numeric($student->enroll_no) ? (int)$student->enroll_no : $student->enroll_no }}</td>
                            <td data-format="dd/mm/yyyy">{{ $student->dob_formatted }}</td>
                        @else
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        @endif
                        @foreach ($modules as $module3)
                            @if ($module3->itemUsers()->exists())
                                @if ($module3->isRepeatable())
                                    @if ($itemGroup->module_id == $module3->id && $itemGroup->itemUsers)
                                        @foreach ($itemGroup->orderedItemUsers() as $itemUser)
                                            <td>{{ $itemUser->getValue() }}</td>
                                        @endforeach
                                    @else
                                        <td colspan="{{ count($module3->items) }}"></td>
                                    @endif
                                @elseif(!$module3->isRepeatable())
                                    @foreach ($module3->orderedItemUsers($student->id) as $itemUser)
                                        @if ($itemGroupKey == 0)
                                            <td>{{ $itemUser->getValue() }}</td>
                                        @else
                                            <td></td>
                                        @endif
                                    @endforeach
                                @else
                                    <td></td>
                                @endif
                            @else
                                <td colspan="{{ count($module3->items) }}"></td>
                            @endif
                        @endforeach
                    </tr>
                    @endforeach
                @else
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ is_numeric($student->enroll_no) ? (int)$student->enroll_no : $student->enroll_no }}</td>
                    <td data-format="dd/mm/yyyy">{{ $student->dob_formatted }}</td>
                    @foreach ($modules as $module4)
                        @if ($module4->itemUsers()->exists())
                            @if ($module4->isRepeatable())
                                @php
                                // get latest group
                                $itemGroup = $module4->itemGroups()->where('user_id', $student->id)->latest()->first();
                                @endphp
                                @if ($itemGroup && $itemGroup->itemUsers)
                                    @foreach ($itemGroup->orderedItemUsers() as $itemUser)
                                        <td>{{ $itemUser->getValue() }}</td>
                                    @endforeach
                                @else
                                    <td colspan="{{ count($module4->items) }}"></td>
                                @endif
                            @else
                                @foreach ($module4->orderedItemUsers($student->id) as $itemUser)
                                    <td>{{ $itemUser->getValue() }}</td>
                                @endforeach
                            @endif
                        @else
                            <td></td>
                        @endif
                    @endforeach
                </tr>
                @endif
            @endforeach
        @endif
    </tbody>
</table>
