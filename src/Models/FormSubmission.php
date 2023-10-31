<?php

declare(strict_types=1);

namespace Hassan\Assesment\Models;

use Hassan\Assesment\Core\Model;

class FormSubmission extends Model
{
    protected $table = 'form_submissions';

    protected $columns = ['form_id', 'field_id', 'value', 'created_at', 'updated_at'];

    public function getSubmissionsCountByFormId(int $formId): int
    {
        return $this->where(['form_id' => $formId])->count();
    }
}
