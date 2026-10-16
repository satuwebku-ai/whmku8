<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Support\UniqueSlug;

/**
 * Shared storage for CMS pages, blog posts, and blog categories.
 *
 * Each public model defines CONTENT_TYPE (tipe di kolom `type`) dan
 * SLUG_FALLBACK (slug cadangan kalau judul kosong), sehingga pemanggil tetap
 * memakai API model yang sama sementara baca/tulis terpisah per tipe.
 */
trait UsesCmsContentTable
{
    /**
     * Semua model CMS memakai satu tabel, jadi nama tabel ditetapkan di sini
     * -- model tidak perlu mendeklarasikan $table sendiri.
     */
    public function initializeUsesCmsContentTable(): void
    {
        $this->setTable('cms_contents');
    }

    /**
     * Slug unik di dalam tipenya sendiri (global scope sudah membatasi query
     * ke tipe model ini). Satu-satunya tempat logika slug CMS -- controller
     * dan hook model sama-sama memanggil ini.
     */
    public static function uniqueSlugFor(string $value, ?int $ignoreId = null): string
    {
        return UniqueSlug::make($value, static::SLUG_FALLBACK, fn (string $slug): bool => static::slugIsReserved($slug)
            || static::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn (Builder $query) => $query->where('id', '!=', $ignoreId))
                ->exists());
    }

    /** Tipe yang punya slug terlarang (halaman) menimpa method ini. */
    protected static function slugIsReserved(string $slug): bool
    {
        return false;
    }

    /**
     * Isi konten yang aman ditampilkan apa adanya ({!! !!}) -- HTML dibersihkan
     * lewat allowlist supaya tidak bisa menyisipkan script (stored XSS).
     */
    public function getSafeContentAttribute(): string
    {
        return \App\Support\HtmlSanitizer::clean($this->content);
    }

    protected static function bootUsesCmsContentTable(): void
    {
        $contentType = static::CONTENT_TYPE;

        static::addGlobalScope(
            'cms_content_type_'.$contentType,
            static function (Builder $query) use ($contentType): void {
                $query->where($query->getModel()->qualifyColumn('type'), $contentType);
            }
        );

        static::creating(static function (Model $model) use ($contentType): void {
            $model->setAttribute('type', $contentType);
        });
    }
}
