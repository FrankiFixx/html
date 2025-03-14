<?php

declare(strict_types=1);

namespace Yiisoft\Html\Tests\Widget;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Yiisoft\Html\Html;
use Yiisoft\Html\Tests\Support\IntegerEnum;
use Yiisoft\Html\Tests\Support\StringEnum;
use Yiisoft\Html\Widget\RadioList\RadioItem;
use Yiisoft\Html\Widget\RadioList\RadioList;

final class RadioListTest extends TestCase
{
    public function testBase(): void
    {
        $this->assertSame(
            '<input type="hidden" name="test" value="0">' . "\n" .
            '<div id="main">' . "\n" .
            '<label><input type="radio" name="test" value="1"> One</label>' . "\n" .
            '<label><input type="radio" name="test" value="2" checked> Two</label>' . "\n" .
            '<label><input type="radio" name="test" value="5"> Five</label>' . "\n" .
            '</div>',
            RadioList::create('test')
                ->items([1 => 'One', 2 => 'Two', 5 => 'Five'])
                ->uncheckValue(0)
                ->value(2)
                ->containerAttributes(['id' => 'main'])
                ->render(),
        );
    }

    public function testName(): void
    {
        $widget = RadioList::create('a')
            ->items([1 => 'One'])
            ->name('b');

        $this->assertSame(
            '<div>' . "\n" .
            '<label><input type="radio" name="b" value="1"> One</label>' . "\n" .
            '</div>',
            $widget->render()
        );
    }

    public function testWithoutContainer(): void
    {
        $this->assertSame(
            '<label><input type="radio" name="test" value="1"> One</label>',
            RadioList::create('test')
                ->items([1 => 'One'])
                ->withoutContainer()
                ->render(),
        );
    }

    public static function dataContainerTag(): array
    {
        return [
            [
                '<label><input type="radio" name="test" value="1"> One</label>',
                null,
            ],
            [
                '<label><input type="radio" name="test" value="1"> One</label>',
                '',
            ],
            [
                '<section>' . "\n" .
                '<label><input type="radio" name="test" value="1"> One</label>' . "\n" .
                '</section>',
                'section',
            ],
        ];
    }

    #[DataProvider('dataContainerTag')]
    public function testContainerTag(string $expected, ?string $name): void
    {
        $this->assertSame(
            $expected,
            RadioList::create('test')
                ->items([1 => 'One'])
                ->containerTag($name)
                ->render(),
        );
    }

    public function testContainerAttributes(): void
    {
        $this->assertSame(
            '<div id="main">' . "\n" .
            '<label><input type="radio" name="test" value="1"> One</label>' . "\n" .
            '</div>',
            RadioList::create('test')
                ->items([1 => 'One'])
                ->containerAttributes(['id' => 'main'])
                ->render(),
        );
    }

    public function testRadioAttributes(): void
    {
        $this->assertSame(
            '<label><input type="radio" class="red" name="test" value="1"> One</label>' . "\n" .
            '<label><input type="radio" class="red" name="test" value="2"> Two</label>',
            RadioList::create('test')
                ->items([
                    1 => 'One',
                    2 => 'Two',
                ])
                ->radioAttributes(['class' => 'red'])
                ->withoutContainer()
                ->render(),
        );
    }

    public function testRadioAttributesMerge(): void
    {
        $this->assertSame(
            '<label><input type="radio" class="red" name="test" value="1" readonly> One</label>' . "\n" .
            '<label><input type="radio" class="red" name="test" value="2" readonly> Two</label>',
            RadioList::create('test')
                ->items([
                    1 => 'One',
                    2 => 'Two',
                ])
                ->readonly()
                ->addRadioAttributes(['class' => 'red'])
                ->withoutContainer()
                ->render(),
        );
    }

    public function testReplaceRadioAttributes(): void
    {
        $this->assertSame(
            '<label><input type="radio" class="red" name="test" value="1"> One</label>' . "\n" .
            '<label><input type="radio" class="red" name="test" value="2"> Two</label>',
            RadioList::create('test')
                ->items([
                    1 => 'One',
                    2 => 'Two',
                ])
                ->readonly()
                ->radioAttributes(['class' => 'red'])
                ->withoutContainer()
                ->render(),
        );
    }

    public function testRadioLabelAttributes(): void
    {
        $this->assertSame(
            '<label class="red"><input type="radio" name="test" value="1"> One</label>' . "\n" .
            '<label class="red"><input type="radio" name="test" value="2"> Two</label>',
            RadioList::create('test')
                ->items([1 => 'One', 2 => 'Two'])
                ->radioLabelAttributes(['class' => 'red'])
                ->withoutContainer()
                ->render(),
        );
    }

    public function testRadioLabelAttributesMerge(): void
    {
        $this->assertSame(
            '<label class="red" data-type="label"><input type="radio" name="test" value="1"> One</label>' . "\n" .
            '<label class="red" data-type="label"><input type="radio" name="test" value="2"> Two</label>',
            RadioList::create('test')
                ->items([1 => 'One', 2 => 'Two'])
                ->radioLabelAttributes(['class' => 'red'])
                ->addRadioLabelAttributes(['data-type' => 'label'])
                ->withoutContainer()
                ->render(),
        );
    }

    public function testIndividualInputAttributes(): void
    {
        $this->assertSame(
            '<label><input type="radio" class="red" name="test" value="1"> One</label>' . "\n" .
            '<label><input type="radio" class="blue" name="test" value="2"> Two</label>' . "\n" .
            '<label><input type="radio" class="green" name="test" value="3"> Three</label>',
            RadioList::create('test')
                ->items([
                    1 => 'One',
                    2 => 'Two',
                    3 => 'Three',
                ])
                ->radioAttributes(['class' => 'red'])
                ->individualInputAttributes([
                    2 => ['class' => 'blue'],
                    3 => ['class' => 'green'],
                ])
                ->withoutContainer()
                ->render(),
        );
    }

    public function testIndividualUncheckInputAttributes(): void
    {
        $this->assertSame(
            '<input type="hidden" class="blue" name="test" value="0">' . "\n" .
            '<label><input type="radio" class="red" name="test" value="1"> One</label>' . "\n" .
            '<label><input type="radio" class="red" name="test" value="2"> Two</label>',
            RadioList::create('test')
                ->items([
                    1 => 'One',
                    2 => 'Two',
                ])
                ->uncheckValue(0)
                ->radioAttributes(['class' => 'red'])
                ->individualInputAttributes([
                    0 => ['class' => 'blue'],
                ])
                ->withoutContainer()
                ->render(),
        );
    }

    public function testIndividualInputAttributesMerge(): void
    {
        $this->assertSame(
            '<label><input type="radio" class="yellow" name="test" value="1"> One</label>' . "\n" .
            '<label><input type="radio" class="cyan" name="test" value="2"> Two</label>' . "\n" .
            '<label><input type="radio" class="green" name="test" value="3"> Three</label>',
            RadioList::create('test')
                ->items([
                    1 => 'One',
                    2 => 'Two',
                    3 => 'Three',
                ])
                ->radioAttributes(['class' => 'red'])
                ->addIndividualInputAttributes([
                    2 => ['class' => 'blue'],
                    3 => ['class' => 'green'],
                ])
                ->addIndividualInputAttributes([
                    1 => ['class' => 'yellow'],
                    2 => ['class' => 'cyan'],
                ])
                ->withoutContainer()
                ->render(),
        );
    }

    public function testIndividualInputAttributesReplace(): void
    {
        $this->assertSame(
            '<label><input type="radio" class="yellow" name="test" value="1"> One</label>' . "\n" .
            '<label><input type="radio" class="red" name="test" value="2"> Two</label>' . "\n" .
            '<label><input type="radio" class="red" name="test" value="3"> Three</label>',
            RadioList::create('test')
                ->items([
                    1 => 'One',
                    2 => 'Two',
                    3 => 'Three',
                ])
                ->radioAttributes(['class' => 'red'])
                ->addIndividualInputAttributes([
                    2 => ['class' => 'blue'],
                    3 => ['class' => 'green'],
                ])
                ->individualInputAttributes([
                    1 => ['class' => 'yellow'],
                ])
                ->withoutContainer()
                ->render(),
        );
    }

    public function testItems(): void
    {
        $this->assertSame(
            '<label><input type="radio" name="test" value="1"> One</label>' . "\n" .
            '<label><input type="radio" name="test" value="2"> &lt;b&gt;Two&lt;/b&gt;</label>',
            RadioList::create('test')
                ->items([
                    1 => 'One',
                    2 => '<b>Two</b>',
                ])
                ->withoutContainer()
                ->render(),
        );
    }

    public function testItemsWithoutEncodeLabel(): void
    {
        $this->assertSame(
            '<label><input type="radio" name="test" value="1"> One</label>' . "\n" .
            '<label><input type="radio" name="test" value="2"> <b>Two</b></label>',
            RadioList::create('test')
                ->items([
                    1 => 'One',
                    2 => '<b>Two</b>',
                ], false)
                ->withoutContainer()
                ->render(),
        );
    }

    public static function dataItemsFromValues(): array
    {
        return [
            [
                '<label><input type="radio" name="test" value="1"> 1</label>' . "\n" .
                '<label><input type="radio" name="test" value="2"> 2</label>',
                [1, 2],
            ],
            [
                '<label><input type="radio" name="test" value="One"> One</label>' . "\n" .
                '<label><input type="radio" name="test" value="&lt;b&gt;Two&lt;/b&gt;"> &lt;b&gt;Two&lt;/b&gt;</label>',
                ['One', '<b>Two</b>'],
            ],
            [
                '<label><input type="radio" name="test" value="1"> 1</label>' . "\n" .
                '<label><input type="radio" name="test" value></label>',
                [true, false],
            ],
        ];
    }

    #[DataProvider('dataItemsFromValues')]
    public function testItemsFromValues(string $expected, array $values): void
    {
        $this->assertSame(
            $expected,
            RadioList::create('test')
                ->itemsFromValues($values)
                ->withoutContainer()
                ->render(),
        );
    }

    public function testItemsFromValuesWithoutEncodeLabel(): void
    {
        $this->assertSame(
            '<label><input type="radio" name="test" value="One"> One</label>' . "\n" .
            '<label><input type="radio" name="test" value="&lt;b&gt;Two&lt;/b&gt;"> <b>Two</b></label>',
            RadioList::create('test')
                ->itemsFromValues([
                    'One',
                    '<b>Two</b>',
                ], false)
                ->withoutContainer()
                ->render(),
        );
    }

    public static function dataValue(): array
    {
        return [
            ["<div>\n</div>", [], null],
            ["<div>\n</div>", [], 42],
            [
                '<div>' . "\n" .
                '<label><input type="radio" name="test" value="1"> One</label>' . "\n" .
                '<label><input type="radio" name="test" value="2"> Two</label>' . "\n" .
                '</div>',
                [1 => 'One', 2 => 'Two'],
                null,
            ],
            [
                '<div>' . "\n" .
                '<label><input type="radio" name="test" value="1"> One</label>' . "\n" .
                '<label><input type="radio" name="test" value="2"> Two</label>' . "\n" .
                '</div>',
                [1 => 'One', 2 => 'Two'],
                7,
            ],
            [
                '<div>' . "\n" .
                '<label><input type="radio" name="test" value="1" checked> One</label>' . "\n" .
                '<label><input type="radio" name="test" value="2"> Two</label>' . "\n" .
                '</div>',
                [1 => 'One', 2 => 'Two'],
                1,
            ],
            [
                '<div>' . "\n" .
                '<label><input type="radio" name="test" value="1"> One</label>' . "\n" .
                '<label><input type="radio" name="test" value="2" checked> Two</label>' . "\n" .
                '</div>',
                [1 => 'One', 2 => 'Two'],
                2,
            ],
            ["<div>\n</div>", [], StringEnum::A],
            [
                '<div>' . "\n" .
                '<label><input type="radio" name="test" value="one"> One</label>' . "\n" .
                '<label><input type="radio" name="test" value="two"> Two</label>' . "\n" .
                '</div>',
                [StringEnum::A->value => 'One', StringEnum::B->value => 'Two'],
                StringEnum::C,
            ],
            [
                '<div>' . "\n" .
                '<label><input type="radio" name="test" value="one" checked> One</label>' . "\n" .
                '<label><input type="radio" name="test" value="two"> Two</label>' . "\n" .
                '</div>',
                [StringEnum::A->value => 'One', StringEnum::B->value => 'Two'],
                StringEnum::A,
            ],
            ["<div>\n</div>", [], IntegerEnum::A],
            [
                '<div>' . "\n" .
                '<label><input type="radio" name="test" value="1"> One</label>' . "\n" .
                '<label><input type="radio" name="test" value="2"> Two</label>' . "\n" .
                '</div>',
                [IntegerEnum::A->value => 'One', IntegerEnum::B->value => 'Two'],
                IntegerEnum::C,
            ],
            [
                '<div>' . "\n" .
                '<label><input type="radio" name="test" value="1" checked> One</label>' . "\n" .
                '<label><input type="radio" name="test" value="2"> Two</label>' . "\n" .
                '</div>',
                [IntegerEnum::A->value => 'One', IntegerEnum::B->value => 'Two'],
                IntegerEnum::A,
            ],
        ];
    }

    #[DataProvider('dataValue')]
    public function testValue(string $expected, array $items, $value): void
    {
        $this->assertSame(
            $expected,
            RadioList::create('test')
                ->items($items)
                ->value($value)
                ->render(),
        );
    }

    public static function dataForm(): array
    {
        return [
            [
                '<div>' . "\n" .
                '<label><input type="radio" name="test" value="1"> One</label>' . "\n" .
                '<label><input type="radio" name="test" value="2"> Two</label>' . "\n" .
                '</div>',
                null,
            ],
            [
                '<div>' . "\n" .
                '<label><input type="radio" name="test" value="1" form> One</label>' . "\n" .
                '<label><input type="radio" name="test" value="2" form> Two</label>' . "\n" .
                '</div>',
                '',
            ],
            [
                '<div>' . "\n" .
                '<label><input type="radio" name="test" value="1" form="post"> One</label>' . "\n" .
                '<label><input type="radio" name="test" value="2" form="post"> Two</label>' . "\n" .
                '</div>',
                'post',
            ],
        ];
    }

    #[DataProvider('dataForm')]
    public function testForm(string $expected, ?string $formId): void
    {
        $this->assertSame(
            $expected,
            RadioList::create('test')
                ->items([
                    1 => 'One',
                    2 => 'Two',
                ])
                ->form($formId)
                ->render()
        );
    }

    public function testReadonly(): void
    {
        $checkbox = RadioList::create('test')
            ->items([
                1 => 'One',
                2 => 'Two',
            ]);

        $this->assertSame(
            '<div>' . "\n" .
            '<label><input type="radio" name="test" value="1" readonly> One</label>' . "\n" .
            '<label><input type="radio" name="test" value="2" readonly> Two</label>' . "\n" .
            '</div>',
            $checkbox
                ->readonly()
                ->render()
        );

        $this->assertSame(
            '<div>' . "\n" .
            '<label><input type="radio" name="test" value="1"> One</label>' . "\n" .
            '<label><input type="radio" name="test" value="2"> Two</label>' . "\n" .
            '</div>',
            $checkbox
                ->readonly(false)
                ->render()
        );
        $this->assertSame(
            '<div>' . "\n" .
            '<label><input type="radio" name="test" value="1"> One</label>' . "\n" .
            '<label><input type="radio" name="test" value="2"> Two</label>' . "\n" .
            '</div>',
            $checkbox
                ->readonly(true)
                ->readonly(false)
                ->render()
        );
    }

    public function testDisabled(): void
    {
        $checkbox = RadioList::create('test')
            ->items([
                1 => 'One',
                2 => 'Two',
            ]);

        $this->assertSame(
            '<div>' . "\n" .
            '<label><input type="radio" name="test" value="1" disabled> One</label>' . "\n" .
            '<label><input type="radio" name="test" value="2" disabled> Two</label>' . "\n" .
            '</div>',
            $checkbox
                ->disabled()
                ->render()
        );

        $this->assertSame(
            '<div>' . "\n" .
            '<label><input type="radio" name="test" value="1"> One</label>' . "\n" .
            '<label><input type="radio" name="test" value="2"> Two</label>' . "\n" .
            '</div>',
            $checkbox
                ->disabled(false)
                ->render()
        );
        $this->assertSame(
            '<div>' . "\n" .
            '<label><input type="radio" name="test" value="1"> One</label>' . "\n" .
            '<label><input type="radio" name="test" value="2"> Two</label>' . "\n" .
            '</div>',
            $checkbox
                ->disabled(true)
                ->disabled(false)
                ->render()
        );
    }

    public static function dataUncheckValue(): array
    {
        return [
            [
                '<div>' . "\n" .
                '<label><input type="radio" name="test" value="1"> One</label>' . "\n" .
                '<label><input type="radio" name="test" value="2"> Two</label>' . "\n" .
                '</div>',
                'test',
                null,
            ],
            [
                '<div>' . "\n" .
                '<label><input type="radio" name="test" value="1"> One</label>' . "\n" .
                '<label><input type="radio" name="test" value="2"> Two</label>' . "\n" .
                '</div>',
                'test',
                null,
            ],
            [
                '<input type="hidden" name="test" value="7">' . "\n" .
                '<div>' . "\n" .
                '<label><input type="radio" name="test" value="1"> One</label>' . "\n" .
                '<label><input type="radio" name="test" value="2"> Two</label>' . "\n" .
                '</div>',
                'test',
                7,
            ],
            [
                '<input type="hidden" name="test" value="7">' . "\n" .
                '<div>' . "\n" .
                '<label><input type="radio" name="test" value="1"> One</label>' . "\n" .
                '<label><input type="radio" name="test" value="2"> Two</label>' . "\n" .
                '</div>',
                'test',
                7,
            ],
        ];
    }

    #[DataProvider('dataUncheckValue')]
    public function testUncheckValue(string $expected, string $name, $value): void
    {
        $this->assertSame(
            $expected,
            RadioList::create($name)
                ->items([
                    1 => 'One',
                    2 => 'Two',
                ])
                ->uncheckValue($value)
                ->render()
        );
    }

    public function testUncheckValueDisabled(): void
    {
        $this->assertSame(
            '<input type="hidden" name="test" value="7" disabled>' . "\n" .
            '<label><input type="radio" name="test" value="1" disabled> One</label>' . "\n" .
            '<label><input type="radio" name="test" value="2" disabled> Two</label>',
            RadioList::create('test')
                ->items([
                    1 => 'One',
                    2 => 'Two',
                ])
                ->uncheckValue(7)
                ->disabled()
                ->withoutContainer()
                ->render(),
        );
    }

    public function testUncheckValueForm(): void
    {
        $this->assertSame(
            '<input type="hidden" name="test" value="7" form="post">' . "\n" .
            '<label><input type="radio" name="test" value="1" form="post"> One</label>' . "\n" .
            '<label><input type="radio" name="test" value="2" form="post"> Two</label>',
            RadioList::create('test')
                ->items([
                    1 => 'One',
                    2 => 'Two',
                ])
                ->uncheckValue(7)
                ->form('post')
                ->withoutContainer()
                ->render(),
        );
    }

    public function testSeparator(): void
    {
        $this->assertSame(
            '<div>' . "\n" .
            '<label><input type="radio" name="test" value="1"> One</label><br>' .
            '<label><input type="radio" name="test" value="2"> Two</label>' . "\n" .
            '</div>',
            RadioList::create('test')
                ->items([1 => 'One', 2 => 'Two'])
                ->separator('<br>')
                ->render(),
        );
    }

    public function testItemFormatter(): void
    {
        $this->assertSame(
            '<div>' . "\n" .
            '<div>0) <label><input type="radio" name="test" value="1"> One</label></div>' .
            '<div>1) <label><input type="radio" name="test" value="2"> Two</label></div>' . "\n" .
            '</div>',
            RadioList::create('test')
                ->items([1 => 'One', 2 => 'Two'])
                ->itemFormatter(fn (RadioItem $item): string => '<div>' .
                    $item->index . ') ' .
                    Html::radio(
                        $item->radioAttributes['name'],
                        $item->radioAttributes['value'],
                        $item->radioAttributes
                    )
                        ->checked($item->checked)
                        ->label($item->label) .
                    '</div>')
                ->separator('')
                ->render(),
        );
    }

    public function testDisableRadioLabelWrap(): void
    {
        $html = RadioList::create('test')
            ->items([1 => 'One', 2 => 'Two'])
            ->addIndividualInputAttributes([
                1 => ['id' => 'id1'],
                2 => ['id' => 'id2'],
            ])
            ->radioLabelWrap(false)
            ->render();

        $this->assertSame(
            <<<HTML
            <div>
            <input type="radio" id="id1" name="test" value="1"> <label for="id1">One</label>
            <input type="radio" id="id2" name="test" value="2"> <label for="id2">Two</label>
            </div>
            HTML,
            $html,
        );
    }

    public function testRadioWrap(): void
    {
        $html = RadioList::create('test')
            ->items([1 => 'One', 2 => 'Two'])
            ->radioWrapTag('div')
            ->radioWrapAttributes(['class' => 'form-check'])
            ->render();

        $this->assertSame(
            <<<HTML
            <div>
            <div class="form-check">
            <label><input type="radio" name="test" value="1"> One</label>
            </div>
            <div class="form-check">
            <label><input type="radio" name="test" value="2"> Two</label>
            </div>
            </div>
            HTML,
            $html,
        );
    }

    public static function dataRadioWrapClass(): array
    {
        return [
            ['', []],
            ['', [null]],
            [' class', ['']],
            [' class="main"', ['main']],
            [' class="main bold"', ['main bold']],
            [' class="main bold"', ['main', 'bold']],
        ];
    }

    #[DataProvider('dataRadioWrapClass')]
    public function testRadioWrapClass(string $expected, array $class): void
    {
        $html = RadioList::create('test')
            ->items([1 => 'One', 2 => 'Two'])
            ->radioWrapTag('div')
            ->radioWrapAttributes(['class' => 'form-check'])
            ->radioWrapClass(...$class)
            ->render();

        $this->assertSame(
            <<<HTML
            <div>
            <div$expected>
            <label><input type="radio" name="test" value="1"> One</label>
            </div>
            <div$expected>
            <label><input type="radio" name="test" value="2"> Two</label>
            </div>
            </div>
            HTML,
            $html,
        );
    }

    public static function dataAddRadioWrapClass(): array
    {
        return [
            [' class="form-check"', []],
            [' class="form-check"', [null]],
            [' class="form-check main"', ['main']],
            [' class="form-check main bold"', ['main bold']],
            [' class="form-check main bold"', ['main', 'bold']],
        ];
    }

    #[DataProvider('dataAddRadioWrapClass')]
    public function testAddRadioWrapClass(string $expected, array $class): void
    {
        $html = RadioList::create('test')
            ->items([1 => 'One', 2 => 'Two'])
            ->radioWrapTag('div')
            ->radioWrapAttributes(['class' => 'form-check'])
            ->addRadioWrapClass(...$class)
            ->render();

        $this->assertSame(
            <<<HTML
            <div>
            <div$expected>
            <label><input type="radio" name="test" value="1"> One</label>
            </div>
            <div$expected>
            <label><input type="radio" name="test" value="2"> Two</label>
            </div>
            </div>
            HTML,
            $html,
        );
    }

    public function testStringable(): void
    {
        $this->assertSame(
            "<div>\n</div>",
            (string) RadioList::create('test'),
        );
    }

    public function testImmutability(): void
    {
        $widget = RadioList::create('test');
        $this->assertNotSame($widget, $widget->name('test'));
        $this->assertNotSame($widget, $widget->withoutContainer());
        $this->assertNotSame($widget, $widget->containerTag(''));
        $this->assertNotSame($widget, $widget->containerAttributes([]));
        $this->assertNotSame($widget, $widget->radioWrapTag('div'));
        $this->assertNotSame($widget, $widget->radioWrapAttributes([]));
        $this->assertNotSame($widget, $widget->radioWrapClass());
        $this->assertNotSame($widget, $widget->addRadioWrapClass());
        $this->assertNotSame($widget, $widget->addRadioAttributes([]));
        $this->assertNotSame($widget, $widget->radioAttributes([]));
        $this->assertNotSame($widget, $widget->radioLabelWrap(false));
        $this->assertNotSame($widget, $widget->addIndividualInputAttributes([]));
        $this->assertNotSame($widget, $widget->individualInputAttributes([]));
        $this->assertNotSame($widget, $widget->items([]));
        $this->assertNotSame($widget, $widget->itemsFromValues([]));
        $this->assertNotSame($widget, $widget->value(null));
        $this->assertNotSame($widget, $widget->form(''));
        $this->assertNotSame($widget, $widget->readonly());
        $this->assertNotSame($widget, $widget->disabled());
        $this->assertNotSame($widget, $widget->uncheckValue(null));
        $this->assertNotSame($widget, $widget->separator(''));
        $this->assertNotSame($widget, $widget->itemFormatter(null));
    }
}
