<table class="table">
    <thead>
        <tr>
            <th>SL.NO</th>
            <th>Name</th>
            <th>DH.Batch</th>
            <th>Enroll.No</th>

            @if ($verifications && count($verifications) > 0)
                @foreach ($verifications as $verification)
                    <th>{{ $verification->name }}</th>
                @endforeach
            @endif
        </tr>
    </thead>
    <tbody>
        @if ($students && count($students) > 0)
            @foreach ($students as $student)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $student->getItemByKey('name-of-applicant') }}</td>
                    <td>{{ $student->batch->name }}</td>
                    <td>{{ $student->enroll_no }}</td>

                    @if ($student->verifications)
                        @foreach ($student->verifications as $verifi)
                            <td>
                                @if ($verifi->pivot->status == 1)
                                    Yes
                                @else
                                    No
                                @endif
                            </td>
                        @endforeach
                    @endif
                </tr>
            @endforeach
        @endif
    </tbody>
</table>