<?php
/**
 * Created by PhpStorm.
 * User: note
 * Date: 25.01.2021
 * Time: 23:22
 */

namespace App\Modules\Admin\LeadComment\Services;


use App\Modules\Admin\Lead\Models\Lead;
use App\Modules\Admin\LeadComment\Models\LeadComment;
use App\Modules\Admin\Status\Models\Status;
use App\Modules\Admin\User\Models\User;

class LeadCommentService
{
    public static function saveComment(string $text, Lead $lead, User $user, Status $status, string  $commentValue = null, bool $is_event = false) {

        $comment = new LeadComment([
            'text' => $text,
            'comment_value' => $commentValue,
        ]);
        $comment->is_event = $is_event;

        $comment->
        lead()->
        associate($lead)->
        user()->
        associate($user)->
        status()->
        associate($status)->
        save();

        return $comment;

    }
}
