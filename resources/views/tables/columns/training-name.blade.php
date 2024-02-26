<div>
    <ol>
        @foreach ($getState() as $record)
        <li>{{ $record->name }}</li>
        @endforeach
    </ol>
</div>