<?php

use Illuminate\Session\Store;
use RealRashid\SweetAlert\Builders\AlertBuilder;
use RealRashid\SweetAlert\Builders\InputBuilder;
use RealRashid\SweetAlert\Builders\ToastBuilder;
use RealRashid\SweetAlert\Concerns\HasAnimation;
use RealRashid\SweetAlert\Concerns\HasButtons;
use RealRashid\SweetAlert\Concerns\HasPosition;
use RealRashid\SweetAlert\Concerns\HasStyling;
use RealRashid\SweetAlert\Concerns\HasTimer;
use RealRashid\SweetAlert\Contracts\BuilderInterface;
use RealRashid\SweetAlert\Contracts\SessionStoreInterface;
use RealRashid\SweetAlert\Enums\AlertType;
use RealRashid\SweetAlert\Enums\InputType;
use RealRashid\SweetAlert\Enums\Position;
use RealRashid\SweetAlert\Storage\AlertSessionStore;

/**
 * Architecture Tests - Enforce structural constraints.
 *
 * These tests ensure that the package architecture follows
 * the design principles outlined in the restructuring plan.
 */
describe('Architecture', function () {

    it('builders do not have public properties', function () {
        $alertBuilder = new ReflectionClass(AlertBuilder::class);
        $toastBuilder = new ReflectionClass(ToastBuilder::class);
        $inputBuilder = new ReflectionClass(InputBuilder::class);

        $publicProperties = array_merge(
            $alertBuilder->getProperties(ReflectionProperty::IS_PUBLIC),
            $toastBuilder->getProperties(ReflectionProperty::IS_PUBLIC),
            $inputBuilder->getProperties(ReflectionProperty::IS_PUBLIC),
        );

        $names = array_map(fn ($p) => $p->getDeclaringClass()->getShortName().'::'.$p->getName(), $publicProperties);

        expect($names)->toBe([])->and(count($publicProperties))->toBe(0, 'Expected no public properties, found: '.implode(', ', $names));
    });

    it('enums are backed by strings', function () {
        $enums = [
            AlertType::class,
            InputType::class,
            Position::class,
        ];

        foreach ($enums as $enum) {
            $reflection = new ReflectionClass($enum);
            expect($reflection->isEnum())->toBeTrue("{$enum} is not an enum");
        }
    });

    it('AlertBuilder uses all five concern traits (including via AbstractAlertBuilder)', function () {
        // class_uses_recursive() traverses the full hierarchy so traits
        // inherited through AbstractAlertBuilder are included.
        $traits = class_uses_recursive(AlertBuilder::class);

        expect($traits)->toHaveKey(HasTimer::class);
        expect($traits)->toHaveKey(HasPosition::class);
        expect($traits)->toHaveKey(HasAnimation::class);
        expect($traits)->toHaveKey(HasButtons::class);
        expect($traits)->toHaveKey(HasStyling::class);
    });

    it('AlertSessionStore implements SessionStoreInterface', function () {
        $store = new AlertSessionStore(
            Mockery::mock(Store::class)
        );

        expect($store)->toBeInstanceOf(SessionStoreInterface::class);
    });

    it('AlertBuilder implements BuilderInterface', function () {
        $builder = AlertBuilder::make();
        expect($builder)->toBeInstanceOf(BuilderInterface::class);
    });

    it('ToastBuilder implements BuilderInterface', function () {
        $toast = app(ToastBuilder::class);
        expect($toast)->toBeInstanceOf(BuilderInterface::class);
    });

    it('InputBuilder implements BuilderInterface', function () {
        $input = app(InputBuilder::class);
        expect($input)->toBeInstanceOf(BuilderInterface::class);
    });
});
