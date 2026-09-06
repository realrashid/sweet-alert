<?php

use RealRashid\SweetAlert\Enums\AlertType;
use RealRashid\SweetAlert\Enums\InputType;
use RealRashid\SweetAlert\Enums\Position;

describe('Enums', function () {

    describe('AlertType', function () {
        it('has correct values for all alert types', function () {
            expect(AlertType::Success->value)->toBe('success');
            expect(AlertType::Error->value)->toBe('error');
            expect(AlertType::Warning->value)->toBe('warning');
            expect(AlertType::Info->value)->toBe('info');
            expect(AlertType::Question->value)->toBe('question');
        });

        it('has exactly 5 cases', function () {
            expect(AlertType::cases())->toHaveCount(5);
        });
    });

    describe('InputType', function () {
        it('has correct values for common input types', function () {
            expect(InputType::Text->value)->toBe('text');
            expect(InputType::Email->value)->toBe('email');
            expect(InputType::Password->value)->toBe('password');
            expect(InputType::Select->value)->toBe('select');
            expect(InputType::Checkbox->value)->toBe('checkbox');
            expect(InputType::Textarea->value)->toBe('textarea');
            expect(InputType::File->value)->toBe('file');
        });

        it('has correct count of input type cases', function () {
            expect(InputType::cases())->toHaveCount(19);
        });

        it('has Search and DatetimeLocal cases (BUG-12 fix)', function () {
            expect(InputType::Search->value)->toBe('search');
            expect(InputType::DatetimeLocal->value)->toBe('datetime-local');
        });
    });

    describe('Position', function () {
        it('has correct values for all positions', function () {
            expect(Position::Top->value)->toBe('top');
            expect(Position::TopStart->value)->toBe('top-start');
            expect(Position::TopEnd->value)->toBe('top-end');
            expect(Position::TopLeft->value)->toBe('top-left');
            expect(Position::TopRight->value)->toBe('top-right');
            expect(Position::Center->value)->toBe('center');
            expect(Position::CenterStart->value)->toBe('center-start');
            expect(Position::CenterEnd->value)->toBe('center-end');
            expect(Position::CenterLeft->value)->toBe('center-left');
            expect(Position::CenterRight->value)->toBe('center-right');
            expect(Position::Bottom->value)->toBe('bottom');
            expect(Position::BottomStart->value)->toBe('bottom-start');
            expect(Position::BottomEnd->value)->toBe('bottom-end');
            expect(Position::BottomLeft->value)->toBe('bottom-left');
            expect(Position::BottomRight->value)->toBe('bottom-right');
        });

        it('has exactly 15 positions', function () {
            expect(Position::cases())->toHaveCount(15);
        });
    });

});
