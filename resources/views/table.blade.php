<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"  rel="stylesheet">
    <title>Document</title>
</head>
<body>

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
        <button onClick="window.location.reload();">Назад</button>
    @endforeach
@else
<h2 class="mb-4">Добавить запись</h2>

<form method="POST" action="{{ route('tablePost', ['table' => $table]) }}">
    @csrf

    @foreach($formFields as $field)
        @if($field['type'] === 'hidden')
            <input type="hidden" name="{{ $field['name'] }}" value="{{ $field['value'] ?? '' }}">

        @elseif($field['type'] === 'select')
            <div class="mb-3">
                <label for="{{ $field['name'] }}" class="form-label">{{ $field['label'] }}</label>
                <select name="{{ $field['name'] }}" id="{{ $field['name'] }}" class="form-control">
                    @foreach($field['options'] as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

        @else
            <div class="mb-3">
                <label for="{{ $field['name'] }}" class="form-label">{{ $field['label'] }}</label>
                <input type="text"
                       name="{{ $field['name'] }}"
                       id="{{ $field['name'] }}"
                       class="form-control"
                       value="{{ old($field['name']) }}">
            </div>
        @endif
    @endforeach

    <button type="submit" class="btn btn-success">Добавить</button>
</form>

<hr class="my-4">

<table class="table table-striped table-bordered table-hover align-middle">
    <thead>
    <tr>
        @foreach($columns as $column)
            @php
                $newOrder = $sortField === $column ? ($sortOrder === 'asc' ? 'desc' : 'asc') : 'asc';
            @endphp
            <th>
                <a href="{{ route('table', [
                        'table' => $table,
                        'sort' => $column,
                        'order' => $newOrder,
                        'page' => $page
                    ]) }}">
                    {{ ucfirst($column) }}
                    @if($sortField === $column)
                        @if($sortOrder === 'asc')
                            &darr;
                        @else
                            &uarr;
                       @endif
                    @endif
                </a>
            </th>
        @endforeach
        <th>Статус</th>
        <th>Действия</th>
    </tr>
    </thead>
    <tbody>
    @foreach($tableData as $row)
        <tr>
            @foreach($row->toArray() as $key => $value)
                @if($key === 'status_id' && !empty($statuses))
                    <td>
                        <form method="POST" style="display:inline-block;">
                            @csrf
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="row_id" value="{{ $row['id'] }}">
                            <select name="status_id" onchange="this.form.submit()" class="form-select form-select-sm">
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}" {{ $status->id == $value ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                @elseif($key !== 'status_id')
                    <td>{{ $value ?? '' }}</td>
                @endif
            @endforeach

            <td>
                <form method="POST" onsubmit="return confirm('Вы уверены?');" style="display:inline-block;">
                    @csrf
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="delete_id" value="{{ $row['id'] }}">
                    <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>




<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        <li class="page-item {{ $page <= 1 ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $paginator->previousPageUrl() }}">Назад</a>
        </li>

        @for($i = 1; $i <= $paginator->lastPage(); $i++)
            <li class="page-item {{ $page == $i ? 'active' : '' }}">
                <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        <li class="page-item {{ $page >= $paginator->lastPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $paginator->nextPageUrl() }}">Вперед</a>
        </li>
    </ul>
</nav>
@endif
</body>
</html>

