<?php

namespace RealRashid\SweetAlert\Concerns;

use RealRashid\SweetAlert\Enums\Position;

/**
 * Trait HasPosition - Provides position-related configuration methods.
 *
 * This trait allows developers to set the position of alert modals
 * and toast notifications using either the Position enum or a string value.
 */
trait HasPosition
{
    /**
     * Set the position of the alert/toast.
     */
    public function position(string|Position $position = 'top-end'): static
    {
        $this->config['position'] = $position instanceof Position ? $position->value : $position;

        return $this->reflash();
    }

    /**
     * Set position to top.
     */
    public function top(): static
    {
        return $this->position(Position::Top);
    }

    /**
     * Set position to top-start.
     */
    public function topStart(): static
    {
        return $this->position(Position::TopStart);
    }

    /**
     * Set position to top-end.
     */
    public function topEnd(): static
    {
        return $this->position(Position::TopEnd);
    }

    /**
     * Set position to top-left.
     */
    public function topLeft(): static
    {
        return $this->position(Position::TopLeft);
    }

    /**
     * Set position to top-right.
     */
    public function topRight(): static
    {
        return $this->position(Position::TopRight);
    }

    /**
     * Set position to center.
     */
    public function center(): static
    {
        return $this->position(Position::Center);
    }

    /**
     * Set position to center-start.
     */
    public function centerStart(): static
    {
        return $this->position(Position::CenterStart);
    }

    /**
     * Set position to center-end.
     */
    public function centerEnd(): static
    {
        return $this->position(Position::CenterEnd);
    }

    /**
     * Set position to center-left.
     */
    public function centerLeft(): static
    {
        return $this->position(Position::CenterLeft);
    }

    /**
     * Set position to center-right.
     */
    public function centerRight(): static
    {
        return $this->position(Position::CenterRight);
    }

    /**
     * Set position to bottom.
     */
    public function bottom(): static
    {
        return $this->position(Position::Bottom);
    }

    /**
     * Set position to bottom-start.
     */
    public function bottomStart(): static
    {
        return $this->position(Position::BottomStart);
    }

    /**
     * Set position to bottom-end.
     */
    public function bottomEnd(): static
    {
        return $this->position(Position::BottomEnd);
    }

    /**
     * Set position to bottom-left.
     */
    public function bottomLeft(): static
    {
        return $this->position(Position::BottomLeft);
    }

    /**
     * Set position to bottom-right.
     */
    public function bottomRight(): static
    {
        return $this->position(Position::BottomRight);
    }
}
