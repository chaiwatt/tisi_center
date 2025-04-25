<?php

namespace App\Models\WS;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;

class IndustryPersonal extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ws_industry_personals';

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
                           'citizenID',
                           'age',
                           'dateOfBirth',
                           'dateOfMoveIn',
                           'fatherName',
                           'fatherNationalityCode',
                           'fatherNationalityDesc',
                           'fatherPersonalID',
                           'firstName',
                           'fullnameAndRank',
                           'genderCode',
                           'genderDesc',
                           'lastName',
                           'middleName',
                           'motherName',
                           'motherNationalityCode',
                           'motherNationalityDesc',
                           'motherPersonalID',
                           'nationalityCode',
                           'nationalityDesc',
                           'ownerStatusDesc',
                           'statusOfPersonCode',
                           'statusOfPersonDesc',
                           'titleCode',
                           'titleDesc',
                           'titleName',
                           'titleSex'];

    /*
      Sorting
    */
    public $sortable = ['AgentID',
                           'citizenID',
                           'age',
                           'dateOfBirth',
                           'dateOfMoveIn',
                           'fatherName',
                           'fatherNationalityCode',
                           'fatherNationalityDesc',
                           'fatherPersonalID',
                           'firstName',
                           'fullnameAndRank',
                           'genderCode',
                           'genderDesc',
                           'lastName',
                           'middleName',
                           'motherName',
                           'motherNationalityCode',
                           'motherNationalityDesc',
                           'motherPersonalID',
                           'nationalityCode',
                           'nationalityDesc',
                           'ownerStatusDesc',
                           'statusOfPersonCode',
                           'statusOfPersonDesc',
                           'titleCode',
                           'titleDesc',
                           'titleName',
                           'titleSex'];

}
