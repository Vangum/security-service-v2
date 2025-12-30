<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'visitor_id',

        'type',

        'passport_series',
        'passport_number',
        'passport_issue_date',
        'passport_issued_by',
        'passport_department_code',

        'license_series_number',
        'license_issue_date',
        'license_region',
        'license_issued_by',

        'other_document_name',
        'other_series_number',
        'other_series_number_original',
        'other_issue_date',
        'other_issued_by',

        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'type_label',
        'passport_series_for_input',
        'license_series_number_for_input',
    ];

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    protected function typeLabel(): Attribute
    {
        return Attribute::get(fn () => match ($this->type) {
            'passport' => 'Паспорт',
            'license' => 'Водительское удостоверение',
            'other' => $this->other_document_name ?: 'Другой документ',
            default => $this->type,
        });
    }

    protected function passportSeries(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->formatPassportSeries($value),
        );
    }

    protected function passportDepartmentCode(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->formatDepartmentCode($value),
            set: fn ($value) => $value ? preg_replace('/\D/', '', $value) : null
        );
    }

    protected function licenseSeriesNumber(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->formatLicenseSeriesNumber($value),
        );
    }

    protected function otherSeriesNumber(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value) => preg_replace('/\D/', '', $value)
        );
    }

    protected function otherSeriesNumberOriginal(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value) => $value
        );
    }

    protected function passportSeriesForInput(): Attribute
    {
        return Attribute::get(fn () => $this->passport_series ? preg_replace('/\s/', '', $this->passport_series) : null);
    }

    protected function licenseSeriesNumberForInput(): Attribute
    {
        return Attribute::get(fn () => $this->license_series_number ? preg_replace('/\s/', '', $this->license_series_number) : null);
    }

    private function formatPassportSeries(?string $series): ?string
    {
        if (!$series) return null;

        $digits = preg_replace('/\D/', '', $series);

        if (strlen($digits) === 4) {
            return sprintf(
                '%s %s',
                substr($digits, 0, 2),
                substr($digits, 2, 2)
            );
        }

        return $series;
    }

    private function formatDepartmentCode(?string $code): ?string
    {
        if (!$code) return null;

        $digits = preg_replace('/\D/', '', $code);

        if (strlen($digits) === 6) {
            return sprintf(
                '%s-%s',
                substr($digits, 0, 3),
                substr($digits, 3, 3)
            );
        }

        return $code;
    }

    private function formatLicenseSeriesNumber(?string $value): ?string
    {
        if (!$value) return null;

        $digits = preg_replace('/\D/', '', $value);

        if (strlen($digits) === 10) {
            return sprintf(
                '%s %s %s',
                substr($digits, 0, 2),
                substr($digits, 2, 2),
                substr($digits, 4, 6)
            );
        }

        return $value;
    }
}
