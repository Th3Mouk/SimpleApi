<?php

declare(strict_types=1);

expect()->extend('toBe42', fn () => $this->toBe(42));
