<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'age',
        'status_id',
        // Другие поля, которые можно массово заполнять, кроме created_at и updated_at
    ];

    public $timestamps = true;

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function getTableColumns(): array
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    public static function getPaginatedViewData(string $sortField, string $sortOrder, int $page, string $table): View|RedirectResponse
    {
        $modelClass = "App\\Models\\" . mb_substr(ucfirst($table), 0, -1);
        if (!class_exists($modelClass)) {
            abort(404, 'Модель не найдена');
        }

        $model = new $modelClass();

        $paginator = $model->with('status')
            ->orderBy($sortField, $sortOrder)
            ->paginate(1, ['*'], 'page', $page);

        $tableData = $paginator->items();

        if (empty($tableData) && $page > 1) {
            return redirect()->route('web.messages.list', [
                'sort' => $sortField,
                'order' => $sortOrder,
                'page' => $paginator->lastPage()
            ]);
        }

        $formFields = collect($model->getTableColumns())
            ->reject(fn($column) => in_array($column, ['id', 'status_id', 'created_at', 'updated_at']))
            ->map(fn($column) => [
                'name' => $column,
                'label' => ucfirst($column),
                'type' => 'text'
            ])
            ->toArray();

        if (in_array('status_id', $model->getTableColumns())) {
            $formFields[] = [
                'name' => 'status_id',
                'type' => 'select',
                'label' => 'Статус',
                'options' => Status::pluck('name', 'id')->toArray()
            ];
        }

        $formFields[] = [
            'name' => 'action',
            'type' => 'hidden',
            'value' => 'add'
        ];

        return view('messages', [
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
            'table' => $table,
            'tableData' => $tableData,
            'statuses' => Status::all(),
            'columns' => $model->getTableColumns(),
            'formFields' => $formFields,
            'page' => $page,
            'countPages' => $paginator->lastPage(),
            'paginator' => $paginator,
        ]);
    }

    public static function createMessage(array $data): void
    {
        DB::beginTransaction();
        static::create(Arr::except($data, ['action', 'created_at', 'updated_at']));
        DB::commit();
    }

    public static function updateMessage(array $data): void
    {
        $model = self::findOrFail($data['row_id']);
        DB::beginTransaction();
        $model->update(['status_id' => $data['status_id']]);
        DB::commit();
    }

    public static function deleteMessage(array $data): void
    {
        $model = self::findOrFail($data['delete_id']);
        DB::beginTransaction();
        $model->delete();
        DB::commit();
    }

    public function tableExists()
    {
        return Schema::hasTable('messages');
    }
}
