<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Управление сообщениями</title>
</head>
<body>
<div class="container my-5">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button class="btn btn-secondary mt-3" onclick="window.location.reload()">Назад</button>
        </div>
    @else
        <h2 class="mb-4">Добавить сообщение</h2>

        <form method="POST" action="{{ route('web.messages.create') }}">
            @csrf
            @foreach ($formFields as $field)
                @if ($field['type'] === 'hidden')
                    <input type="hidden" name="{{ $field['name'] }}" value="{{ $field['value'] ?? '' }}">
                @elseif ($field['type'] === 'select')
                    <div class="mb-3">
                        <label for="{{ $field['name'] }}" class="form-label">{{ $field['label'] }}</label>
                        <select name="{{ $field['name'] }}" id="{{ $field['name'] }}" class="form-select">
                            @foreach ($field['options'] as $id => $name)
                                <option value="{{ $id }}" {{ old($field['name']) == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error($field['name'])
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                @else
                    <div class="mb-3">
                        <label for="{{ $field['name'] }}" class="form-label">{{ $field['label'] }}</label>
                        <input type="text"
                               name="{{ $field['name'] }}"
                               id="{{ $field['name'] }}"
                               class="form-control @error($field['name']) is-invalid @enderror"
                               value="{{ old($field['name']) }}">
                        @error($field['name'])
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endif
            @endforeach
            <button type="submit" class="btn btn-success">Добавить сообщение</button>
        </form>

        <hr class="my-4">

        <table class="table table-striped table-bordered table-hover align-middle">
            <thead>
            <tr>
                @foreach ($columns as $column)
                    @php
                        $newOrder = $sortField === $column ? ($sortOrder === 'asc' ? 'desc' : 'asc') : 'asc';
                    @endphp
                    <th>
                        <a href="{{ route('web.messages.list', [
                                    'sort' => $column,
                                    'order' => $newOrder,
                                    'page' => $page
                                ]) }}">
                            {{ ucfirst($column) }}
                            @if ($sortField === $column)
                                <span>{{ $sortOrder === 'asc' ? '↓' : '↑' }}</span>
                            @endif
                        </a>
                    </th>
                @endforeach
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>

            @forelse ($tableData as $message)
                    <?php print_r($columns); ?>

                <tr>
                    @foreach ($columns as $column)

                        @if ($column === 'status_id')
                            <td>
                                <form method="POST" action="{{ route('web.messages.modify') }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="row_id" value="{{ $message->id }}">
                                    <select name="status_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status->id }}" {{ $status->id == $message->status_id ? 'selected' : '' }}>
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                        @elseif ($column === 'created_at' || $column === 'updated_at')
                            <td>{{ $message->$column ? $message->$column->format('d.m.Y H:i') : '' }}</td>
                        @else
                            <td>{{ $message->$column ?? '' }}</td>
                        @endif
                    @endforeach
                    <td>
                        <form method="POST" action="{{ route('web.messages.remove') }}" onsubmit="return confirm('Вы уверены, что хотите удалить сообщение?')">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="delete_id" value="{{ $message->id }}">
                            <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) + 2 }}" class="text-center">Нет сообщений</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $paginator->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
