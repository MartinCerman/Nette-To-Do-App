<?php

declare(strict_types=1);

namespace App\Components\TaskForm;

use Nette\Application\UI\Form;
use Nette\Forms;

class TaskForm extends Form
{
    private const ACCEPTED_TYPES = [
        'image/bmp',
        'image/gif',
        'image/jpeg',
        'image/png',
        'application/pdf',
        'application/msword',
        'application/vnd.ms-excel',
        'text/plain',
        'text/csv',
    ];

    public function __construct()
    {
        parent::__construct();

        $this->addText('name', 'Název úlohy')
            ->addRule(
                Forms\Form::MAX_LENGTH,
                'Název nesmí být delší než 20 znaků.',
                20
            )
            ->setRequired('Musíte zadat alespoň název úlohy.')
            ->setHtmlAttribute('placeholder', 'Název');

        $this->addTextArea('description', 'Popis')
            ->addRule(
                Forms\Form::MAX_LENGTH,
                'Popis nesmí být delší než 1000 znaků.',
                1000
            )
            ->setHtmlAttribute('placeholder', 'Popis');

        $this->addCheckbox('isCompleted', 'Splněno');

        $this->addUpload('upload', 'Příloha')
            ->addRule(
                Forms\Form::MIME_TYPE,
                'Soubor není v požadovaném formátu.',
                self::ACCEPTED_TYPES
            )
            ->addRule(
                Forms\Form::MAX_FILE_SIZE,
                'Soubor je moc velký, maximální velikost souboru je 5 MB.',
                5 * 1024 * 1024
            );

        $this->addSubmit('submit', 'Uložit');
    }
}