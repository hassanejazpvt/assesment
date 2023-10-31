<?php

declare(strict_types=1);

namespace Hassan\Assesment\Controllers;

use Hassan\Assesment\Core\Error;
use Hassan\Assesment\Core\Mailer;
use Hassan\Assesment\Core\Request;
use Hassan\Assesment\Core\Response;
use Hassan\Assesment\Core\Validator;
use Hassan\Assesment\Models\Form;
use Hassan\Assesment\Models\FormField;
use Hassan\Assesment\Models\FormSubmission;

class FormsController
{
    private $formModel;
    private $formFieldModel;
    private $formSubmissionModel;

    public function __construct()
    {
        $this->formModel           = new Form();
        $this->formFieldModel      = new FormField();
        $this->formSubmissionModel = new FormSubmission();
    }

    public function index(): string
    {
        $forms = $this->formModel->all();
        $forms = array_map(function ($form) {
            $form['fields_count']  = $this->formFieldModel->getFieldsCountByFormId($form['id']);
            $form['entries_count'] = $this->formSubmissionModel->getSubmissionsCountByFormId($form['id']);

            return $form;
        }, $forms);

        return view('forms/index.php', compact('forms'));
    }

    /**
     * @throws Error
     */
    public function store(Request $request): string
    {
        $formValidator = new Validator(only($request->input(), ['name', 'title', 'fields']));
        $formValidator->required('name')
            ->required('title')
            ->required('fields')
            ->validate();

        $formId = $this->formModel->insert($request->input());
        foreach ($request->input('fields') as $field) {
            $field['form_id'] = $formId;
            $fieldValidator   = new Validator(only($field, ['form_id', 'field_name', 'field_type', 'options', 'validations']));
            $fieldValidator->required('form_id')
                ->required('field_name')
                ->required('field_type')
                ->inList('field_type', FormField::FIELD_TYPES);

            if (isset($field['validations'])) {
                $fieldValidator->matchList('validations', FormField::VALIDATIONS);
            }

            if (isset($field['field_type']) && in_array($field['field_type'], FormField::OPTIONS_FIELD_TYPES)) {
                $fieldValidator->required('options');
            }

            try {
                $fieldValidator->validate();
                if (isset($field['validations'])) {
                    $field['validations'] = json_encode(array_map(static function ($validation) {
                        return str_replace('\\', '\\\\', $validation);
                    }, $field['validations']));
                }

                if (isset($field['options'])) {
                    $field['options'] = json_encode($field['options']);
                }
                $this->formFieldModel->insert($field);
            } catch (Error $e) {
                $this->formModel->delete($formId);
                throw $e;
            }
        }

        return Response::json([
            'status'  => 1,
            'message' => 'Form created successfully.',
        ], 201);
    }

    /**
     * @throws Error
     */
    public function submit(Request $request, int $formId): string
    {
        if ($request->post('form_id') != $formId) {
            show404();
        }

        if (! isset($_SESSION['captcha']) || $request->post('captcha') != $_SESSION['captcha']) {
            return Response::json([
                'status'  => 0,
                'message' => 'Invalid captcha.',
            ], 422);
        }

        $submittedFields = $request->post('fields') ?: [];
        if (count($submittedFields) == 0) {
            return Response::json([
                'status'  => 0,
                'message' => 'No fields to save.',
            ]);
        }

        $form = $this->formModel->find($formId);

        $fields = $this->formFieldModel->getFieldsByIds(array_keys($submittedFields));

        $errors = [];

        // Running validations
        foreach ($fields as $field) {
            $validations = array_filter(json_decode($field['validations'] ?: '') ?: []);
            if ($validations) {
                if (count($validations)) {
                    $field_name = ucwords(str_replace(['_', '-'], ' ', $field['field_name']));
                    $validator  = new Validator([$field_name => $submittedFields[$field['id']]]);
                    foreach ($validations as $validation) {
                        if ('required' == $validation) {
                            $validator->required($field_name);
                        }
                        if ('email' == $validation) {
                            $validator->email($field_name);
                        }
                        if ('number' == $validation) {
                            $validator->number($field_name);
                        }

                        $validationExploded = explode(':', $validation, 2);
                        if (isset($validationExploded[0]) && isset($validationExploded[1])) {
                            if ('minlength' == $validationExploded[0]) {
                                $validator->minLength($field_name, (int) $validationExploded[1]);
                            }
                            if ('maxlength' == $validationExploded[0]) {
                                $validator->maxLength($field_name, (int) $validationExploded[1]);
                            }
                            if ('min' == $validationExploded[0]) {
                                $validator->min($field_name, (int) $validationExploded[1]);
                            }
                            if ('max' == $validationExploded[0]) {
                                $validator->max($field_name, (int) $validationExploded[1]);
                            }
                            if ('pattern' == $validationExploded[0]) {
                                $validator->pattern($field_name, str_replace('\\\\', '\\', $validationExploded[1]));
                            }
                        }
                    }

                    if (! $validator->validate(true)) {
                        $errors += $validator->errors();
                    }
                }
            }
        }
        if (count($errors)) {
            throw new Error('Fields could not be saved.', 422, $errors);
        }

        $emailFields = array_filter($fields, static function ($field) {
            return 1 == $field['sent_via_email'];
        });

        foreach ($submittedFields as $fieldId => $value) {
            $this->formSubmissionModel->insert(['form_id' => $formId, 'field_id' => $fieldId, 'value' => $value]);
        }

        if (count($emailFields) && env('SEND_ENTRY_EMAIL_TO')) {
            (new Mailer())->to(env('SEND_ENTRY_EMAIL_TO'))
                ->subject("Form Submitted - {$form['name']}")
                ->body(view('emails/form.php', compact('emailFields', 'submittedFields')))
                ->send();
        }

        return Response::json([
            'status'  => 1,
            'message' => 'Data saved successfully.',
        ]);
    }

    public function view(Request $request, int $id): string
    {
        $form = $this->formModel->find($id);
        if (! $form) {
            show404();
        }
        $fields = $this->formFieldModel->getFieldsByFormId($form['id']);

        return view('forms/form.php', compact('form', 'fields'));
    }

    public function delete(Request $request, int $id): void
    {
        $form = $this->formModel->find($id);
        if (!$form) {
            show404();
        }

        $this->formModel->delete($id);

        redirect(url('forms'));
    }
}
