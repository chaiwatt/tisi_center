<?php

namespace App\Models\Funtion;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;

class SendMail extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'log_send_mails';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    protected $fillable = ['invite', 'quality', 'sender_name', 'send_type', 'password_type', 'new_password', 'name_send', 'emails', 'information', 'file_attach', 'file_attach_name', 'send_date', 'data_multi'];
}
