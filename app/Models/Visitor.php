<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visitor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'full_name',
        'department_id',
        'birth_date',
        'position',
        'phone',
        'entry_datetime',
        'exit_datetime',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'birth_date_formatted',
        'entry_datetime_formatted',
        'exit_datetime_formatted',
        'birth_date_for_input',
        'entry_datetime_for_input',
        'exit_datetime_for_input',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'entry_datetime' => 'datetime',
        'exit_datetime' => 'datetime',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function document(): HasOne
    {
        return $this->hasOne(Document::class);
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                $value = preg_replace('/[^А-яËёA-z\s\-]/u', '', $value);
                $value = preg_replace('/\s+/', ' ', $value);
                $value = mb_convert_case($value, MB_CASE_TITLE, "UTF-8");

                return trim($value);
            }
        );
    }

    protected function position(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                $value = preg_replace('/[^А-яËёA-z\s\-]/u', '', $value);
                $value = preg_replace('/\s+/', ' ', $value);

                return trim($value);
            }
        );
    }

    protected function phone(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->formatPhone($value),
            set: fn($value) => preg_replace('/\D/', '', $value)
        );
    }

    private function formatPhone(string $value): string
    {
        $digits = preg_replace('/\D/', '', $value);

        if (strlen($digits) === 11 && $digits[0] === '7') {
            return '+7(' . substr($digits, 1, 3) . ')' . substr($digits, 4, 3) . '-' . substr($digits, 7, 2) . '-' . substr($digits, 9, 2);
        }

        return $value;
    }

    protected function birthDateFormatted(): Attribute
    {
        return Attribute::get(
            fn() => $this->birth_date ? $this->birth_date->format('d.m.Y') : null
        );
    }

    protected function entryDatetimeFormatted(): Attribute
    {
        return Attribute::get(
            fn() => $this->entry_datetime ? $this->entry_datetime->format('d.m.Y H:i') : null
        );
    }

    protected function exitDatetimeFormatted(): Attribute
    {
        return Attribute::get(
            fn() => $this->exit_datetime ? $this->exit_datetime->format('d.m.Y H:i') : null
        );
    }

    protected function birthDateForInput(): Attribute
    {
        return Attribute::get(
            fn() => $this->birth_date ? $this->birth_date->format('Y-m-d') : null
        );
    }

    protected function entryDatetimeForInput(): Attribute
    {
        return Attribute::get(
            fn() => $this->entry_datetime ? $this->entry_datetime->format('Y-m-d\TH:i') : null
        );
    }

    protected function exitDatetimeForInput(): Attribute
    {
        return Attribute::get(
            fn() => $this->exit_datetime ? $this->exit_datetime->format('Y-m-d\TH:i') : null
        );
    }

    public function syncDocument(array $data): void
    {
        if ($this->document?->type !== $data['type']) {
            $this->document?->delete();
            $this->document()->create($data);
            return;
        }

        $this->document
            ? $this->document->update($data)
            : $this->document()->create($data);
    }
}
