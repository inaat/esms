<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Utils\Util;
use DB;

class Account extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public static function forDropdown($system_settings_id, $campus_id, $prepend_none, $closed = false, $default_campus_account=false, $show_balance = false)
    {
        $query = Account::where('system_settings_id', $system_settings_id);

        $can_access_account = auth()->user()->can('account.access');
        if ($can_access_account && $show_balance) {
            $query->leftjoin('account_transactions as AT', function ($join) {
                $join->on('AT.account_id', '=', 'accounts.id');
                $join->whereNull('AT.deleted_at');
            })
            ->select(
                'accounts.name',
                'accounts.id',
                DB::raw("SUM( IF(AT.type='credit', amount, -1*amount) ) as balance")
            );
        }
        $permitted_campuses = auth()->user()->permitted_campuses();
        if ($permitted_campuses != 'all') {
          $query->whereIn('accounts.campus_id', $permitted_campuses);
        }
        if (!empty($campus_id)) {
            $query->where('accounts.campus_id', $campus_id);
        }
        if (!$closed) {
            $query->where('accounts.is_closed', 0);
        }
        // if ($default_campus_account) {
        //     $query->where('accounts.default_campus_account', 1);
        // }

        $accounts = $query->groupBy('accounts.id')->get();
        $dropdown = [];
        if ($prepend_none) {
            $dropdown[''] = __('english.none');
        }

        $commonUtil = new Util();
        foreach ($accounts as $account) {
            $name = $account->name;

            if ($can_access_account && $show_balance) {
                $name .= ' (' . __('english.balance') . ': ' . $commonUtil->num_f($account->balance) . ')';
            }

            $dropdown[$account->id] = $name;
        }
        if (!empty($campus_id)) {
            $other_accounts = $commonUtil->accountOther($system_settings_id, $campus_id, $prepend_none, $closed = false, $default_campus_account=false, $show_balance = false);
            foreach ($other_accounts as $account) {
                $name = $account->name;

               
                    $name .= ' (' . __('english.balance') . ': ' . $commonUtil->num_f($account->balance) . ')';
                

                $dropdown[$account->id] = $name;
            }
        }
        return $dropdown;
    }

    /**
     * Scope a query to only include not closed accounts.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotClosed($query)
    {
        return $query->where('is_closed', 0);
    }

    /**
     * Scope a query to only include non capital accounts.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    // public function scopeNotCapital($query)
    // {
    //     return $query->where(function ($q) {
    //         $q->where('account_type', '!=', 'capital');
    //         $q->orWhereNull('account_type');
    //     });
    // }

    public static function accountTypes()
    {
        return [
            '' => __('english.not_applicable'),
            'saving_current' => __('english.saving_current'),
            'capital' => __('english.capital')
        ];
    }



    public function account_type()
    {
        return $this->belongsTo(\App\Models\AccountType::class, 'account_type_id');
    }
}
