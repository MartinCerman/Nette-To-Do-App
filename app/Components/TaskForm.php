<?php

declare(strict_types=1);

namespace App\Components;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Forms;

class TaskForm extends Form
{
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

        $this->addSubmit('submit', 'Uložit');
        $this->addUpload('upload')
            ->addRule(
                Forms\Form::MAX_FILE_SIZE,
                'Soubor je moc velký, maximální velikost souboru je 5 MB.',
                5 * 1024 * 1024
            );
    }
}