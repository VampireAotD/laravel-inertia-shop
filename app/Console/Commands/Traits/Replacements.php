<?php

namespace App\Console\Commands\Traits;

trait Replacements
{
    /**
     * Return array of replaced russian letters
     *
     * @return string[]
     */
    protected function getRussianReplacementArray(): array
    {
        return [
            "ф => a",
            "и => b",
            "с => c",
            "в => d",
            "у => e",
            "а => f",
            "п => g",
            "р => h",
            "ш => i",
            "о => j",
            "л => k",
            "д => l",
            "ь => m",
            "т => n",
            "щ => o",
            "з => p",
            "к => r",
            "ы => s",
            "е => t",
            "г => u",
            "м => v",
            "ц => w",
            "ч => x",
            "н => y",
            "я => z",
            "Ф => A",
            "И => B",
            "С => C",
            "В => D",
            "У => E",
            "А => F",
            "П => G",
            "Р => H",
            "Ш => I",
            "О => J",
            "Л => K",
            "Д => L",
            "Ь => M",
            "Т => N",
            "Щ => O",
            "З => P",
            "К => R",
            "Ы => S",
            "Е => T",
            "Г => U",
            "М => V",
            "Ц => W",
            "Ч => X",
            "Н => Y",
            "Я => Z",
            "х => [",
            "ъ => ]",
            "ж => ;",
            "б => <",
            "ю => >"
        ];
    }
}
