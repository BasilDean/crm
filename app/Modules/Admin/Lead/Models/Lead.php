<?php

namespace App\Modules\Admin\Lead\Models;

use App\Modules\Admin\LeadComment\Models\LeadComment;
use App\Modules\Admin\Sources\Models\Source;
use App\Modules\Admin\Status\Models\Status;
use App\Modules\Admin\Unit\Models\Unit;
use App\Modules\Admin\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use function Symfony\Component\Translation\t;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'link',
        'phone',
        'source_id',
        'unit_id',
        'is_processed',
        'is_express_delivery',
        'is_add_sale',
    ];

    public function source() {
        return $this->belongsTo(Source::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function unit() {
        return $this->belongsTo(Unit::class);
    }

    public function status() {
        return $this->belongsTo(Status::class);
    }

    public function comments() {
        return $this->hasMany(LeadComment::class);
    }

    public function lastComments() {
        return $this->comments()->where('comment_value', '!=', NULL)->orderBy('id', 'desc')->first();
    }

    public function getLeads()
    {
        return $this->
            with(['source', 'unit', 'status'])->
            whereBetween('status_id', [1,2])->
            orWhere([
                ['status_id', 3],
                ['updated_at', '>', DB::raw('DATE_SUB(NOW(), INTERVAL 24 HOUR)')]
            ])->
            orderBy('created_at')->
            get();
    }
}
