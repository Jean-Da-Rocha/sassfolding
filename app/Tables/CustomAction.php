<?php

declare(strict_types=1);

namespace App\Tables;

use App\Tables\Data\ButtonStyleData;
use App\Tables\Enums\ButtonSeverity;
use App\Tables\Enums\ButtonSize;
use App\Tables\Enums\ButtonVariant;
use Hybridly\Tables\Actions\InlineAction;
use Spatie\LaravelData\Optional;

class CustomAction extends InlineAction
{
    protected ?ButtonStyleData $buttonStyle;

    /**
     * @return array{
     *     label: string,
     *     metadata: array{}|array<string, mixed>,
     *     name: string,
     *     style: ButtonStyleData,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'label' => $this->getLabel(),
            'metadata' => $this->getMetadata(),
            'name' => $this->getName(),
            'style' => $this->buttonStyle ?? new ButtonStyleData(),
        ];
    }

    /**
     * @param array{}|array{
     *     severity?: Optional|ButtonSeverity,
     *     size?: Optional|ButtonSize,
     *     variant?: Optional|ButtonVariant,
     *     icon?: string,
     *     raised?: bool,
     *     rounded?: bool,
     *     text?: string,
     * } $stylingOptions
     */
    public function style(array $stylingOptions = []): static
    {
        $this->buttonStyle = ButtonStyleData::from($stylingOptions);

        return $this;
    }
}
