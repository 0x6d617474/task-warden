<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Task extends Model
{
    use HasUuids;
    use SoftDeletes;

    /**
     * The name of the "created at" column.
     */
    public const ?string CREATED_AT = 'created';

    /**
     * The name of the "updated at" column.
     */
    public const ?string UPDATED_AT = 'last_modified';

    /**
     * The name of the "deleted at" column.
     */
    public const ?string DELETED_AT = 'deleted';

    public $incrementing = false;

    protected $primaryKey = 'id';

    protected $guarded = [];

    protected $hidden = [
        self::DELETED_AT,
        'pivot',
    ];

    protected $table = 'tasks';
}
