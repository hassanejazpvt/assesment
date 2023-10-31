<?php

declare(strict_types=1);

namespace Hassan\Assesment\Models;

use Hassan\Assesment\Core\DB;
use Hassan\Assesment\Core\Model;

class FormField extends Model
{
    public const FIELD_TYPES = ['input', 'textarea', 'select', 'radio', 'checkbox'];

    public const OPTIONS_FIELD_TYPES = ['select', 'radio'];

    public const VALIDATIONS = ['email', 'number', 'required', 'minlength:x', 'maxlength:x', 'min:x', 'max:x', 'pattern:x'];
    protected $table         = 'form_fields';

    protected $columns = ['form_id', 'field_name', 'field_type', 'sent_via_email', 'options', 'validations', 'created_at', 'updated_at'];

    public function getFieldsByFormId(int $formId): ?array
    {
        return $this->where(['form_id' => $formId])->get();
    }

    public function getFieldsCountByFormId(int $formId): int
    {
        return $this->where(['form_id' => $formId])->count();
    }

    public function getFieldsByIds(array $ids): ?array
    {
        return $this->whereIn('id', $ids)->get();
    }

    public function getFieldsByIdsAndHaveValidations(array $ids): ?array
    {
        $query = DB::query();
        $query->select('*')
            ->from($this->table)
            ->where(
                $query->expr()->andX(
                    $query->expr()->in('id', $ids),
                    $query->expr()->isNotNull('validations')
                )
            );
        $data = $query->fetchAllAssociative();
        $query->resetQueryParts();

        return $data;
    }
}
