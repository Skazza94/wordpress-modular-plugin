<?php

namespace WPModular\L10n;

use WPModular\Contracts\L10n\TranslationContract;

class TranslationManager implements TranslationContract
{
    private $textDomain = null;

    public function __construct($textDomain)
    {
        $this->textDomain = $textDomain;
    }

    public function translate($tag)
    {
        return __($tag, $this->textDomain);
    }
}