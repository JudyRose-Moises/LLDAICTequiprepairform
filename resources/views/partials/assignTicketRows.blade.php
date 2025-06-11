@foreach($tickets as $ticket)
<tr>
    <td><strong>{{ $ticket->id }}</strong></td>
    <td>{{ $ticket->accountableUser }}</td>
    <td>{{ $ticket->problem }}</td>
    <td>
        @if ($ticket->repairedBy)
            {{ $ticket->inspector ? $ticket->inspector->first_name . ' ' . $ticket->inspector->last_name : '—' }}
        @else
            <select class="assign-inspector" data-ticket-id="{{ $ticket->id }}">
                <option value="">Select Inspector</option>
                @foreach($inspectors as $inspector)
                <option value="{{ $inspector->emp_number }}">
                    {{ $inspector->first_name }} {{ $inspector->last_name }}
                </option>
                @endforeach
            </select>
        @endif
    </td>
    <td>
        <input type="checkbox" class="urgent-checkbox" data-ticket-id="{{ $ticket->id }}" {{ $ticket->urgent ? 'checked' : '' }}>
    </td>
    <td>
        @if ($ticket->repairedBy)
            @if ($ticket->status !== 'working' && $ticket->status !== 'nonworking')
                <button class="btn btn-danger unassign-btn" data-ticket-id="{{ $ticket->id }}">Unassign</button>
            @else
                <span class="text-muted" style="font-size: 18px;">Assigned</span>
            @endif
        @else
            <button class="btn-assign assign-btn" data-ticket-id="{{ $ticket->id }}">Assign</button>
        @endif
    </td>
</tr>
@endforeach
