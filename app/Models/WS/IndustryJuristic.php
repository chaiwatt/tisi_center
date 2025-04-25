<?php

namespace App\Models\WS;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;

class IndustryJuristic extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ws_industry_juristics';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['AgentID',
                           'juristicType',
                           'juristicID',
                           'registerDate',
                           'oldJuristicID',
                           'juristicName',
                           'juristicNameEng',
                           'numberOfCommittee',
                           'committeeInformationType',
                           'addressInformationType',
                           'registerCapital',
                           'paidRegisterCapital',
                           'numberOfObjective',
                           'numberOfPageOfObjective',
                           'address',
                           'juristicStatus',
                           'authorizeDescriptionType',
                           'standardObjectiveType'];

    /*
      Sorting
    */
    public $sortable = ['AgentID',
                        'juristicType',
                        'juristicID',
                        'registerDate',
                        'oldJuristicID',
                        'juristicName',
                        'juristicNameEng',
                        'numberOfCommittee',
                        'committeeInformationType',
                        'addressInformationType',
                        'registerCapital',
                        'paidRegisterCapital',
                        'numberOfObjective',
                        'numberOfPageOfObjective',
                        'address',
                        'juristicStatus',
                        'authorizeDescriptionType',
                        'standardObjectiveType'];

}
