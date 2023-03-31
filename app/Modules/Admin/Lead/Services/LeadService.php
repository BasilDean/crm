<?php
/**
 * Created by PhpStorm.
 * User: note
 * Date: 27.12.2020
 * Time: 11:48
 */

namespace App\Modules\Admin\Lead\Services;


use App\Modules\Admin\Lead\Models\Lead;
use App\Modules\Admin\LeadComment\Services\LeadCommentService;
use App\Modules\Admin\Status\Models\Status;
use App\Modules\Admin\User\Models\User;

class LeadService
{

    public function getLeads()
    {
        $leads = (new Lead())->getLeads();
        $statuses = Status::all();

        $resultLeads = [];

        $statuses->each(function($item, $key) use(&$resultLeads,$leads) {
            $collection = $leads->where('status_id', $item->id);
            $resultLeads[$item->title] = $collection->map(function($elem) {
                return $elem;
            });

        });

        return $resultLeads;
    }

    public function store($request, User $user)
    {
        $lead = new Lead();
        $lead->fill($request->only($lead->getFillable()));

        $status = Status::where('title','new')->first();

        $lead->status()->associate($status);

        $user->leads()->save($lead);

        ///add comments
        $this->addStoreComments($lead, $request, $user, $status);

        $lead->statuses()->attach($status->id);

        return $lead;
    }

    private function addStoreComments($lead, $request, $user, $status)
    {
        $is_event = true;
        $tmpText = "Автор <strong>".$user->fullname.'</strong> создал лид со статусом '.$status->title_ru;
        LeadCommentService::saveComment($tmpText, $lead, $user, $status, null, $is_event);

        if($request->text) {
            $is_event = false;
            $tmpText = "Пользователь <strong>".$user->fullname.'</strong> оставил комментарий '.$request->text;
            LeadCommentService::saveComment($tmpText, $lead, $user, $status, $request->text, $is_event);
        }

    }

    public function update($request, $user, $lead)
    {
        $lead->count_create++;

        $tmp = clone $lead;

        $status = Status::where('title', 'new')->first();

        $lead->fill($request->only($lead->getFillable));
        $lead->status()->associte($status);

        $lead->save();

        $this->addUpdateComments($lead, $request, $user, $status, $tmp);

        return $lead;
    }

    private function addUpdateComments($lead, $request, $user, $status, $tmp)
    {

        if($request->text) {
            $tmpText = "Пользователь <strong>".$user->fullname.'</strong> оставил комментарий '.$request->text;
            LeadCommentService::saveComment($tmpText, $lead, $user, $status, $request->text);
        }

        if($tmp->source_id != $lead->source_id) {
            $is_event = true;
            $tmpText = "Пользователь <strong>".$user->fullname.'</strong> изменил источник на '.$lead->source;
            LeadCommentService::saveComment($tmpText, $lead, $user, $status, null, $is_event);
        }

        if($tmp->unit_id != $lead->unit_id) {
            $tmpText = "Пользователь <strong>".$user->fullname.'</strong> изменил позразделение на '.$lead->unit_id;
            LeadCommentService::saveComment($tmpText, $lead, $user, $status, null, true);
        }

        if($tmp->status_id != $lead->status_id) {
            $tmpText = "Пользователь <strong>".$user->fullname.'</strong> изменил статус на '.$lead->status_id;
            LeadCommentService::saveComment($tmpText, $lead, $user, $status, null, true);
        }

        $tmpText = "Автор <strong>".$user->fullname.'</strong> создал лид со статусом '.$status->title_ru;
        LeadCommentService::saveComment($tmpText, $lead, $user, $status, null, true);

        $lead->statuses()->attach($status->id);
    }
}
